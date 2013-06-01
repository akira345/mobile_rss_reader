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
	if(count( $_POST) ){
		//エラーメッセージ用
		$err="";
		//データ用
		$data=array();
		//id
		$id = $_SESSION["RSS"]["USER"]["id"];
		If($_POST["kill"]){
			//アカウント削除
			$data = array(
						"id" => $id
						);
			$c->login_user->kill_acount($data);
		}
		//ＴＯＰへリダイレクト
		$c->redirect('index.php');
	}
//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/kill_acount.html" );
}
?>