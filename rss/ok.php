<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
function action( &$c )
{

//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/ok.html" );
}
?>