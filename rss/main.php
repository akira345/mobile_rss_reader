<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//���[�U�F�؂�������
function is_secure(&$c){
	return true;
}
function action( &$c )
{
//�R���g���[��
	$id = $_SESSION["RSS"]["USER"]["id"];
	$c->set("last_login",$c->login_his->last_login($id),TRUE);
//�e���v���[�g�t�@�C���w��
	$c->SetViewFile( "./tmplate/main.html" );
}
?>