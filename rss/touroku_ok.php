<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{

//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/touroku_ok.html" );
}
?>