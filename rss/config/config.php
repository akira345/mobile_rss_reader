<?php
//DBの接続設定関数
function config_database( &$db )
{
	$db->add( "", "localhost", "db_user", "db_pass", "rss" );
}
//モデルを定義する
function config_models( &$controller )
{
//dirname(__FILE__) はこのファイルのパスが入る（最後の\はなし)
	$controller->AddModel( dirname(__FILE__) . "/../model/login_user.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/rss_data.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/category.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/login_his.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/wk_send_rss.php");
}
//コンポーネントを定義する
function config_components( &$controller )
{
//ようはユーザ定義クラスを取り込む。第２引数はクラス名を指定する。
//ファイル名と第３引数は同じにする？
//使用方法はこんな感じ
//$c->mylib->cr_to_br( $in_str );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/mylib.php", 'mylib', 'mylib' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/easy_login.php", 'MobileInformation', 'easy_login' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/common_lib.php", 'common_lib', 'common_lib' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/Pagination.php", 'Pagination', 'pagination' );
}
//アクション（コントローラ）が呼ばれる直前に実行される関数
//全コントローラ共通で前処理をさせたい場合ここに設定する
function config_controller( &$controller )
{
//http://www.asahi-net.or.jp/~wv7y-kmr/memo/php_security.html#PHP_Sessionより
//セッションIDの妥当性をチェックする。
	$session_id = session_id();
	if ( preg_match( '/^[-,0-9a-zA-Z]+$/D', $session_id ) ) {
		//セッションIDOK
	} elseif ( $session_id == ""){
		//なにもしない
	} else {
		die("不正なアクセスです");
	}
	//デバックモード
	//$controller->SetDebug( true );
	//文字コードを設定する
	$controller->setEncoding('EUC-JP');
	//DBのクライアント文字コード(MySQL用)
	//MySQL以外のＤＢを使用する場合は書き換えること。
	$controller->db->query( "set character set gb2312" );	//いったんクエリを投げる
	mysql_set_charset("ujis"); 								//↑のクエリを実行した接続で文字コード設定を行う(まともな方法で接続文字列が取得できなかったので)
	If ($controller->mylib->chk_mobile() == 0){
		If ($controller->common_lib->check_login() == TRUE){
			//共通テンプレートを設定する(PC用サイドバー付)
			$controller->SetTemplateFile( dirname(__FILE__) . "/../tmplate/common.html");
		}else{
			//共通テンプレートを設定する(PC用サイドバーなし。現在は携帯と兼用する)
			$controller->SetTemplateFile( dirname(__FILE__) . "/../tmplate/mb_common.html");
		}
	}else{
		//共通テンプレートを設定する(携帯用)
		$controller->SetTemplateFile( dirname(__FILE__) . "/../tmplate/mb_common.html");
	}
}
//ちいたんのコントローラクラスを拡張する
function config_controller_class()
{
    require_once( 'extent_controler.php' );
    return 'CMyController';
}
//ログインチェック関数
//コントローラis_secure関数がtrueの時動く
function check_secure( &$controller )
{
    if( empty( $_SESSION["RSS"]["USER"] ) )
    {
        $controller->redirect( "index.php" );
    }
}
?>
