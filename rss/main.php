<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
//����ȥ���
	$id = $_SESSION["RSS"]["USER"]["id"];
	$c->set("last_login",$c->login_his->last_login($id),TRUE);
//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/main.html" );
}
?>