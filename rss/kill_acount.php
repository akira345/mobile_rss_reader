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
	if(count( $_POST) ){
		//���顼��å�������
		$err="";
		//�ǡ�����
		$data=array();
		//id
		$id = $_SESSION["RSS"]["USER"]["id"];
		If($_POST["kill"]){
			//��������Ⱥ��
			$data = array(
						"id" => $id
						);
			$c->login_user->kill_acount($data);
		}
		//�ԣϣФإ�����쥯��
		$c->redirect('index.php');
	}
//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/kill_acount.html" );
}
?>