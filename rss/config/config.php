<?php
//DB����³����ؿ�
function config_database( &$db )
{
	$db->add( "", "localhost", "db_user", "db_pass", "rss" );
}
//��ǥ���������
function config_models( &$controller )
{
//dirname(__FILE__) �Ϥ��Υե�����Υѥ�������ʺǸ��\�Ϥʤ�)
	$controller->AddModel( dirname(__FILE__) . "/../model/login_user.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/rss_data.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/category.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/login_his.php");
	$controller->AddModel( dirname(__FILE__) . "/../model/wk_send_rss.php");
}
//����ݡ��ͥ�Ȥ��������
function config_components( &$controller )
{
//�褦�ϥ桼��������饹������ࡣ�裲�����ϥ��饹̾����ꤹ�롣
//�ե�����̾���裳������Ʊ���ˤ��롩
//������ˡ�Ϥ���ʴ���
//$c->mylib->cr_to_br( $in_str );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/mylib.php", 'mylib', 'mylib' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/easy_login.php", 'MobileInformation', 'easy_login' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/common_lib.php", 'common_lib', 'common_lib' );
	$controller->AddComponent(  dirname(__FILE__) . "/../component/Pagination.php", 'Pagination', 'pagination' );
}
//���������ʥ���ȥ���ˤ��ƤФ��ľ���˼¹Ԥ����ؿ�
//������ȥ��鶦�̤��������򤵤�������礳�������ꤹ��
function config_controller( &$controller )
{
//http://www.asahi-net.or.jp/~wv7y-kmr/memo/php_security.html#PHP_Session���
//���å����ID��������������å����롣
	$session_id = session_id();
	if ( preg_match( '/^[-,0-9a-zA-Z]+$/D', $session_id ) ) {
		//���å����IDOK
	} elseif ( $session_id == ""){
		//�ʤˤ⤷�ʤ�
	} else {
		die("�����ʥ��������Ǥ�");
	}
	//�ǥХå��⡼��
	//$controller->SetDebug( true );
	//ʸ�������ɤ����ꤹ��
	$controller->setEncoding('EUC-JP');
	//DB�Υ��饤�����ʸ��������(MySQL��)
	//MySQL�ʳ��Σģ¤���Ѥ�����Ͻ񤭴����뤳�ȡ�
	$controller->db->query( "set character set gb2312" );	//���ä��󥯥�����ꤲ��
	mysql_set_charset("ujis"); 								//���Υ������¹Ԥ�����³��ʸ�������������Ԥ�(�ޤȤ����ˡ����³ʸ���󤬼����Ǥ��ʤ��ä��Τ�)
	If ($controller->mylib->chk_mobile() == 0){
		If ($controller->common_lib->check_login() == TRUE){
			//���̥ƥ�ץ졼�Ȥ����ꤹ��(PC�ѥ����ɥС���)
			$controller->SetTemplateFile( dirname(__FILE__) . "/../tmplate/common.html");
		}else{
			//���̥ƥ�ץ졼�Ȥ����ꤹ��(PC�ѥ����ɥС��ʤ������ߤϷ��Ӥȷ��Ѥ���)
			$controller->SetTemplateFile( dirname(__FILE__) . "/../tmplate/mb_common.html");
		}
	}else{
		//���̥ƥ�ץ졼�Ȥ����ꤹ��(������)
		$controller->SetTemplateFile( dirname(__FILE__) . "/../tmplate/mb_common.html");
	}
}
//��������Υ���ȥ��饯�饹���ĥ����
function config_controller_class()
{
    require_once( 'extent_controler.php' );
    return 'CMyController';
}
//����������å��ؿ�
//����ȥ���is_secure�ؿ���true�λ�ư��
function check_secure( &$controller )
{
    if( empty( $_SESSION["RSS"]["USER"] ) )
    {
        $controller->redirect( "index.php" );
    }
}
?>
