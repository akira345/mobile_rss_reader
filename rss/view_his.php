<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//����ȥ���
	$data = array();
	//�桼���ɣĥ��å�
	$id = $_SESSION["RSS"]["USER"]["id"];

	//���򻲾�
	$data = array(
					'id' => $id
				);
	If ($data = $c->login_his->find($data, "touroku_date DESC,touroku_time DESC" )){
		//�ǡ�������
		//�ڡ����󥰽���
		if (count($_GET['page'])){
			$now_page = $c->s->gett("page");
		}else{
			$now_page = 1;
		}
		//���ǡ����������
		$all_count = count($data);
		
		//ɽ���ڡ�����
		$page = 30;	//�����ޤ����줯�餤
		
		//�ڡ���������
		$option = array(
			'baseUrl'	=> 'view_his.php',	// ��󥯤�URL
			'queryStr'	=> htmlspecialchars( SID,ENT_QUOTES ) . '&page',			// �����꡼ʸ����
			'curPage'	=> $now_page,		// ���ߤΥڡ����ֹ�
			'perPage'	=> $page,			// 1����������Υꥹ�ȿ�
			'totalRows'  	=> $all_count,	// �ꥹ�Ȥι�׿�
			'numLinks'	=> 2,				// ����Υ�󥯿�
			'pageSummary'	=> TRUE,		// ���ޥ꡼��ɽ��
			'firstLink'	=> '��',			// "�ǽ�" �Υڡ����ؤΥ��ʸ����
			'prevLink'	=> '��',			// "��" �Υڡ����ؤΥ��ʸ����
			'nextLink'	=> '��',			// "��" �Υڡ����ؤΥ��ʸ����
			'lastLink'	=> '��',			// "�Ǹ�" �Υڡ����ؤΥ��ʸ����
			'fullTagOpen'	=> '<ul>',		// �ڡ����͡������γ��ϥ���
			'fullTagClose'	=> '</ul>',		// �ڡ����͡������ν�λ����
			'linkTagOpen'	=> '',			// �ڡ�����󥯤γ��ϥ���
			'linkTagClose'	=> '',			// �ڡ�����󥯤ν�λ����
			'curTagOpen'	=> '<B>',		// "����" �Υڡ������ֹ�γ��ϥ���
			'curTagClose'	=> '</B>',		// "����" �Υڡ������ֹ�ν�λ����
		);

		//�ڡ����󥰽����
		$c->pagination->initialize( $option );

		//����ǡ�����ڡ������Ȥ�ʬ�䤹��
		$data = $c->pagination->slice( $data);
		
		//�ڡ�����ɽ��
		$c->set("pager",$c->pagination->create_links(),'TRUE');

		//�ǡ���
		$c->set("datas",$data,'FALSE');//��󥯤ϵ��Ĥ��ʤ�


	}else{
		//�ǡ����ʤ�
	}
	//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/view_his.html" );

}
?>