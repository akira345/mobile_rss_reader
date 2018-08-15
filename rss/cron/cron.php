<?php
//クーロン用
	require_once( "<サーバの絶対パス>/rss/component/rss_fetch.inc");
	define('MAGPIE_OUTPUT_ENCODING', 'utf-8');
	define('MAGPIE_CACHE_DIR','<サーバの絶対パス>/rss/component/cache');
	//ちいたんはあとに読み込ますこと
	require_once( "<サーバの絶対パス>/rss/config/config.php" );
	require_once( "<サーバの絶対パス>/cheetan/cheetan.php" );
function is_session()
{
//セッションは無効にする
    return false;
}
function action( &$c )
{
	//コントローラ
	//ユーザＩＤセット
	//メールアドレスが登録済みのユーザ
	If($c->login_user->getcount("'e-mail' <> ''") >0){
		$user_data = $c->login_user->find("'e-mail' != ''");
		foreach($user_data as $u_data){
			$user_id = $u_data["id"];
			$e_mail = $u_data["e-mail"];
			$tmp = array(
							'id' => $user_id,
							'haisin_flg' => '1',
						);
			If ($c->rss_data->getcount($tmp) > 0){
				//データあり
				$datas=$c->rss_data->find($tmp);
				$wk_body2 = '';//RSS単位の文面
				foreach($datas as $data){
					//キーワードを取得
					//念のため改行コードをそろえておく
					$keyword = explode("\n",str_replace(array("\r\n","\n","\r"), "\n", $data["keyword"]));
					//表示でつかうので退避
					$wk_keyword = $keyword;
					//キーワード文字の正規化を行う
					$keyword = $c->mylib->mb_convert_kana_variables($keyword,'rnaskh',$c->encoding);
					//キーワードの重複を除外
					$uniq_keyword = array_unique($keyword);
					//RSSのURL
					$url = $data["rss_url"];
					//コメント
					$comment = $data["comment"];
					//RSSのNO
					$rss_no = $data["no"];
					//RSSのパーズ
					$url = html_entity_decode($url, ENT_QUOTES);
					$rss = fetch_rss($url);
					$wk_body = '';//記事のタイトル、本文
					//携帯変換へのURL
					//Googleを使ってみる
					$keitai_cnv = 'http://www.google.co.jp/gwt/n?u=';
					//携帯変換が必要かどうか
					if($data["cnv_keitai"] == 0){
						$keitai_cnv = null;
					}
					foreach ($rss->items as $item){
						//ここの文字コードも取得できたら置換する
						//記事単位
						$title = strip_tags(mb_convert_encoding($item['title'],$c->encoding,'UTF8'));
						$summary = strip_tags(mb_convert_encoding($item['summary'],$c->encoding,'UTF8'));
						$wk_url = $keitai_cnv . htmlspecialchars(mb_convert_encoding($item['link'],$c->encoding,'UTF8'));
						//日付の変換
						If ($item['dc']['date']){
							//RSS1.0
							$wk_time = preg_replace('/T|[\+Z].+/', ' ',$item['dc']['date']); //2009-04-25 06:25:03
						}else{
							$wk_time = date('Y-m-d H:i:s',strtotime($item['pubdate'])); //2009-04-24 22:25:34
						}
						$match_keywords = '';//マッチングキーワードクリア
						//広告NG処理
						$ad_words = array("[PR]","【PR】","AD:","［PR］","AD：","広告：","PR:","PR：","Info:");
						//$ad_words = $c->settings->get_settings('ad_words');
						If ($c->common_lib->array_strpos($title,$ad_words) == TRUE){
							continue;	//以下の処理スキップ
						}
						//タイトルを取得し、すでにメール送信していないか確認
						$tmp = "user_id='" . $c->wk_send_rss->escape($user_id) . "' ";
						$tmp.= "and rss_id='" . $c->wk_send_rss->escape($rss_no). "' ";
						$tmp.= "and title='" . $c->wk_send_rss->escape($title). "' ";
						//再配信をしない
						If ($item['no_repert_flg'] != True){
							$tmp.= "and touroku_date >='" . date("Ymd",strtotime("-1 week")) ."'";//同じRSS,同じタイトルで配信する猶予期間は１週間
						}
						If($c->wk_send_rss->getcount($tmp) == 0){
						//記事の中にキーワードにマッチしたものがあるか？
							foreach ($uniq_keyword as $key){
						//タイトルもマッチングの対象に変更20100318
						//		If (stripos(mb_convert_kana($summary,'rnaskh',$c->encoding),$key) ===FALSE){
								If (stripos(mb_convert_kana($title . $summary,'rnaskh',$c->encoding),$key) ===FALSE){
									//不一致はなにもしない
								}else{
										//一致したキーワードをセット
										//まずは正規化したキーワードの配列キー取得
										$match_key = array_search($key,$keyword);
										//次に該当する配列キーから登録されたキーワードを取得する
										$match_keywords .= '[' . $wk_keyword[$match_key] . ']';
								}
							}
						}
						If (($match_keywords)!=''){
							//マッチしたキーワードがあった
							//再送の場合はタイトルに(再)をつける
							$tmp=array(
										'user_id' => $user_id,
										'rss_id' => $rss_no,
										'title' => $title
										);
							If ($c->wk_send_rss->getcount($tmp) !=0){
								$resend_flg = 1;
							}else{
								$resend_flg = 0;
							}
							//メール送信テーブルに記録
							$tmp=array(
										'user_id' => $user_id,
										'rss_id' => $rss_no,
										'title' => $title,
										'touroku_date' => $c->common_lib->get_date()
										);
							$c->wk_send_rss->insert($tmp);
							If ($resend_flg !=0){
								$title = "(再)" . $title;
							}
							//メール用文面構築
							$wk_body .= "タイトル：" .$title . "\n";	//タイトル
							$wk_body .= "(" . $wk_time . ")" . "\n";	//記事の日付
							$wk_body .= "マッチしたキーワード：" . $match_keywords . "\n";
							$wk_body .= $summary . "\n";	//本文
							$wk_body .= "link:" . $wk_url . "\n";
							$wk_body .= "--------------------------------------------------------------------" . "\n\n";
						}
					}
					If (($wk_body)!=''){
						//RSS単位
						$wk_body2 .= "RSS:「" . $comment . "」" . "\n\n";
						$wk_body2 .= $wk_body;
					}
				}
				If (($wk_body2)!=''){
					echo "メール送信！！";
					$wk_body2 .= "\n\n携帯RSSリーダ：http://exsample.com/rss/" . "\n";
				//	echo $wk_body2;
					////
					mb_language("Ja") ;
					mb_internal_encoding("UTF-8") ;
					$mailto=$e_mail;
					$subject="RSSマッチングレポート(".$c->common_lib->get_date().")";
					$content="キーワードにマッチングした記事を送信いたします。"."\n\n".$wk_body2;
					$mailfrom="From:info@exsample.com";
					mb_send_mail($mailto,$subject,$content,$mailfrom);
				}
			}
		}
	}
}
?>
