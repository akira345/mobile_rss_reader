<?php
	require_once( "./component/rss_fetch.inc");
	define('MAGPIE_OUTPUT_ENCODING', 'utf-8');
	define('MAGPIE_CACHE_DIR','./component/cache');//�饤�֥�꤫��Υѥ�
	//��������Ϥ��Ȥ��ɤ߹��ޤ�����
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//����ȥ���
	//�桼���ɣĥ��å�
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	If (count($_GET)){
		//Get�˲������äƤ���
		//�ǡ�����Ͽ��
		$data=array();
		//GET�ǡ����������
		$data["rss_cd"]=$c->s->gett("rss_cd");
		If ($data["rss_cd"] != ""){
			//�����ɤ���
			$tmp = array(
							'id' => $user_id,
							'no' => $data["rss_cd"]
						);
			If ($c->rss_data->getcount($tmp) >0){
				//�ǡ�������
				$datas=$c->rss_data->findone($tmp);
				//RSS��URL
				$url = $datas["rss_url"];
				//�����Ѵ��ؤ�URL
				//Google��ȤäƤߤ�
				$keitai_cnv = 'http://www.google.co.jp/gwt/n?u=';
				//�����Ѵ���ɬ�פ��ɤ���
				if($datas["cnv_keitai"] == 0){
					$keitai_cnv = null;
				}
				//RSS�Υѡ���
				$url = html_entity_decode($url, ENT_QUOTES);
				$rss = fetch_rss($url);
				//�ڡ����󥰽���
				if (count($_GET['page'])){
					$now_page = $c->s->gett("page");
				}else{
					$now_page = 1;
				}
				//�������ΰ١����RSS��Ÿ������
				$rss_data = array();
				foreach ($rss->items as $item){
					$title = strip_tags(mb_convert_encoding($item['title'],$c->encoding,'UTF8'));
					$summary = strip_tags(mb_convert_encoding($item['summary'],$c->encoding,'UTF8'));
					$summary = mb_strimwidth($summary,0,100,"...",$c->encoding);	//100ʸ���ޤ�
					$wk_url = $keitai_cnv . htmlspecialchars(mb_convert_encoding($item['link'],$c->encoding,'UTF8'));
					//����NG����
					$ad_words = array("[PR]","��PR��","AD:","��PR��","AD��","����","PR:","PR��","Info:");
					If ($c->common_lib->array_strpos($title,$ad_words) == TRUE){
						continue;	//�ʲ��ν��������å�
					}
					$tmp = array(
								"title" => $title,
								"summary" => $summary,
								"url" => $wk_url
								);
					array_push($rss_data,$tmp);
				}



				//���ǡ����������
				//$all_count = count($rss->items);
				$all_count = count($rss_data);
				//ɽ���ڡ�����
				If($datas["view_cnt"] == 0){
					$datas["view_cnt"] = 9999;//�ڡ����󥰤����ʤ�
				}
				//�ڡ���������
				$option = array(
					'baseUrl'	=> 'view_rss.php',		// ��󥯤�URL
					'queryStr'	=> htmlspecialchars( SID,ENT_QUOTES ) . '&rss_cd=' . $data["rss_cd"] .'&page',			// �����꡼ʸ����
					'curPage'	=> $now_page,		// ���ߤΥڡ����ֹ�
					'perPage'	=> $datas["view_cnt"],				// 1����������Υꥹ�ȿ�
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
				//RSS�ǡ�����ڡ������Ȥ�ʬ�䤹��
			//	$data = $c->pagination->slice( $rss->items);
				$data = $c->pagination->slice( $rss_data);
				//�����ȥ�
				$c->set("title",$datas["comment"],'FALSE');
				//�ڡ�����ɽ��
				$c->set("pager",$c->pagination->create_links(),'TRUE');
				//�ǡ���
				$c->set("datas",$data,'TRUE');
				//�ƥ�ץ졼�ȥե��������
				$c->SetViewFile( "./tmplate/rss.html" );
			}else{
				//�ǡ����ʤ�
			}
		}else{
		//�����ɤʤ�
		}
	}else{
	//Get�ʤ�
	}
}
?>