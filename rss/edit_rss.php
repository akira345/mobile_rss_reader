<?php

	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//��������к�
	$token = sha1(uniqid(mt_rand(), true));

	 // �ȡ�����򥻥å������ɲä���
	$_SESSION['token'][] = $token;
	//�桼���ɣĥ��å�
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//�ȡ���������
	$c->set("token",$token,'FALSE');
	
	//���顼��å�������
	$err2="";
	//�ǡ�����
	$data=array();
	
    if( count( $_POST ) )
    {
	//POST�Ǥʤ����äƤ���
	//��������к�
		// �������줿�ȡ����󤬥��å����Υȡ������������ˤ��뤫Ĵ�٤�
		$key = array_search($_POST['token'], $_SESSION['token']);
		
		if ($key !== false) {
		    // ����� POST
		    unset($_SESSION['token'][$key]); // ���ѺѤߥȡ�������˴�
			//�ǡ�����Ͽ��
			$data=array();
		
			//���顼��å�������
			$err2="";
			
			//POST�ǡ����������
			$data["rss_url"]=$c->s->postt("rss_url");
			$data["comment"]=$c->s->postt("comment");
			$data["cnv_keitai"]=$c->s->postt("cnv_keitai");
			$data["view_cnt"]=$c->s->postt("view_cnt");
			$data["category_cd"]=$c->s->postt("category_cd");
			$data["hidden_chk"]=$c->s->postt("hidden_chk");
			$data["del"]=$c->s->postt("del");
			$data["update"]=$c->s->postt("update");
			$data["no"]=$c->s->postt("no");
			//�᡼�����������å�����������ɲ�
			$data["haisin_flg"]=$c->s->postt("haisin_flg");
			$data["keyword"]=$c->s->postt("keyword");
			
	
			If ($data["del"] !==''){
				//���
				If ($data["no"] !==''){
					//�������򥻥å�����
					$_SESSION["RSS"]["DEL"]["NO"] = $data["no"];
					//��ǧ�ڡ�����
					$c->redirect('del_ok.php');
				
				}
			}elseif($data["update"] !==''){
			//����
			//�ѥ�᥿�Υ����å�
			
				//ɬ������
				$err2 .= $c->v->notempty($data["rss_url"],"RSS��ɬ�����ϤǤ�<BR>");
				$err2 .= $c->v->notempty($data["comment"],"�����Ȥ�ɬ�����ϤǤ�<BR>");
				$err2 .= $c->v->notempty($data["view_cnt"],"ɽ�������ɬ�����ϤǤ�<BR>");
				$err2 .= $c->v->notempty($data["category_cd"],"���ƥ����ɬ�����ϤǤ�<BR>");
				
				if ($err2 == ""){
					//�ѿ��������å�
					//���椬�ޤޤ��Τǥ����å���ѥ�
					//$err2 .= $c->v->eisu($data["rss_url"],"RSS��Ⱦ�ѱѿ����ΤߤǤ�<BR>");
				}
				if ($err2 == ""){
					//���������å�
					$err2 .= $c->v->number($data["view_cnt"],"ɽ�������Ⱦ�ѿ����ΤߤǤ�<BR>");
					$err2 .= $c->v->number($data["category_cd"],"���ƥ�������������Ǥ�<BR>");
				}
				if ($err2 == ""){
					//URL�����å�
					//��������ΥХ�ơ��Ȥ��ĥ�������ä����ɤ�ʬ����ʤ��ä��ΤǶ��̥��饹�ǽ�������
					$err2 .= $c->common_lib->chk_url($data["rss_url"],"RSS�Υ��ɥ쥹�������Ǥ�<BR>");
				}
				if ($err2 == ""){
					//ͭ���ϰϥ����å�
					$err2 .= $c->v->len($data["rss_url"],1,2000,"RSS�ϣ�ʸ���ʾ�2000ʸ���ʲ��Ǥ�<BR>");
					$err2 .= $c->v->len($data["comment"],1,512,"�����Ȥ����ѣ�ʸ���ʾ�256ʸ���ʲ��Ǥ�<BR>");
					$err2 .= $c->v->len($data["view_cnt"],1,3,"ɽ������ϣ�ʸ���ʾ�3ʸ���ʲ��Ǥ�<BR>");
					$err2 .= $c->v->len($data["category_cd"],1,2,"���ƥ�������������Ǥ�<BR>");
				}
					//ǰ�Τ����ʣ��Ͽ�����å��򤹤�
				if ($err2 == ""){
					//��ʬ�ʳ���Ʊ��գң̤����ä���Σ�
					$tmp =  "`id` = '" . $c->rss_data->escape($user_id) . "' and ";
					$tmp .= "`rss_url` = '" . $c->rss_data->escape($data["rss_url"]) . "' and ";
					$tmp .= "`no` != '" . $c->rss_data->escape($data["no"]) . "'";
					if ($c->rss_data->getcount($tmp) >0){
						//�ǡ���ȯ��
						$err2 .= "���Ǥ�Ʊ��RSS����Ͽ����Ƥ��ޤ�<BR>";
					}
				}
	
		
				if ($err2 == ""){
					//���顼�ʤ�
					
					//�����Ѵ��Υ����å��ܥå������Ѵ�����
					If($data["cnv_keitai"]==''){
						$data["cnv_keitai"] = 0;
					}else{
						$data["cnv_keitai"] = 1;
					}
					//��ɽ�������å��ܥå������Ѵ�����
					If($data["hidden_chk"]==''){
						$data["hidden_chk"] = 0;
					}else{
						$data["hidden_chk"] = 1;
					}
					//�᡼���ۿ��ե饰�Υ����å��ܥå������Ѵ�����
					If($data["haisin_flg"]==''){
						$data["haisin_flg"] = 0;
					}else{
						$data["haisin_flg"] = 1;
					}
					
					//�桼�����󥻥å�
					$data["id"] = $user_id;
					
					//��Ͽ�����å�
					$data["touroku_date"] = $c->common_lib->get_date();
					$data["touroku_time"] = $c->common_lib->get_time();
					
					//����ʤ������ä�
					unset($data["del"]);
					unset($data["update"]);
					
					//��Ͽ
					$c->rss_data->updateby($data,'no=' . $c->rss_data->escape($data["no"]));
					
					//��λ��å�����ɽ��
					$err2 = "��������λ���ޤ���";
					
					
				}else{
					//���顼����
					//�ǡ���ɽ�����ǥ��顼��å�������ɽ������
				}
	
			}
		}
	}
	//�ǡ���ɽ��
	//�ǡ�������
	$tmp = array(
					'id' => $user_id
				);
	if ($c->rss_data->getcount($tmp) >0){
		//�ǡ���ȯ��
		$c->set("datas", $c->rss_data->find($tmp, "no ASC" ));	//NO�Ǿ���˥�����
		//�����оݤΥ쥳���ɤ����ä��鿧���Ѥ���
		$c->set("change_no",$data["no"]);
	}else{
		$err2 = "<font color=red>��Ͽ�ǡ���������ޤ���</font>";
		$tmp = array();
		$c->set("datas",$tmp);	//����������顼�ˤʤ��к�
	}

	//�ǡ������å�
	$c->set("err2",$err2,TRUE);
	
//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/edit_rss.html" );

}

?>