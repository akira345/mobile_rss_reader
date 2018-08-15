<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//リロード対策
	$token = sha1(uniqid(mt_rand(), true));
	 // トークンをセッションに追加する
	$_SESSION['token'][] = $token;
	//ユーザＩＤセット
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//トークンを出力
	$c->set("token",$token,'FALSE');

	//エラーメッセージ用
	$err2="";
	//データ用
	$data=array();

    if( count( $_POST ) )
    {
	//POSTでなんか入っている
	//リロード対策
		// 送信されたトークンがセッションのトークン配列の中にあるか調べる
		$key = array_search($_POST['token'], $_SESSION['token']);

		if ($key !== false) {
		    // 正常な POST
		    unset($_SESSION['token'][$key]); // 使用済みトークンを破棄
			//データ登録用
			$data=array();

			//エラーメッセージ用
			$err2="";

			//POSTデータを入れる
			$data["rss_url"]=$c->s->postt("rss_url");
			$data["comment"]=$c->s->postt("comment");
			$data["cnv_keitai"]=$c->s->postt("cnv_keitai");
			$data["view_cnt"]=$c->s->postt("view_cnt");
			$data["category_cd"]=$c->s->postt("category_cd");
			$data["hidden_chk"]=$c->s->postt("hidden_chk");
			$data["del"]=$c->s->postt("del");
			$data["update"]=$c->s->postt("update");
			$data["no"]=$c->s->postt("no");
			//メール送信チェック、キーワード追加
			$data["haisin_flg"]=$c->s->postt("haisin_flg");
			$data["keyword"]=$c->s->postt("keyword");


			If ($data["del"] !==''){
				//削除
				If ($data["no"] !==''){
					//削除情報をセッションへ
					$_SESSION["RSS"]["DEL"]["NO"] = $data["no"];
					//確認ページへ
					$c->redirect('del_ok.php');

				}
			}elseif($data["update"] !==''){
			//更新
			//パラメタのチェック

				//必須入力
				$err2 .= $c->v->notempty($data["rss_url"],"RSSは必須入力です<BR>");
				$err2 .= $c->v->notempty($data["comment"],"コメントは必須入力です<BR>");
				$err2 .= $c->v->notempty($data["view_cnt"],"表示件数は必須入力です<BR>");
				$err2 .= $c->v->notempty($data["category_cd"],"カテゴリは必須入力です<BR>");

				if ($err2 == ""){
					//英数字チェック
					//記号が含まれるのでチェックをパス
					//$err2 .= $c->v->eisu($data["rss_url"],"RSSは半角英数字のみです<BR>");
				}
				if ($err2 == ""){
					//数字チェック
					$err2 .= $c->v->number($data["view_cnt"],"表示件数は半角数字のみです<BR>");
					$err2 .= $c->v->number($data["category_cd"],"カテゴリの選択が不正です<BR>");
				}
				if ($err2 == ""){
					//URLチェック
					//ちいたんのバリテートを拡張したかったが良く分からなかったので共通クラスで処理する
					$err2 .= $c->common_lib->chk_url($data["rss_url"],"RSSのアドレスが不正です<BR>");
				}
				if ($err2 == ""){
					//有効範囲チェック
					$err2 .= $c->v->len($data["rss_url"],1,2000,"RSSは１文字以上2000文字以下です<BR>");
					$err2 .= $c->v->len($data["comment"],1,512,"コメントは全角１文字以上256文字以下です<BR>");
					$err2 .= $c->v->len($data["view_cnt"],1,3,"表示件数は１文字以上3文字以下です<BR>");
					$err2 .= $c->v->len($data["category_cd"],1,2,"カテゴリの選択が不正です<BR>");
				}
					//念のため重複登録チェックをする
				if ($err2 == ""){
					//自分以外で同一ＵＲＬがあったらＮＧ
					$tmp =  "`id` = '" . $c->rss_data->escape($user_id) . "' and ";
					$tmp .= "`rss_url` = '" . $c->rss_data->escape($data["rss_url"]) . "' and ";
					$tmp .= "`no` != '" . $c->rss_data->escape($data["no"]) . "'";
					if ($c->rss_data->getcount($tmp) >0){
						//データ発見
						$err2 .= "すでに同一RSSが登録されています<BR>";
					}
				}


				if ($err2 == ""){
					//エラーなし

					//携帯変換のチェックボックスを変換する
					If($data["cnv_keitai"]==''){
						$data["cnv_keitai"] = 0;
					}else{
						$data["cnv_keitai"] = 1;
					}
					//非表示チェックボックスを変換する
					If($data["hidden_chk"]==''){
						$data["hidden_chk"] = 0;
					}else{
						$data["hidden_chk"] = 1;
					}
					//メール配信フラグのチェックボックスを変換する
					If($data["haisin_flg"]==''){
						$data["haisin_flg"] = 0;
					}else{
						$data["haisin_flg"] = 1;
					}

					//ユーザ情報セット
					$data["id"] = $user_id;

					//登録日セット
					$data["touroku_date"] = $c->common_lib->get_date();
					$data["touroku_time"] = $c->common_lib->get_time();

					//いらない配列を消す
					unset($data["del"]);
					unset($data["update"]);

					//登録
					$c->rss_data->updateby($data,'no=' . $c->rss_data->escape($data["no"]));

					//完了メッセージ表示
					$err2 = "更新が完了しました";


				}else{
					//エラーあり
					//データ表示部でエラーメッセージを表示する
				}

			}
		}
	}
	//データ表示
	//データ検索
	$tmp = array(
					'id' => $user_id
				);
	if ($c->rss_data->getcount($tmp) >0){
		//データ発見
		$c->set("datas", $c->rss_data->find($tmp, "no ASC" ));	//NOで昇順にソート
		//更新対象のレコードがあったら色を変える
		$c->set("change_no",$data["no"]);
	}else{
		$err2 = "<font color=red>登録データがありません</font>";
		$tmp = array();
		$c->set("datas",$tmp);	//ゼロ件時エラーになる対策
	}
	//データセット
	$c->set("err2",$err2,TRUE);

//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/edit_rss.html" );
}
?>
