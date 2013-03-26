<?php

	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}

function action( &$c )
{	//������к�
	$token = sha1(uniqid(mt_rand(), true));

	 // �ȡ�����򥻥å������ɲä���
	$_SESSION['token'][] = $token;

	//�桼���ɣĥ��å�
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	//�ȡ���������
	$c->set("token",$token,'FALSE');

	//���顼��å�������(����ɽ��)
	$err2="";

    if( count( $_POST ) )
    {
	//POST�Ǥʤ����äƤ���
	//������к�
		// �������줿�ȡ����󤬥��å����Υȡ������������ˤ��뤫Ĵ�٤�
		$key = array_search($_POST['token'], $_SESSION['token']);

		if ($key !== false) {
		    // ����� POST
		    unset($_SESSION['token'][$key]); // ���ѺѤߥȡ�������˴�

			//�ǡ�����Ͽ��
			$data=array();

			//���顼��å�������
			$err="";

			//POST�ǡ����������
			$data["rss_url"]=$c->s->postt("rss_url");
			$data["comment"]=$c->s->postt("comment");
			$data["cnv_keitai"]=$c->s->postt("cnv_keitai");
			$data["view_cnt"]=$c->s->postt("view_cnt");
			$data["category_cd"]=$c->s->postt("category_cd");
			//�᡼�����������å�����������ɲ�
			$data["haisin_flg"]=$c->s->postt("haisin_flg");
			$data["keyword"]=$c->s->postt("keyword");


			//�ѥ�᥿�Υ����å�

			//ɬ������
			$err .= $c->v->notempty($data["rss_url"],"RSS��ɬ�����ϤǤ�<BR>");
			$err .= $c->v->notempty($data["comment"],"�����Ȥ�ɬ�����ϤǤ�<BR>");
			$err .= $c->v->notempty($data["view_cnt"],"ɽ�������ɬ�����ϤǤ�<BR>");
			$err .= $c->v->notempty($data["category_cd"],"���ƥ����ɬ�����ϤǤ�<BR>");

			if ($err == ""){
				//�ѿ��������å�
				//���椬�ޤޤ��Τǥ����å���ѥ�
				//$err .= $c->v->eisu($data["rss_url"],"RSS��Ⱦ�ѱѿ����ΤߤǤ�<BR>");
			}
			if ($err == ""){
				//���������å�
				$err .= $c->v->number($data["view_cnt"],"ɽ�������Ⱦ�ѿ����ΤߤǤ�<BR>");
				$err .= $c->v->number($data["category_cd"],"���ƥ�������������Ǥ�<BR>");
			}
			if ($err == ""){
				//URL�����å�
				//��������ΥХ�ơ��Ȥ��ĥ�������ä����ɤ�ʬ����ʤ��ä��ΤǶ��̥��饹�ǽ�������
				$err .= $c->common_lib->chk_url($data["rss_url"],"RSS�Υ��ɥ쥹�������Ǥ�<BR>");
			}
			if ($err == ""){
				//ͭ���ϰϥ����å�
				$err .= $c->v->len($data["rss_url"],1,2000,"RSS�ϣ�ʸ���ʾ�2000ʸ���ʲ��Ǥ�<BR>");
				$err .= $c->v->len($data["comment"],1,512,"�����Ȥ����ѣ�ʸ���ʾ�256ʸ���ʲ��Ǥ�<BR>");
				$err .= $c->v->len($data["view_cnt"],1,3,"ɽ������ϣ�ʸ���ʾ�3ʸ���ʲ��Ǥ�<BR>");
				$err .= $c->v->len($data["category_cd"],1,2,"���ƥ�������������Ǥ�<BR>");
			}

			//ǰ�Τ����ʣ��Ͽ�����å��򤹤�
			if ($err == ""){
				$tmp = array(
								'id' => $user_id,
								'rss_url' => $data["rss_url"]
							);
				if ($c->rss_data->getcount($tmp) >0){
					//�ǡ���ȯ��
					$err .= "���Ǥ�Ʊ��RSS����Ͽ����Ƥ��ޤ�<BR>";
				}
			}

			if ($err == ""){
				//���顼�ʤ�

				//�����Ѵ��Υ����å��ܥå������Ѵ�����
				If($data["cnv_keitai"]==''){
					$data["cnv_keitai"] = 0;
				}else{
					$data["cnv_keitai"] = 1;
				}
				//�᡼���ۿ��ե饰�Υ����å��ܥå������Ѵ�����
				If($data["haisin_flg"]==''){
					$data["haisin_flg"] = 0;
				}else{
					$data["haisin_flg"] = 1;
				}

				//�桼�����󡢽���ͥ��å�
				$data["id"] = $user_id;
				$data["hidden_chk"] = 0;

				//��Ͽ�����å�
				$data["touroku_date"] = $c->common_lib->get_date();
				$data["touroku_time"] = $c->common_lib->get_time();

				//��Ͽ
				$c->rss_data->insert($data);

				//��λ��å�����ɽ��
				$err = "��Ͽ����λ���ޤ���";

				$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON

			}else{
				//���顼����
				$c->set("err",$err,'TRUE');//���顼��å������ϥ�������ON

				//�ǡ������å�
				$c->set("rss_url",$data["rss_url"],'TRUE');
				$c->set("comment",$data["comment"]);
				$c->set("cnv_keitai",$data["cnv_keitai"]);
				$c->set("view_cnt",$data["view_cnt"]);
				$c->set("category_cd",$data["category_cd"]);
				//�᡼���ۿ��ե饰����������ɲ�
				$c->set("haisin_flg",$data["haisin_flg"]);
				$c->set("keyword",$data["keyword"]);


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
	}else{
		$err2 = "��Ͽ�ǡ���������ޤ���";
		$tmp = array();
		$c->set("datas",$tmp);	//���������顼�ˤʤ��к�
	}

	//�ǡ������å�
	$c->set("err2",$err2,TRUE);//���顼��å������ϥ�������ON

//�ƥ�ץ졼�ȥե��������
	$c->SetViewFile( "./tmplate/add_rss.html" );

}

?>