<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{
//コントローラ
	//ユーザＩＤセット
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	If (count($_GET)){
		//Getに何か入っている
		//データ登録用
		$data=array();
		//GETデータを入れる
		$data["category_cd"]=$c->s->gett("category_cd");
		If ($data["category_cd"] != ""){
			//現在カテゴリの登録があるかチェックする
			$tmp = array(
						'id' => $user_id,
						'category_cd' => $data["category_cd"],
						'hidden_chk' => '0'
					);
			if ($c->rss_data->getcount($tmp) >0){
				//データ発見
				$c->set("rss_datas", $c->rss_data->find($tmp, "no ASC" ));	//NOで昇順にソート
			}else{
			//でーたなし
				$c->set("err","登録はありません");
			}
			//テンプレートファイル指定
			$c->SetViewFile( "./tmplate/select_rss.html" );
		}
		//getなし
	}
	//getなし
}
?>
