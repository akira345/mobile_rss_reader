<?php
//��ʬ�ѥ��饹�饤�֥��

class mylib{
	//����Ƚ��ؿ�
	//���͡�����DoCoMo 2:Au 3:SoftBank 0:����¾(PC)
	function chk_mobile(){
	//��ա��ɲä���ݡ�J-PHONE��Ϣ��AU������Ƚ�ꤹ�뤳�ȡ���
	//      (VodaFone�ΰ��������UP.Browser���֤���Τ����뤿��
	
		//�桼��������������ȼ���
		$IN_AGENT = $_SERVER["HTTP_USER_AGENT"];
	
		If ( preg_match( "/DoCoMo/", $IN_AGENT) ) {
			//DoCoMo
			Return 1;
			exit;
		} Else If ( preg_match( "/SoftBank/", $IN_AGENT)) {
			//SoftBank
			Return 3;
			exit;
		} Else If ( preg_match( "/J-PHONE/", $IN_AGENT)) {
			//SoftBank
			Return 3;
			exit;
		} Else If ( preg_match ("/Vodafone/", $IN_AGENT)){
			//SoftBank
			Return 3;
			exit;
		} Else If ( preg_match( "/MOT-/",$IN_AGENT)) {
			//SoftBank ��ȥ���
			Return 3;
			exit; 
		} Else If ( preg_match( "/UP\.Browser/", $IN_AGENT)) {
			//Au
			Return 2;
			exit;
		} Else If ( preg_match( "/KDDI-/", $IN_AGENT)){
			//Au
			Return 2;
			exit;
		} Else {
			//����¾(PC)
			Return 0;
			Exit;
		}
	}
	
	function set_ime($par){
		//IME����
		//����ꥢȽ��
		$kyaria = $this->chk_mobile();
		If ($kyaria===0){
			//PC
			switch ($par) {
			    case "IME_ON":
				return "style=\"ime-mode:active\"";
			        break;
			    case "IME_OFF":
			    case "NUMBER":
			        return "style=\"ime-mode:disabled\"";
			        break;
			    default:
			        break;
			}
		}
		If ($kyaria === 1 || $kyaria === 2){
			//DoCoMo��AU
			switch ($par) {
			    case "IME_ON":
				return "istyle=\"1\"";
			        break;
			    case "IME_OFF":
			        return "istyle=\"3\"";//PC�Ǥˤ��碌�뤿�ᡢȾ�ѱѻ��ˤ��롣
			        break;
				case "NUMBER":
			        return "istyle=\"4\"";//���ӤΤߡ�
			        break;
			    default:
			        break;
			}
	
		}
		If ($kyaria === 3){
			//SoftBank
			switch ($par) {
			    case "IME_ON":
				return "mode=\"hiragana\"";
			        break;
			    case "IME_OFF":
			        return "mode=\"alphabet\"";//PC�Ǥˤ��碌�뤿�ᡢȾ�ѱѻ��ˤ��롣
			        break;
				case "NUMBER":
			        return "mode=\"numeric\"";//���ӤΤߡ�
			        break;
			    default:
			        break;
			}
	
		}
	}
	
	//���ԥ����ɤ�<BR>�����ˤ���
	function cr_to_br($IN_STR){
		$IN_STR = str_replace("\r\n", "<br>", $IN_STR);
		$IN_STR = str_replace("\r", "<br>", $IN_STR);
		$IN_STR = str_replace("\n", "<br>", $IN_STR);
		return $IN_STR;
	}
	//����ץ�������˥塼�������
	//�������ץ�������̾�����Ǿ��͡������͡�ɽ��No
	function value_list($name,$min_no,$max_no,$set_no){
	$ret = "<SELECT name=\"" . $name . "\">" . "\r\n";
		for($i=$min_no;$i<=$max_no;$i++){
			If ($i == $set_no){
				//���פ�����
				$ret .= "<OPTION value=\"" . $i ."\" selected>" . $i . "</OPTION>" . "\r\n";
			}else{
				$ret .= "<OPTION value=\"" . $i ."\">" . $i . "</OPTION>" . "\r\n";
			}
		}
	$ret .= "</SELECT>" . "\r\n";
	return $ret;
	}
	
	//�����å��ܥå�����ɽ������
	//���������������å����� ����ʳ��������å��ʤ��������å��ܥå�����̾���������å����줿�Ȥ�����
	function make_chkbox($in_cd,$in_name,$in_value = 1){
		if ($in_cd == 1){
			$tmp = "CHECKED";
		}else{
			$tmp = "";
		}
		return "<input type=\"checkbox\" " . $tmp . " value=\"" . $in_value . "\" name=\"" . $in_name . "\">";
	}
	//�Ƶ�Ū��mb_convert_kana��ƤӽФ��ؿ����
	//������mb_convert_kana��Ʊ�������������ѿ��Ǥ�����Ǥ�OK��
	function mb_convert_kana_variables($value,$option, $encoding){
	//http://soft.fpso.jp/develop/php/entry_1891.html�򻲹ͤ˺������Ƥߤ�
		If (is_array($value)){
		//����ʤ�
			foreach ($value as $key => $val){
				//�����Ÿ������
				If (is_array($val)){
					//Ÿ�������ͤ�������ä�
					//�Ƶ�Ū�˸ƤӽФ�
					$val = mb_convert_kana_variables($value[$key],$option, $encoding);
				}else{
					$val = mb_convert_kana($val,$option, $encoding);
				}
				//Ÿ����������򸵤��᤹
				$value[$key] = $val;
			}
			return $value;
		}else{
			//����ǤϤʤ�
			return mb_convert_kana($value,$option, $encoding);
		}
	}

}
	

?>