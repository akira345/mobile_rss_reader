<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );

function action( &$c )
{
//コントローラ
	if(count( $_POST) ){
		//エラーメッセージ用
		$err="";
		//データ用
		$data=array();
//		echo $c->easy_login->chk_mobile();

		//簡単ログインのチェックを行う
		$mb_key = $c->easy_login->IndividualNum();
		If ( $mb_key !=0 && $_POST["easy_login"] !=""){//キーが入っていて簡単ログインボタンが押されていたら
			$data = array(
						'mb_key' => $mb_key
						);
			If($c->login_user->mb_login($data)){
				//キャリア判定
				If ($c->mylib->chk_mobile() == 0){

					//リダイレクト(PC)
					$c->redirect('main.php');
				}else{
					//リダイレクト(携帯)
					$c->redirect('mb_main.php');
				}

			}else{
				$err .= $c->login_user->show_msg();
			}
		}else{
		//簡単ログインではないまたは非対応端末

			//POSTデータを入れる
			$data["user_name"]=$c->s->postt("name");
			$data["password"]=$c->s->postt("pw");
			//パラメタのチェック

			//必須入力
			$err .= $c->v->notempty($data["user_name"],"IDは必須入力です<BR>");
			$err .= $c->v->notempty($data["password"],"パスワードは必須入力です<BR>");

			if ($err == ""){
				//英数字チェック
				$err .= $c->v->eisu($data["user_name"],"IDは半角英数字のみです<BR>");
				$err .= $c->v->eisu($data["password"],"パスワードは半角英数字のみです<BR>");
			}

			if ($err == ""){
				//有効範囲チェック
				$err .= $c->v->len($data["user_name"],1,100,"IDは１文字以上１００文字以下です<BR>");
			}

			if ($err == ""){

				//ログインチェック
				$data=array(
							'user_name' => $data["user_name"],
							'password' => $data["password"]
							);
				If($c->login_user->login($data)){
					//キャリア判定
					If ($c->mylib->chk_mobile() == 0){

						//リダイレクト(PC)
						$c->redirect('main.php');
					}else{
						//リダイレクト(携帯)
						$c->redirect('mb_main.php');
					}
				}else{
					$err .= $c->login_user->show_msg();
				}
			}
		}
		//この下はエラーがある場合しか動かない
			//エラーあり
			$c->set("err",$err,'TRUE');//エラーメッセージはタグ出力ON

			$c->set("name",$data["user_name"]);
			$c->set("pw",$data["password"]);
	}

//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/login.html" );
}
?>
