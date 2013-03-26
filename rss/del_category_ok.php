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
	if ($c->category->getcount($tmp) ==1){
		//データ発見
		$c->set("datas", $c->category->findone($tmp));
	}else{
		//データが無いので戻す
		$c->redirect('edit_category.php');
	}
	if( count( $_POST ) )
    {
	//POSTでなんか入っている
	$data = array();//データ用
	
		//POSTデータを入れる
		$data["back"]=$c->s->postt("back");
		$data["del"]=$c->s->postt("del");
	//	echo var_dump($data);
	//	echo count($data["back"]);
		If ($data["back"] !==''){
		//戻す
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_category.php');
		}
		If ($data["del"] !==''){
			//削除処理開始
			//削除前に該当カテゴリが指定されたデータが存在したら、該当なしデータとして登録する
			//いらない配列を消す
			unset($data["del"]);
			unset($data["update"]);
			unset($data["back"]);
			//更新データセット
			$data["category_cd"] = 0;

			//登録日セット
			$data["touroku_date"] = $c->common_lib->get_date();
			$data["touroku_time"] = $c->common_lib->get_time();

			//条件
			$tmp = array(
							'id' => $user_id,
							'category_cd' => $no
						);
			//登録
			$c->rss_data->updateby($data,$tmp);
			
			//削除
			$tmp = array(
				'id' => $user_id,
				'no' => $no
			);
			$c->category->del($tmp);
			unset($_SESSION["RSS"]["DEL"]);
			$c->redirect('edit_category.php');

		}
	
	}

//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/del_category_ok.html" );

}

?>