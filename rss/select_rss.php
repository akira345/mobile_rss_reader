<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//�桼��ǧ�ڤ򤫤���
function is_secure(&$c){
	return true;
}
function action( &$c )
{
//����ȥ�����
	//�桼���ɣĥ��å�
	$user_id = $_SESSION["RSS"]["USER"]["id"];
	If (count($_GET)){
		//Get�˲������äƤ���
		//�ǡ�����Ͽ��
		$data=array();
		//GET�ǡ����������
		$data["category_cd"]=$c->s->gett("category_cd");
		If ($data["category_cd"] != ""){
			//���ߥ��ƥ������Ͽ�����뤫�����å�����
			$tmp = array(
						'id' => $user_id,
						'category_cd' => $data["category_cd"],
						'hidden_chk' => '0'
					);
			if ($c->rss_data->getcount($tmp) >0){
				//�ǡ���ȯ��
				$c->set("rss_datas", $c->rss_data->find($tmp, "no ASC" ));	//NO�Ǿ���˥�����
			}else{
			//�ǡ����ʤ�
				$c->set("err","��Ͽ�Ϥ���ޤ���");
			}
			//�ƥ�ץ졼�ȥե��������
			$c->SetViewFile( "./tmplate/select_rss.html" );
		}
		//get�ʤ�
	}
	//get�ʤ�
}
?>