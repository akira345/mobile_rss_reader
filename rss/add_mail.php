<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}

function action( &$c )
{
//コントローラ
	//リロード対策
	$token = sha1(uniqid(mt_rand(), true));
	 // トークンをセッションに追加する
	$_SESSION['token'][] = $token;
	//トークンを出力
	$c->set("token",$token,'FALSE');
	//id
	$id = $_SESSION["RSS"]["USER"]["id"];
	//メールが登録されている場合表示する
	If($c->login_user->getcount("'e-mail' <> '' and id = '" . $c->login_user->escape($id) . "'")>0){
		$tmp=array(
					'id' => $id
					);
		$data = $c->login_user->findone($tmp);
		$c->set("e-mail",$data["e-mail"]);
	}

	if(count( $_POST) ){
		//リロード対策
		// 送信されたトークンがセッションのトークン配列の中にあるか調べる
		$key = array_search($_POST['token'], $_SESSION['token']);

		if ($key !== false) {
		    // 正常な POST
		    unset($_SESSION['token'][$key]); // 使用済みトークンを破棄
			//エラーメッセージ用
			$err="";
			//データ用
			$data=array();


			//POSTデータを入れる
			$data["e-mail"]=$c->s->postt("e-mail");
			$data["e-mail2"]=$c->s->postt("e-mail2");

			//パラメタのチェック

			//同じかチェック
			If ($data["e-mail"] <> $data["e-mail2"]){
				$err .= "確認用メールアドレスが一致しません<BR>";
			}

			//メールアドレスチェック
			if ($err == "" And $data["e-mail"] <> ""){
				//メアドチェック
				$err .= $c->v->email($data["e-mail"],"メールアドレスが有効ではありません<BR>");
			}


			if ($err == ""){

				//パスワード変更
				$data=array(
							'id' => $id,
							'e-mail' => $data["e-mail"]
							);
				If($c->login_user->add_email($data)){
					$c->redirect('touroku_ok.php');
				}
				//エラー
					$err .= $c->login_user->show_msg();
			}

			//この下はエラーがある場合しか動かない
				//エラーあり
				$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON

				$c->set("e-mail",$data["e-mail"]);
				$c->set("e-mail2",$data["e-mail2"]);
		}
	}
//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/add_mail.html" );
}
?>
