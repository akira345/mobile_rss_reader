<?php
//��������Υ���ȥ��饯�饹���ĥ���롣
class CMyController extends CController
{
	var $encoding;	//���󥳡��ǥ���

    function CMyController()
    {
		//���󥹥ȥ饯��
    }

	function setEncoding($encode = 'EUC-JP'){//�ǥե���Ȥϣţգäˤ���
		$this->encoding = $encode;
	}
	function getEncoding(){
		return $this->encoding;
	}
	function setEscape($value){
	//http://soft.fpso.jp/develop/php/entry_1891.html�򻲹ͤ˺������Ƥߤ�
		If (is_array($value)){
			foreach ($value as $key => $val){
				//�����Ÿ������
				If (is_array($val)){
					//Ÿ�������ͤ�������ä�
					//�Ƶ�Ū�˸ƤӽФ�
					$val = $this->setEscape($val);
				}else{
					$val = htmlentities($val, ENT_QUOTES,$this->encoding);
				}
				//Ÿ����������򸵤��᤹
				$value[$key] = $val;
			}
			return $value;
		}else{
			return htmlentities($value, ENT_QUOTES,$this->encoding);
		}
	}
	function set( $name, $value, $out_tag_flg = FALSE )
	{
		//���ϻ���htmlentities���̤�����������
		//�������ϥե饰��ON�ξ��ϥ��롼����
		If ($out_tag_flg == FALSE){
			$this->variables[$name] = $this->setEscape($value);
		}else{
			$this->variables[$name]	= $value;
		}
	}

	function redirect( $url, $is301 = FALSE )
	{
		if( $is301 )
		{
			header( "HTTP/1.1 301 Moved Permanently" );
		}
		###madhatter����Υ����ɤ��Ҽڤ�����������
		if(!$_COOKIE[session_name()]){
			$url .= ( strpos($url, "?") != false ? "&" : "?" ) . urlencode(session_name()) . "=" . htmlspecialchars(session_id(),ENT_QUOTES);
		}
		###
		header( "Location: " . $url );
		exit();
	}

}
?>
