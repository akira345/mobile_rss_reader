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

			//id
			$id = $_SESSION["RSS"]["USER"]["id"];

			//POSTデータを入れる
			$data["password"]=$c->s->postt("pw");
			$data["password2"]=$c->s->postt("pw2");

			//パラメタのチェック

			//必須入力
			$err .= $c->v->notempty($data["password"],"パスワードは必須入力です<BR>");
			$err .= $c->v->notempty($data["password2"],"確認用パスワードは必須入力です<BR>");

			if ($err == ""){
				//同じかチェック
				If ($data["password"] <> $data["password2"]){
					$err .= "確認用パスワードが一致しません<BR>";
				}
			}

			if ($err == ""){
				//英数字チェック
				$err .= $c->v->eisu($data["password"],"パスワードは半角英数字のみです<BR>");
			}

			if ($err == ""){

				//パスワード変更
				$data=array(
							'id' => $id,
							'password' => $data["password"]
							);
				If($c->login_user->change_password($data)){
					//パスワード変更したらセッションを切る
					If($c->login_user->log_out($data)){
						$c->redirect('ok.php');
					}
				}
				//エラー
					$err .= $c->login_user->show_msg();
			}

			//この下はエラーがある場合しか動かない
				//エラーあり
				$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON

				$c->set("pw",$data["password"]);
				$c->set("pw2",$data["password2"]);
		}
	}
//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/chg_pw.html" );
}
?>
