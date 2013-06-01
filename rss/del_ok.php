<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//情報セット
	$no = $_SESSION["RSS"]["DEL"]["NO"];
	//ユーザＩＤセット
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//データ読み込み
	$tmp = array(
				'id' => $user_id,
				'no' => $no
			);
	if ($c->rss_data->getcount($tmp) ==1){
		//データ発見
		$c->set("datas", $c->rss_data->findone($tmp));
	}else{
		//データが無いので戻す
		$c->redirect('edit_rss.php');
	}
	if( count( $_POST ) )
    {
	//POSTでなんか入っている

		//POSTデータを入れる
		$data["back"]=$c->s->postt("back");
		$data["del"]=$c->s->postt("del");
	//	echo var_dump($data);
	//	echo count($data["back"]);
		If ($data["back"] !==''){
		//戻す
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_rss.php');
		}
		If ($data["del"] !==''){
			//削除処理開始
			$tmp = array(
				'id' => $user_id,
				'no' => $no
			);
			$c->rss_data->del($tmp);
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_rss.php');
		}

	}
//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/del_ok.html" );
}
?>