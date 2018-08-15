<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
function action( &$c )
{
    if( count( $_POST ) )
    {
	//POSTでなんか入っている

		//データ登録用
		$data=array();

		//エラーメッセージ用
		$err="";

		//POSTデータを入れる
		$data["user_name"]=$c->s->postt("name");
		$data["password"]=$c->s->postt("pw");
		//パラメタのチェック

		//必須入力
		$err .= $c->v->notempty($data["user_name"],"IDは必須入力です<BR>");
		$err .= $c->v->notempty($data["password"],"パスワードは必須入力です<BR>");

		if ($err == ""){
			//英数字チェック
			$err .= $c->v->eisu($data["user_name"],"IDは半角英数字のみです<BR>");
			$err .= $c->v->eisu($data["password"],"パスワードは半角英数字のみです<BR>");
		}
		if ($err == ""){
			//有効範囲チェック
			$err .= $c->v->len($data["user_name"],1,100,"IDは１文字以上１００文字以下です<BR>");
		}
		if ($err == ""){
			//エラーなし

			//パラメタセット
			$data = array(
					"user_name" => $data["user_name"],
					"password" => $data["password"]
					);
			//登録
			If($c->login_user->touroku($data)){

				//完了ページへ
				$c->redirect('ok.php');
			}else{
				//登録エラー
				$err = $c->login_user->show_msg();
			}
		}
		//ここにはエラーの場合しか来ない
		//エラーあり
		$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON

		$c->set("name",$data["user_name"]);
		$c->set("pw",$data["password"]);
	}
//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/add.html" );
}
?>
