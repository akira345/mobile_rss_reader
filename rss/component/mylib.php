<?php
//自分用クラスライブラリ

class mylib{
	//機種判定関数
	//戻値：１：DoCoMo 2:Au 3:SoftBank 0:その他(PC)
	function chk_mobile(){
	//注意！追加する際、J-PHONE関連はAUより先に判定すること！！
	//      (VodaFoneの一部機種にUP.Browserを返すものがあるため
	
		//ユーザーエージェント取得
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
			//SoftBank モトローラ
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
			//その他(PC)
			Return 0;
			Exit;
		}
	}
	
	function set_ime($par){
		//IME制御
		//キャリア判別
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
			//DoCoMoとAU
			switch ($par) {
			    case "IME_ON":
				return "istyle=\"1\"";
			        break;
			    case "IME_OFF":
			        return "istyle=\"3\"";//PC版にあわせるため、半角英字にする。
			        break;
				case "NUMBER":
			        return "istyle=\"4\"";//携帯のみ。
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
			        return "mode=\"alphabet\"";//PC版にあわせるため、半角英字にする。
			        break;
				case "NUMBER":
			        return "mode=\"numeric\"";//携帯のみ。
			        break;
			    default:
			        break;
			}
	
		}
	}
	
	//改行コードを<BR>タグにする
	function cr_to_br($IN_STR){
		$IN_STR = str_replace("\r\n", "<br>", $IN_STR);
		$IN_STR = str_replace("\r", "<br>", $IN_STR);
		$IN_STR = str_replace("\n", "<br>", $IN_STR);
		return $IN_STR;
	}
	//選択プルダウンメニューを作成。
	//引数：プルダウンの名前、最小値、最大値、表示No
	function value_list($name,$min_no,$max_no,$set_no){
	$ret = "<SELECT name=\"" . $name . "\">" . "\r\n";
		for($i=$min_no;$i<=$max_no;$i++){
			If ($i == $set_no){
				//一致したら
				$ret .= "<OPTION value=\"" . $i ."\" selected>" . $i . "</OPTION>" . "\r\n";
			}else{
				$ret .= "<OPTION value=\"" . $i ."\">" . $i . "</OPTION>" . "\r\n";
			}
		}
	$ret .= "</SELECT>" . "\r\n";
	return $ret;
	}
	
	//チェックボックスを表示する
	//引数：１：チェックあり それ以外：チェックなし　チェックボックスの名前、チェックされたときの値
	function make_chkbox($in_cd,$in_name,$in_value = 1){
		if ($in_cd == 1){
			$tmp = "CHECKED";
		}else{
			$tmp = "";
		}
		return "<input type=\"checkbox\" " . $tmp . " value=\"" . $in_value . "\" name=\"" . $in_name . "\">";
	}
	//再帰的にmb_convert_kanaを呼び出す関数定義
	//引数はmb_convert_kanaと同じ（第一引数が変数でも配列でもOK）
	function mb_convert_kana_variables($value,$option, $encoding){
	//http://soft.fpso.jp/develop/php/entry_1891.htmlを参考に作成してみた
		If (is_array($value)){
		//配列なら
			foreach ($value as $key => $val){
				//配列を展開する
				If (is_array($val)){
					//展開した値が配列だった
					//再帰的に呼び出す
					$val = mb_convert_kana_variables($value[$key],$option, $encoding);
				}else{
					$val = mb_convert_kana($val,$option, $encoding);
				}
				//展開した配列を元に戻す
				$value[$key] = $val;
			}
			return $value;
		}else{
			//配列ではない
			return mb_convert_kana($value,$option, $encoding);
		}
	}

}
	

?>