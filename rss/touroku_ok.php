<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{

//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/touroku_ok.html" );
}
?>