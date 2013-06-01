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
	$id = $_SESSION["RSS"]["USER"]["id"];
	$c->set("last_login",$c->login_his->last_login($id),TRUE);
//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/main.html" );
}
?>