<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );

function action( &$c )
{
//����ȥ���
	if(count( $_POST) ){
		//���顼��å�������
		$err="";
		//�ǡ�����
		$data=array();
//		echo $c->easy_login->chk_mobile();

		//��ñ������Υ����å���Ԥ�
		$mb_key = $c->easy_login->IndividualNum();
		If ( $mb_key !=0 && $_POST["easy_login"] !=""){//���������äƤ��ƴ�ñ������ܥ��󤬲�����Ƥ�����
			$data = array(
						'mb_key' => $mb_key
						);
			If($c->login_user->mb_login($data)){
				//����ꥢȽ��
				If ($c->mylib->chk_mobile() == 0){

					//������쥯��(PC)
					$c->redirect('main.php');
				}else{
					//������쥯��(����)
					$c->redirect('mb_main.php');
				}

			}else{
				$err .= $c->login_user->show_msg();
			}
		}else{
		//��ñ������ǤϤʤ��ޤ������б�ü��

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

				//����������å�
				$data=array(
							'user_name' => $data["user_name"],
							'password' => $data["password"]
							);
				If($c->login_user->login($data)){
					//����ꥢȽ��
					If ($c->mylib->chk_mobile() == 0){

						//������쥯��(PC)
						$c->redirect('main.php');
					}else{
						//������쥯��(����)
						$c->redirect('mb_main.php');
					}
				}else{
					$err .= $c->login_user->show_msg();
				}
			}
		}
		//���β��ϥ��顼�������礷��ư���ʤ�
			//���顼����
			$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON

			$c->set("name",$data["user_name"]);
			$c->set("pw",$data["password"]);
	}

//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/login.html" );
}
?>