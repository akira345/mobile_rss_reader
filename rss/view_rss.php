<?php
	require_once( "./component/rss_fetch.inc");
	define('MAGPIE_OUTPUT_ENCODING', 'utf-8');
	define('MAGPIE_CACHE_DIR','./component/cache');//ライブラりからのパス
	//ちいたんはあとに読み込ますこと
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//コントローラ
	//ユーザＩＤセット
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	If (count($_GET)){
		//Getに何か入っている
		//データ登録用
		$data=array();
		//GETデータを入れる
		$data["rss_cd"]=$c->s->gett("rss_cd");
		If ($data["rss_cd"] != ""){
			//コードあり
			$tmp = array(
							'id' => $user_id,
							'no' => $data["rss_cd"]
						);
			If ($c->rss_data->getcount($tmp) >0){
				//データあり
				$datas=$c->rss_data->findone($tmp);
				//RSSのURL
				$url = $datas["rss_url"];
				//携帯変換へのURL
				//Googleを使ってみる
				$keitai_cnv = 'http://www.google.co.jp/gwt/n?u=';
				//携帯変換が必要かどうか
				if($datas["cnv_keitai"] == 0){
					$keitai_cnv = null;
				}
				//RSSのパーズ
				$url = html_entity_decode($url, ENT_QUOTES);
				$rss = fetch_rss($url);
				//ページング処理
				if (count($_GET['page'])){
					$now_page = $c->s->gett("page");
				}else{
					$now_page = 1;
				}
				//広告除去の為、一回RSSを展開する
				$rss_data = array();
				foreach ($rss->items as $item){
					$title = strip_tags(mb_convert_encoding($item['title'],$c->encoding,'UTF8'));
					$summary = strip_tags(mb_convert_encoding($item['summary'],$c->encoding,'UTF8'));
					$summary = mb_strimwidth($summary,0,100,"...",$c->encoding);	//100文字まで
					$wk_url = $keitai_cnv . htmlspecialchars(mb_convert_encoding($item['link'],$c->encoding,'UTF8'));
					//広告NG処理
					$ad_words = array("[PR]","【PR】","AD:","［PR］","AD：","広告：","PR:","PR：","Info:");
					If ($c->common_lib->array_strpos($title,$ad_words) == TRUE){
						continue;	//以下の処理スキップ
					}
					$tmp = array(
								"title" => $title,
								"summary" => $summary,
								"url" => $wk_url
								);
					array_push($rss_data,$tmp);
				}



				//全データ件数取得
				//$all_count = count($rss->items);
				$all_count = count($rss_data);
				//表示ページ数
				If($datas["view_cnt"] == 0){
					$datas["view_cnt"] = 9999;//ページングさせない
				}
				//ページング設定
				$option = array(
					'baseUrl'	=> 'view_rss.php',		// リンクのURL
					'queryStr'	=> htmlspecialchars( SID,ENT_QUOTES ) . '&rss_cd=' . $data["rss_cd"] .'&page',			// クエリー文字列
					'curPage'	=> $now_page,		// 現在のページ番号
					'perPage'	=> $datas["view_cnt"],				// 1画面当たりのリスト数
					'totalRows'  	=> $all_count,	// リストの合計数
					'numLinks'	=> 2,				// 前後のリンク数
					'pageSummary'	=> TRUE,		// サマリーの表示
					'firstLink'	=> '≪',			// "最初" のページへのリンク文字列
					'prevLink'	=> '＜',			// "前" のページへのリンク文字列
					'nextLink'	=> '＞',			// "次" のページへのリンク文字列
					'lastLink'	=> '≫',			// "最後" のページへのリンク文字列
					'fullTagOpen'	=> '<ul>',		// ページネーションの開始タグ
					'fullTagClose'	=> '</ul>',		// ページネーションの終了タグ
					'linkTagOpen'	=> '',			// ページリンクの開始タグ
					'linkTagClose'	=> '',			// ページリンクの終了タグ
					'curTagOpen'	=> '<B>',		// "現在" のページの番号の開始タグ
					'curTagClose'	=> '</B>',		// "現在" のページの番号の終了タグ
				);
				//ページング初期化
				$c->pagination->initialize( $option );
				//RSSデータをページごとに分割する
			//	$data = $c->pagination->slice( $rss->items);
				$data = $c->pagination->slice( $rss_data);
				//タイトル
				$c->set("title",$datas["comment"],'FALSE');
				//ページング表示
				$c->set("pager",$c->pagination->create_links(),'TRUE');
				//データ
				$c->set("datas",$data,'TRUE');
				//テンプレートファイル指定
				$c->SetViewFile( "./tmplate/rss.html" );
			}else{
				//データなし
			}
		}else{
		//コードなし
		}
	}else{
	//Getなし
	}
}
?>
