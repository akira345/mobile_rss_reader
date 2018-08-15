<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//ログアウト処理
	$data = array();
	$id = $_SESSION["RSS"]["USER"]["id"];
	$data = array(
					"id" => $id
				);
	If($c->login_user->log_out($data)){
	//TOPへ
		$c->redirect('index.php');
	}else{
		echo $c->login_user->show_msg();
	}
}
?>
