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
	if ($c->category->getcount($tmp) ==1){
		//�ǡ���ȯ��
		$c->set("datas", $c->category->findone($tmp));
	}else{
		//�ǡ�����̵���Τ��᤹
		$c->redirect('edit_category.php');
	}
	if( count( $_POST ) )
    {
	//POST�Ǥʤ����äƤ���
	$data = array();//�ǡ�����
	
		//POST�ǡ����������
		$data["back"]=$c->s->postt("back");
		$data["del"]=$c->s->postt("del");
	//	echo var_dump($data);
	//	echo count($data["back"]);
		If ($data["back"] !==''){
		//�᤹
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_category.php');
		}
		If ($data["del"] !==''){
			//�����������
			//������˳������ƥ��꤬���ꤵ�줿�ǡ�����¸�ߤ����顢�����ʤ��ǡ����Ȥ�����Ͽ����
			//����ʤ������ä�
			unset($data["del"]);
			unset($data["update"]);
			unset($data["back"]);
			//�����ǡ������å�
			$data["category_cd"] = 0;

			//��Ͽ�����å�
			$data["touroku_date"] = $c->common_lib->get_date();
			$data["touroku_time"] = $c->common_lib->get_time();

			//���
			$tmp = array(
							'id' => $user_id,
							'category_cd' => $no
						);
			//��Ͽ
			$c->rss_data->updateby($data,$tmp);
			
			//���
			$tmp = array(
				'id' => $user_id,
				'no' => $no
			);
			$c->category->del($tmp);
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_category.php');

		}
	
	}

//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/del_category_ok.html" );

}

?>