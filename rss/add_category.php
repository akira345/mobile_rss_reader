<?php

	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//������к�
	$token = sha1(uniqid(mt_rand(), true));

	 // �ȡ�����򥻥å������ɲä���
	$_SESSION['token'][] = $token;
	//�桼���ɣĥ��å�
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//�ȡ���������
	$c->set("token",$token,'FALSE');
	
	//���顼��å�������(����ɽ��)
	$err2="";
	
    if( count( $_POST ) )
    {
	//POST�Ǥʤ����äƤ���
	
	//������к�
		// �������줿�ȡ����󤬥��å����Υȡ������������ˤ��뤫Ĵ�٤�
		$key = array_search($_POST['token'], $_SESSION['token']);
		
		if ($key !== false) {
		    // ����� POST
		    unset($_SESSION['token'][$key]); // ���ѺѤߥȡ�������˴�

			//�ǡ�����Ͽ��
			$data=array();
		
			//���顼��å�������
			$err="";
			
			//POST�ǡ����������
			//�ơ��֥�ι���̾��Ʊ������̾�ˤ��뤳��
			$data["category"]=$c->s->postt("category_name");
	
			//�ѥ�᥿�Υ����å�
			
			//ɬ������
			$err .= $c->v->notempty($data["category"],"���ƥ����ɬ�����ϤǤ�<BR>");
			
			if ($err == ""){
				//ͭ���ϰϥ����å�
				$err .= $c->v->len($data["category"],1,256,"���ƥ���ϣ�ʸ���ʾ�256ʸ���ʲ��Ǥ�<BR>");
			}
			//ǰ�Τ����ʣ��Ͽ�����å��򤹤�
			if ($err == ""){
				$tmp = array(
								'id' => $user_id,
								'category' => $data["category"]
							);
				if ($c->category->getcount($tmp) >0){
					//�ǡ���ȯ��
					$err .= "���Ǥ�Ʊ�쥫�ƥ��꤬��Ͽ����Ƥ��ޤ�<BR>";
				}
			}
	
			if ($err == ""){
				//���顼�ʤ�
				
				//�桼�����󡢽���ͥ��å�
				$data["id"] = $user_id;
				
				//��Ͽ�����å�
				$data["touroku_date"] = $c->common_lib->get_date();
				$data["touroku_time"] = $c->common_lib->get_time();
				
				//��Ͽ
				$c->category->insert($data);
				
				//��λ��å�����ɽ��
				$err = "��Ͽ����λ���ޤ���";
				
				$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON
				
			}else{
				//���顼����
				$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON
				
				//�ǡ������å�
				$c->set("category_name",$data["category"]);
				
			}
		}
	}
	//�ǡ���ɽ��
	//�ǡ�������
	$tmp = array(
					'id' => $user_id
				);
	if ($c->category->getcount($tmp) >0){
		//�ǡ���ȯ��
		$c->set("datas", $c->category->find($tmp, "no ASC" ));	//NO�Ǿ���˥�����
	}else{
		$err2 = "��Ͽ�ǡ���������ޤ���";
		$tmp = array();
		$c->set("datas",$tmp);	//���������顼�ˤʤ��к�
	}
	
	//�ǡ������å�
	$c->set("err2",$err2,TRUE);//���顼��å������ϥ�������ON

//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/edit_category.html" );

}

?>