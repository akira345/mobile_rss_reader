<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
	
function action( &$c )
{
//����ȥ�����
	//��������к�
	$token = sha1(uniqid(mt_rand(), true));

	 // �ȡ�����򥻥å������ɲä���
	$_SESSION['token'][] = $token;

	//�ȡ���������
	$c->set("token",$token,'FALSE');

	if(count( $_POST) ){
		//��������к�
		// �������줿�ȡ����󤬥��å����Υȡ������������ˤ��뤫Ĵ�٤�
		$key = array_search($_POST['token'], $_SESSION['token']);
		
		if ($key !== false) {
		    // ����� POST
		    unset($_SESSION['token'][$key]); // ���ѺѤߥȡ�������˴�

			//���顼��å�������
			$err="";
			//�ǡ�����
			$data=array();
	
			//id
			$id = $_SESSION["RSS"]["USER"]["id"];
			
			//POST�ǡ����������
			$data["password"]=$c->s->postt("pw");
			$data["password2"]=$c->s->postt("pw2");
	
			//�ѥ�᥿�Υ����å�
			
			//ɬ������
			$err .= $c->v->notempty($data["password"],"�ѥ���ɤ�ɬ�����ϤǤ�<BR>");
			$err .= $c->v->notempty($data["password2"],"��ǧ�ѥѥ���ɤ�ɬ�����ϤǤ�<BR>");
	
			if ($err == ""){
				//Ʊ���������å�
				If ($data["password"] <> $data["password2"]){
					$err .= "��ǧ�ѥѥ���ɤ����פ��ޤ���<BR>";
				}
			}
			
			if ($err == ""){
				//�ѿ��������å�
				$err .= $c->v->eisu($data["password"],"�ѥ���ɤ�Ⱦ�ѱѿ����ΤߤǤ�<BR>");
			}
		
			if ($err == ""){
	
				//�ѥ�����ѹ�
				$data=array(
							'id' => $id,
							'password' => $data["password"]
							);
				If($c->login_user->change_password($data)){
					//�ѥ�����ѹ������饻�å������ڤ�
					If($c->login_user->log_out($data)){
						$c->redirect('ok.php');
					}
				}
				//���顼
					$err .= $c->login_user->show_msg();
			}
	
			//���β��ϥ��顼�������礷��ư���ʤ�
				//���顼����
				$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON
				
				$c->set("pw",$data["password"]);
				$c->set("pw2",$data["password2"]);
		}
	}

//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/chg_pw.html" );
}
?>