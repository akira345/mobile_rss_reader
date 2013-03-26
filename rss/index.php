<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
	
function action( &$c )
{
//コントローラ

//ログイン中かどうか判定
	If ($c->common_lib->check_login() == TRUE){
		//リダイレクト
		//キャリア判定
		If ($c->mylib->chk_mobile() == 0){
			//リダイレクト(PC)
			$c->redirect('main.php');
		}else{
			//リダイレクト(携帯)
			$c->redirect('mb_main.php');
		}
	}else{
		//リダイレクト
		$c->redirect('login.php');
	}
}

