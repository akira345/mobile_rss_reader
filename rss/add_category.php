<?php

	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//リロード対策
	$token = sha1(uniqid(mt_rand(), true));

	 // トークンをセッションに追加する
	$_SESSION['token'][] = $token;
	//ユーザＩＤセット
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//トークンを出力
	$c->set("token",$token,'FALSE');
	
	//エラーメッセージ用(一覧表示)
	$err2="";
	
    if( count( $_POST ) )
    {
	//POSTでなんか入っている
	
	//リロード対策
		// 送信されたトークンがセッションのトークン配列の中にあるか調べる
		$key = array_search($_POST['token'], $_SESSION['token']);
		
		if ($key !== false) {
		    // 正常な POST
		    unset($_SESSION['token'][$key]); // 使用済みトークンを破棄

			//データ登録用
			$data=array();
		
			//エラーメッセージ用
			$err="";
			
			//POSTデータを入れる
			//テーブルの項目名と同じ配列名にすること
			$data["category"]=$c->s->postt("category_name");
	
			//パラメタのチェック
			
			//必須入力
			$err .= $c->v->notempty($data["category"],"カテゴリは必須入力です<BR>");
			
			if ($err == ""){
				//有効範囲チェック
				$err .= $c->v->len($data["category"],1,256,"カテゴリは１文字以上256文字以下です<BR>");
			}
			//念のため重複登録チェックをする
			if ($err == ""){
				$tmp = array(
								'id' => $user_id,
								'category' => $data["category"]
							);
				if ($c->category->getcount($tmp) >0){
					//データ発見
					$err .= "すでに同一カテゴリが登録されています<BR>";
				}
			}
	
			if ($err == ""){
				//エラーなし
				
				//ユーザ情報、初期値セット
				$data["id"] = $user_id;
				
				//登録日セット
				$data["touroku_date"] = $c->common_lib->get_date();
				$data["touroku_time"] = $c->common_lib->get_time();
				
				//登録
				$c->category->insert($data);
				
				//完了メッセージ表示
				$err = "登録が完了しました";
				
				$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON
				
			}else{
				//エラーあり
				$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON
				
				//データセット
				$c->set("category_name",$data["category"]);
				
			}
		}
	}
	//データ表示
	//データ検索
	$tmp = array(
					'id' => $user_id
				);
	if ($c->category->getcount($tmp) >0){
		//データ発見
		$c->set("datas", $c->category->find($tmp, "no ASC" ));	//NOで昇順にソート
	}else{
		$err2 = "登録データがありません";
		$tmp = array();
		$c->set("datas",$tmp);	//ゼロ件時エラーになる対策
	}
	
	//データセット
	$c->set("err2",$err2,TRUE);//エラーメッセージはタグ出力ON

//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/edit_category.html" );

}

?>