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

	//�桼���ɣĥ��å�
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//�ǡ�����Ͽ��
	$data=array();
	//�ѥ�᥿��
	$tmp = array();
	//���Ӹ�ͭ�ֹ����
	$c->easy_login->MobileInformation();
	$mobile_key = $c->easy_login->IndividualNum();
	//��ñ��������Ͽ��ۤ��Υڡ����عԤ�����ä��Ȥ����٥�å��������Ф��к�
	If ($mobile_key != 0){
		$tmp = array(
						'mb_key' => $mobile_key
					);
		if ($c->login_user->getcount($tmp) >0){
			//�ǡ���ȯ��
			$c->set("easy_login_chk","FALSE");
		}else{
			$c->set("easy_login_chk","TRUE");
		}
	}else{
		$c->set("easy_login_chk","TRUE");
	}
	if(count( $_POST) ){
		//��ñ��������Ͽ�����ƥ�
	
		//���顼��å�������
		$err="";
	
		If ($mobile_key != 0){
			//��Ͽ
			$data = array(
							"id" => $user_id,
							"mb_key" => $mobile_key
						);
			If($c->login_user->mb_touroku($data)){
				//��λ��å�����ɽ��
				$err = "<font color=red>��������λ���ޤ�����</font>";
			}else{
				$err = "<font color=red>" . $c->login_user->show_msg() . "</font>";
			}
		}else{
			$err = "<font color=red>��Ͽ�˼��ԡ����Ӥ�ǧ�ھ�������˼��Ԥ��ޤ�����</font>";
		}
		$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON
	}
	//���ߥ��ƥ������Ͽ�����뤫�����å�����
	$tmp = array(
				'id' => $user_id,
			);
	if ($c->category->getcount($tmp) >0){
		//�ǡ���ȯ��
		$c->set("category_datas", $c->category->find($tmp, "no ASC" ));	//NO�Ǿ���˥�����
	}
	//RSS�ǡ������ɤ߹���
	$tmp = array(
			'id' => $user_id,
			'hidden_chk' => '0',
			'category_cd' => '0'
		);
	If ($c->rss_data->getcount($tmp) >0){
		//�ǡ���ȯ��
		$c->set("rss_datas", $c->rss_data->find($tmp,"no ASC"));
	}
//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/mb_main.html" );
}
?>