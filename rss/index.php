<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
	
function action( &$c )
{
//����ȥ���

//�������椫�ɤ���Ƚ��
	If ($c->common_lib->check_login() == TRUE){
		//������쥯��
		//����ꥢȽ��
		If ($c->mylib->chk_mobile() == 0){
			//������쥯��(PC)
			$c->redirect('main.php');
		}else{
			//������쥯��(����)
			$c->redirect('mb_main.php');
		}
	}else{
		//������쥯��
		$c->redirect('login.php');
	}
}

