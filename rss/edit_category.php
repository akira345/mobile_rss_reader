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

	//���顼��å�������
	$err2="";
	//�ǡ�����
	$data=array();

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
			$err2="";

			//POST�ǡ����������
			//�ơ��֥�ι���̾��Ʊ������̾�ˤ��뤳��
			$data["category"]=$c->s->postt("category_name");

			$data["del"]=$c->s->postt("del");
			$data["update"]=$c->s->postt("update");
			$data["no"]=$c->s->postt("no");

			If ($data["del"] !==''){
				//���
				If ($data["no"] !==''){
					//�������򥻥å�����
					$_SESSION["RSS"]["DEL"]["NO"] = $data["no"];
					//��ǧ�ڡ�����
					$c->redirect('del_category_ok.php');

				}
			}elseif($data["update"] !==''){
			//����
			//�ѥ�᥿�Υ����å�

				//ɬ������
				$err2 .= $c->v->notempty($data["category"],"���ƥ����ɬ�����ϤǤ�<BR>");

				if ($err2 == ""){
					//ͭ���ϰϥ����å�
					$err2 .= $c->v->len($data["category"],1,256,"���ƥ���ϣ�ʸ���ʾ�256ʸ���ʲ��Ǥ�<BR>");
				}
				//ǰ�Τ����ʣ��Ͽ�����å��򤹤�
				if ($err2 == ""){
					$tmp = array(
									'id' => $user_id,
									'category' => $data["category"]
								);
					if ($c->category->getcount($tmp) >0){
						//�ǡ���ȯ��
						$err2 .= "���Ǥ�Ʊ�쥫�ƥ��꤬��Ͽ����Ƥ��ޤ�<BR>";
					}
				}


				if ($err2 == ""){
					//���顼�ʤ�

					//�桼�����󥻥å�
					$data["id"] = $user_id;

					//��Ͽ�����å�
					$data["touroku_date"] = $c->common_lib->get_date();
					$data["touroku_time"] = $c->common_lib->get_time();

					//����ʤ������ä�
					unset($data["del"]);
					unset($data["update"]);

					//��Ͽ
					$c->category->updateby($data,'no=' . $c->category->escape($data["no"]));

					//��λ��å�����ɽ��
					$err2 = "��������λ���ޤ���";


				}else{
					//���顼����
					//�ǡ���ɽ�����ǥ��顼��å�������ɽ������
				}

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
		//�����оݤΥ쥳���ɤ����ä��鿧���Ѥ���
		$c->set("change_no",$data["no"]);
	}else{
		$err2 = "<font color=red>��Ͽ�ǡ���������ޤ���</font>";
		$tmp = array();
		$c->set("datas",$tmp);	//���������顼�ˤʤ��к�
	}

	//�ǡ������å�
	$c->set("err2",$err2,TRUE);

//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/edit_category.html" );
}
?>
