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
			//テーブルの項目名と同じ配列名にすること
			$data["category"]=$c->s->postt("category_name");

			$data["del"]=$c->s->postt("del");
			$data["update"]=$c->s->postt("update");
			$data["no"]=$c->s->postt("no");

			If ($data["del"] !==''){
				//削除
				If ($data["no"] !==''){
					//削除情報をセッションへ
					$_SESSION["RSS"]["DEL"]["NO"] = $data["no"];
					//確認ページへ
					$c->redirect('del_category_ok.php');

				}
			}elseif($data["update"] !==''){
			//更新
			//パラメタのチェック

				//必須入力
				$err2 .= $c->v->notempty($data["category"],"カテゴリは必須入力です<BR>");

				if ($err2 == ""){
					//有効範囲チェック
					$err2 .= $c->v->len($data["category"],1,256,"カテゴリは１文字以上256文字以下です<BR>");
				}
				//念のため重複登録チェックをする
				if ($err2 == ""){
					$tmp = array(
									'id' => $user_id,
									'category' => $data["category"]
								);
					if ($c->category->getcount($tmp) >0){
						//データ発見
						$err2 .= "すでに同一カテゴリが登録されています<BR>";
					}
				}


				if ($err2 == ""){
					//エラーなし

					//ユーザ情報セット
					$data["id"] = $user_id;

					//登録日セット
					$data["touroku_date"] = $c->common_lib->get_date();
					$data["touroku_time"] = $c->common_lib->get_time();

					//いらない配列を消す
					unset($data["del"]);
					unset($data["update"]);

					//登録
					$c->category->updateby($data,'no=' . $c->category->escape($data["no"]));

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
	if ($c->category->getcount($tmp) >0){
		//データ発見
		$c->set("datas", $c->category->find($tmp, "no ASC" ));	//NOで昇順にソート
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
	$c->SetViewFile( "./tmplate/edit_category.html" );
}
?>
