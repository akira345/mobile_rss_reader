<?php
//���������
	require_once( "<�����Ф����Хѥ�>/rss/component/rss_fetch.inc");
	define('MAGPIE_OUTPUT_ENCODING', 'utf-8');
	define('MAGPIE_CACHE_DIR','<�����Ф����Хѥ�>/rss/component/cache');
	//��������Ϥ��Ȥ��ɤ߹��ޤ�����
	require_once( "<�����Ф����Хѥ�>/rss/config/config.php" );
	require_once( "<�����Ф����Хѥ�>/cheetan/cheetan.php" );
function is_session()
{
//���å�����̵���ˤ���
    return false;
}
function action( &$c )
{
	//����ȥ���
	//�桼���ɣĥ��å�
	//�᡼�륢�ɥ쥹����Ͽ�ѤߤΥ桼��
	If($c->login_user->getcount("'e-mail' <> ''") >0){
		$user_data = $c->login_user->find("'e-mail' != ''");
		foreach($user_data as $u_data){
			$user_id = $u_data["id"];
			$e_mail = $u_data["e-mail"];
			$tmp = array(
							'id' => $user_id,
							'haisin_flg' => '1',
						);
			If ($c->rss_data->getcount($tmp) > 0){
				//�ǡ�������
				$datas=$c->rss_data->find($tmp);
				$wk_body2 = '';//RSSñ�̤�ʸ��
				foreach($datas as $data){
					//������ɤ����
					//ǰ�Τ�����ԥ����ɤ򤽤��Ƥ���
					$keyword = explode("\n",str_replace(array("\r\n","\n","\r"), "\n", $data["keyword"]));
					//ɽ���ǤĤ����Τ�����
					$wk_keyword = $keyword;
					//�������ʸ������������Ԥ�
					$keyword = $c->mylib->mb_convert_kana_variables($keyword,'rnaskh',$c->encoding);
					//������ɤν�ʣ�����
					$uniq_keyword = array_unique($keyword);
					//RSS��URL
					$url = $data["rss_url"];
					//������
					$comment = $data["comment"];
					//RSS��NO
					$rss_no = $data["no"];
					//RSS�Υѡ���
					$url = html_entity_decode($url, ENT_QUOTES);
					$rss = fetch_rss($url);
					$wk_body = '';//�����Υ����ȥ롢��ʸ
					//�����Ѵ��ؤ�URL
					//Google��ȤäƤߤ�
					$keitai_cnv = 'http://www.google.co.jp/gwt/n?u=';
					//�����Ѵ���ɬ�פ��ɤ���
					if($data["cnv_keitai"] == 0){
						$keitai_cnv = null;
					}
					foreach ($rss->items as $item){
						//������ʸ�������ɤ�����Ǥ������ִ�����
						//����ñ��
						$title = strip_tags(mb_convert_encoding($item['title'],$c->encoding,'UTF8'));
						$summary = strip_tags(mb_convert_encoding($item['summary'],$c->encoding,'UTF8'));
						$wk_url = $keitai_cnv . htmlspecialchars(mb_convert_encoding($item['link'],$c->encoding,'UTF8'));
						//���դ��Ѵ�
						If ($item['dc']['date']){
							//RSS1.0
							$wk_time = preg_replace('/T|[\+Z].+/', ' ',$item['dc']['date']); //2009-04-25 06:25:03
						}else{
							$wk_time = date('Y-m-d H:i:s',strtotime($item['pubdate'])); //2009-04-24 22:25:34
						}
						$match_keywords = '';//�ޥå��󥰥�����ɥ��ꥢ
						//����NG����
						$ad_words = array("[PR]","��PR��","AD:","��PR��","AD��","����","PR:","PR��","Info:");
						//$ad_words = $c->settings->get_settings('ad_words');
						If ($c->common_lib->array_strpos($title,$ad_words) == TRUE){
							continue;	//�ʲ��ν��������å�
						}
						//�����ȥ������������Ǥ˥᡼���������Ƥ��ʤ�����ǧ
						$tmp = "user_id='" . $c->wk_send_rss->escape($user_id) . "' ";
						$tmp.= "and rss_id='" . $c->wk_send_rss->escape($rss_no). "' ";
						$tmp.= "and title='" . $c->wk_send_rss->escape($title). "' ";
						//���ۿ��򤷤ʤ�
						If ($item['no_repert_flg'] != True){
							$tmp.= "and touroku_date >='" . date("Ymd",strtotime("-1 week")) ."'";//Ʊ��RSS,Ʊ�������ȥ���ۿ�����ͱͽ���֤ϣ�����
						}
						If($c->wk_send_rss->getcount($tmp) == 0){
						//��������˥�����ɤ˥ޥå�������Τ����뤫��
							foreach ($uniq_keyword as $key){
						//�����ȥ��ޥå��󥰤��оݤ��ѹ�20100318
						//		If (stripos(mb_convert_kana($summary,'rnaskh',$c->encoding),$key) ===FALSE){
								If (stripos(mb_convert_kana($title . $summary,'rnaskh',$c->encoding),$key) ===FALSE){
									//�԰��פϤʤˤ⤷�ʤ�
								}else{
										//���פ���������ɤ򥻥å�
										//�ޤ�������������������ɤ����󥭡�����
										$match_key = array_search($key,$keyword);
										//���˳����������󥭡�������Ͽ���줿������ɤ��������
										$match_keywords .= '[' . $wk_keyword[$match_key] . ']';
								}
							}
						}
						If (($match_keywords)!=''){
							//�ޥå�����������ɤ����ä�
							//�����ξ��ϥ����ȥ��(��)��Ĥ���
							$tmp=array(
										'user_id' => $user_id,
										'rss_id' => $rss_no,
										'title' => $title
										);
							If ($c->wk_send_rss->getcount($tmp) !=0){
								$resend_flg = 1;
							}else{
								$resend_flg = 0;
							}
							//�᡼�������ơ��֥�˵�Ͽ
							$tmp=array(
										'user_id' => $user_id,
										'rss_id' => $rss_no,
										'title' => $title,
										'touroku_date' => $c->common_lib->get_date()
										);
							$c->wk_send_rss->insert($tmp);
							If ($resend_flg !=0){
								$title = "(��)" . $title;
							}
							//�᡼����ʸ�̹���
							$wk_body .= "�����ȥ롧" .$title . "\n";	//�����ȥ�
							$wk_body .= "(" . $wk_time . ")" . "\n";	//����������
							$wk_body .= "�ޥå�����������ɡ�" . $match_keywords . "\n";
							$wk_body .= $summary . "\n";	//��ʸ
							$wk_body .= "link:" . $wk_url . "\n";
							$wk_body .= "--------------------------------------------------------------------" . "\n\n";
						}
					}
					If (($wk_body)!=''){
						//RSSñ��
						$wk_body2 .= "RSS:��" . $comment . "��" . "\n\n";
						$wk_body2 .= $wk_body;
					}
				}
				If (($wk_body2)!=''){
					echo "�᡼����������";
					$wk_body2 .= "\n\n����RSS�꡼����http://exsample.com/rss/" . "\n";
				//	echo $wk_body2;
					////
					mb_language("Ja") ;
					mb_internal_encoding("EUC-JP") ;
					$mailto=$e_mail;
					$subject="RSS�ޥå��󥰥�ݡ���(".$c->common_lib->get_date().")";
					$content="������ɤ˥ޥå��󥰤��������������������ޤ���"."\n\n".$wk_body2;
					$mailfrom="From:info@exsample.com";
					mb_send_mail($mailto,$subject,$content,$mailfrom);
				}
			}
		}
	}
}
?>