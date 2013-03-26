<?php

	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}

function action( &$c )
{	//リロード対策
	$token = sha1(uniqid(mt_rand(), true));

	 // トークンをセッションに追加する
	$_SESSION['token'][] = $token;

	//ユーザＩＤセット
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//トークンを出力
	$c->set("token",$token,'FALSE');

	//エラーメッセージ用(一覧表示)
	$err2="";

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
			$err="";

			//POSTデータを入れる
			$data["rss_url"]=$c->s->postt("rss_url");
			$data["comment"]=$c->s->postt("comment");
			$data["cnv_keitai"]=$c->s->postt("cnv_keitai");
			$data["view_cnt"]=$c->s->postt("view_cnt");
			$data["category_cd"]=$c->s->postt("category_cd");
			//メール送信チェック、キーワード追加
			$data["haisin_flg"]=$c->s->postt("haisin_flg");
			$data["keyword"]=$c->s->postt("keyword");


			//パラメタのチェック

			//必須入力
			$err .= $c->v->notempty($data["rss_url"],"RSSは必須入力です<BR>");
			$err .= $c->v->notempty($data["comment"],"コメントは必須入力です<BR>");
			$err .= $c->v->notempty($data["view_cnt"],"表示件数は必須入力です<BR>");
			$err .= $c->v->notempty($data["category_cd"],"カテゴリは必須入力です<BR>");

			if ($err == ""){
				//英数字チェック
				//記号が含まれるのでチェックをパス
				//$err .= $c->v->eisu($data["rss_url"],"RSSは半角英数字のみです<BR>");
			}
			if ($err == ""){
				//数字チェック
				$err .= $c->v->number($data["view_cnt"],"表示件数は半角数字のみです<BR>");
				$err .= $c->v->number($data["category_cd"],"カテゴリの選択が不正です<BR>");
			}
			if ($err == ""){
				//URLチェック
				//ちいたんのバリテートを拡張したかったが良く分からなかったので共通クラスで処理する
				$err .= $c->common_lib->chk_url($data["rss_url"],"RSSのアドレスが不正です<BR>");
			}
			if ($err == ""){
				//有効範囲チェック
				$err .= $c->v->len($data["rss_url"],1,2000,"RSSは１文字以上2000文字以下です<BR>");
				$err .= $c->v->len($data["comment"],1,512,"コメントは全角１文字以上256文字以下です<BR>");
				$err .= $c->v->len($data["view_cnt"],1,3,"表示件数は１文字以上3文字以下です<BR>");
				$err .= $c->v->len($data["category_cd"],1,2,"カテゴリの選択が不正です<BR>");
			}

			//念のため重複登録チェックをする
			if ($err == ""){
				$tmp = array(
								'id' => $user_id,
								'rss_url' => $data["rss_url"]
							);
				if ($c->rss_data->getcount($tmp) >0){
					//データ発見
					$err .= "すでに同一RSSが登録されています<BR>";
				}
			}

			if ($err == ""){
				//エラーなし

				//携帯変換のチェックボックスを変換する
				If($data["cnv_keitai"]==''){
					$data["cnv_keitai"] = 0;
				}else{
					$data["cnv_keitai"] = 1;
				}
				//メール配信フラグのチェックボックスを変換する
				If($data["haisin_flg"]==''){
					$data["haisin_flg"] = 0;
				}else{
					$data["haisin_flg"] = 1;
				}

				//ユーザ情報、初期値セット
				$data["id"] = $user_id;
				$data["hidden_chk"] = 0;

				//登録日セット
				$data["touroku_date"] = $c->common_lib->get_date();
				$data["touroku_time"] = $c->common_lib->get_time();

				//登録
				$c->rss_data->insert($data);

				//完了メッセージ表示
				$err = "登録が完了しました";

				$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON

			}else{
				//エラーあり
				$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON

				//データセット
				$c->set("rss_url",$data["rss_url"],'TRUE');
				$c->set("comment",$data["comment"]);
				$c->set("cnv_keitai",$data["cnv_keitai"]);
				$c->set("view_cnt",$data["view_cnt"]);
				$c->set("category_cd",$data["category_cd"]);
				//メール配信フラグ、キーワード追加
				$c->set("haisin_flg",$data["haisin_flg"]);
				$c->set("keyword",$data["keyword"]);


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
	}else{
		$err2 = "登録データがありません";
		$tmp = array();
		$c->set("datas",$tmp);	//ゼロ件時エラーになる対策
	}

	//データセット
	$c->set("err2",$err2,TRUE);//エラーメッセージはタグ出力ON

//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/add_rss.html" );

}

?>