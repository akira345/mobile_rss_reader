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
	//データ登録用
	$data=array();
	//パラメタ用
	$tmp = array();
	//携帯固有番号取得
	$c->easy_login->MobileInformation();
	$mobile_key = $c->easy_login->IndividualNum();
	//簡単ログイン登録後ほかのページへ行き、戻ったとき再度メッセージが出る対策
	If ($mobile_key != 0){
		$tmp = array(
						'mb_key' => $mobile_key
					);
		if ($c->login_user->getcount($tmp) >0){
			//データ発見
			$c->set("easy_login_chk","FALSE");
		}else{
			$c->set("easy_login_chk","TRUE");
		}
	}else{
		$c->set("easy_login_chk","TRUE");
	}
	if(count( $_POST) ){
		//簡単ログイン登録システム
	
		//エラーメッセージ用
		$err="";
	
		If ($mobile_key != 0){
			//登録
			$data = array(
							"id" => $user_id,
							"mb_key" => $mobile_key
						);
			If($c->login_user->mb_touroku($data)){
				//完了メッセージ表示
				$err = "<font color=red>更新が完了しました。</font>";
			}else{
				$err = "<font color=red>" . $c->login_user->show_msg() . "</font>";
			}
		}else{
			$err = "<font color=red>登録に失敗。携帯の認証情報取得に失敗しました。</font>";
		}
		$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON
	}
	//現在カテゴリの登録があるかチェックする
	$tmp = array(
				'id' => $user_id,
			);
	if ($c->category->getcount($tmp) >0){
		//データ発見
		$c->set("category_datas", $c->category->find($tmp, "no ASC" ));	//NOで昇順にソート
	}
	//RSSデータを読み込み
	$tmp = array(
			'id' => $user_id,
			'hidden_chk' => '0',
			'category_cd' => '0'
		);
	If ($c->rss_data->getcount($tmp) >0){
		//データ発見
		$c->set("rss_datas", $c->rss_data->find($tmp,"no ASC"));
	}
//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/mb_main.html" );
}
?>