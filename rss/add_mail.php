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
	//������к�
	$token = sha1(uniqid(mt_rand(), true));
	 // �ȡ�����򥻥å������ɲä���
	$_SESSION['token'][] = $token;
	//�ȡ���������
	$c->set("token",$token,'FALSE');
	//id
	$id = $_SESSION["RSS"]["USER"]["id"];
	//�᡼�뤬��Ͽ����Ƥ�����ɽ������
	If($c->login_user->getcount("'e-mail' <> '' and id = '" . $c->login_user->escape($id) . "'")>0){
		$tmp=array(
					'id' => $id
					);
		$data = $c->login_user->findone($tmp);
		$c->set("e-mail",$data["e-mail"]);
	}

	if(count( $_POST) ){
		//������к�
		// �������줿�ȡ����󤬥��å����Υȡ������������ˤ��뤫Ĵ�٤�
		$key = array_search($_POST['token'], $_SESSION['token']);

		if ($key !== false) {
		    // ����� POST
		    unset($_SESSION['token'][$key]); // ���ѺѤߥȡ�������˴�
			//���顼��å�������
			$err="";
			//�ǡ�����
			$data=array();


			//POST�ǡ����������
			$data["e-mail"]=$c->s->postt("e-mail");
			$data["e-mail2"]=$c->s->postt("e-mail2");

			//�ѥ�᥿�Υ����å�

			//Ʊ���������å�
			If ($data["e-mail"] <> $data["e-mail2"]){
				$err .= "��ǧ�ѥ᡼�륢�ɥ쥹�����פ��ޤ���<BR>";
			}

			//�᡼�륢�ɥ쥹�����å�
			if ($err == "" And $data["e-mail"] <> ""){
				//�ᥢ�ɥ����å�
				$err .= $c->v->email($data["e-mail"],"�᡼�륢�ɥ쥹��ͭ���ǤϤ���ޤ���<BR>");
			}


			if ($err == ""){

				//�ѥ�����ѹ�
				$data=array(
							'id' => $id,
							'e-mail' => $data["e-mail"]
							);
				If($c->login_user->add_email($data)){
					$c->redirect('touroku_ok.php');
				}
				//���顼
					$err .= $c->login_user->show_msg();
			}

			//���β��ϥ��顼�������礷��ư���ʤ�
				//���顼����
				$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON

				$c->set("e-mail",$data["e-mail"]);
				$c->set("e-mail2",$data["e-mail2"]);
		}
	}
//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/add_mail.html" );
}
?>
