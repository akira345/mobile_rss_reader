<?php
//���̥��饹�饤�֥��
class common_lib{
	//�����󤷤Ƥ��뤫�Υ����å��ؿ�
	function check_login(){
	    if( empty( $_SESSION["RSS"]["USER"] ) ){
		//�����󤷤Ƥ��ʤ�
			return FALSE;
		}else{
		//�����󤷤Ƥ���
			return TRUE;
		}
	}
	//DB��Ͽ������Ͽ��
	function get_date()
	{
		return date('Ymd');
	}
	function get_time()
	{
		return date('His');
	}
	//URL�������뤫�����å�����
	function chk_url($data,$errmsg = ""){
		return $this->_check(preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/',$data),$errmsg);
	}
	function _check( $b, $errmsg )
	{
		if( $b )
		{
			if( $errmsg )	return "";
			else			return TRUE;
		}
		else
		{
			if( $errmsg )	return $errmsg;
			else			return FALSE;
		}
	}
	function make_category_list( &$c ,$name="",$user_id="",$set_no=""){
	
			$ret = "<SELECT name=\"" . $name . "\">" . "\r\n";
			$ret .="<option selected value=\"0\">����ʤ�</option>" . "\r\n";
			$tmp = array();
			$datas = array();
			$tmp = array(
							'id' => $user_id
						);
			if($c->category->getcount($tmp)>0){
				$data["datas"]=$c->category->find($tmp,"no ASC");
			}else{
				$ret .= "</SELECT>" . "\r\n";
				return $ret;
			}
			foreach( $data["datas"] as $var ){ 
				If ($var["no"] == $set_no){
					//���פ�����
					$ret .= "<OPTION value=\"" . $var["no"] ."\" selected>" . $var["category"] . "</OPTION>" . "\r\n";
				}else{
					$ret .= "<OPTION value=\"" . $var["no"] ."\">" . $var["category"] . "</OPTION>" . "\r\n";
				}
			}
		$ret .= "</SELECT>" . "\r\n";
		return $ret;
	}
	function make_rss_list( &$c ,$name="",$user_id=""){
	
			$ret = "<SELECT name=\"" . $name . "\">" . "\r\n";
			$tmp = array();
			$datas = array();
			$tmp = array(
							'id' => $user_id
						);
			if($c->rss_data->getcount($tmp)>0){
				$data["datas"]=$c->rss_data->find($tmp,"no ASC");
			}else{
				return FALSE;
			}
			foreach( $data["datas"] as $var ){ 
				$ret .= "<OPTION value=\"" . $var["no"] ."\">" . $var["comment"] . "</OPTION>" . "\r\n";
			}
		$ret .= "</SELECT>" . "\r\n";
		return $ret;
	}
	//����˳�Ǽ���줿������ɤ�¸�ߤ������ɤ�����Ƚ�ꤹ��ؿ����
	//�����������о�ʸ���󡢥�����ɤ����ä�Ϣ������
	//���͡��������¸�ߤ����True)�ʤ���False��
	function array_strpos($in_str,$in_array_keyword){
		$ret = FALSE;
		foreach ($in_array_keyword as $key){
			$ret = stripos($in_str,$key);
			If ($ret !== FALSE){
				$ret = TRUE;
				break;
			}
		}
		return $ret;
	}	
}
?>