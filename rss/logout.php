<?php

	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//���������Ƚ���
	$data = array();
	$id = $_SESSION["RSS"]["USER"]["id"];
	$data = array(
					"id" => $id
				);
	If($c->login_user->log_out($data)){
	//TOP��
		$c->redirect('index.php');
	}else{
		echo $c->login_user->show_msg();
	}

}

?>