<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
function action( &$c )
{
    if( count( $_POST ) )
    {
	//POST�Ǥʤ����äƤ���

		//�ǡ�����Ͽ��
		$data=array();

		//���顼��å�������
		$err="";

		//POST�ǡ����������
		$data["user_name"]=$c->s->postt("name");
		$data["password"]=$c->s->postt("pw");
		//�ѥ�᥿�Υ����å�

		//ɬ������
		$err .= $c->v->notempty($data["user_name"],"ID��ɬ�����ϤǤ�<BR>");
		$err .= $c->v->notempty($data["password"],"�ѥ���ɤ�ɬ�����ϤǤ�<BR>");

		if ($err == ""){
			//�ѿ��������å�
			$err .= $c->v->eisu($data["user_name"],"ID��Ⱦ�ѱѿ����ΤߤǤ�<BR>");
			$err .= $c->v->eisu($data["password"],"�ѥ���ɤ�Ⱦ�ѱѿ����ΤߤǤ�<BR>");
		}
		if ($err == ""){
			//ͭ���ϰϥ����å�
			$err .= $c->v->len($data["user_name"],1,100,"ID�ϣ�ʸ���ʾ壱����ʸ���ʲ��Ǥ�<BR>");
		}
		if ($err == ""){
			//���顼�ʤ�

			//�ѥ�᥿���å�
			$data = array(
					"user_name" => $data["user_name"],
					"password" => $data["password"]
					);
			//��Ͽ
			If($c->login_user->touroku($data)){

				//��λ�ڡ�����
				$c->redirect('ok.php');
			}else{
				//��Ͽ���顼
				$err = $c->login_user->show_msg();
			}
		}
		//�����ˤϥ��顼�ξ�礷����ʤ�
		//���顼����
		$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON

		$c->set("name",$data["user_name"]);
		$c->set("pw",$data["password"]);
	}
//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/add.html" );
}
?>