<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//���󥻥å�
	$no = $_SESSION["RSS"]["DEL"]["NO"];
	//�桼���ɣĥ��å�
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//�ǡ����ɤ߹���
	$tmp = array(
				'id' => $user_id,
				'no' => $no
			);
	if ($c->rss_data->getcount($tmp) ==1){
		//�ǡ���ȯ��
		$c->set("datas", $c->rss_data->findone($tmp));
	}else{
		//�ǡ�����̵���Τ��᤹
		$c->redirect('edit_rss.php');
	}
	if( count( $_POST ) )
    {
	//POST�Ǥʤ����äƤ���

		//POST�ǡ����������
		$data["back"]=$c->s->postt("back");
		$data["del"]=$c->s->postt("del");
	//	echo var_dump($data);
	//	echo count($data["back"]);
		If ($data["back"] !==''){
		//�᤹
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_rss.php');
		}
		If ($data["del"] !==''){
			//�����������
			$tmp = array(
				'id' => $user_id,
				'no' => $no
			);
			$c->rss_data->del($tmp);
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_rss.php');
		}

	}
//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/del_ok.html" );
}
?>