<?php
//共通クラスライブラリ
class common_lib{
	//ログインしているかのチェック関数
	function check_login(){
	    if( empty( $_SESSION["RSS"]["USER"] ) ){
		//ログインしていない
			return FALSE;
		}else{
		//ログインしている
			return TRUE;
		}
	}
	//DB登録日時記録用
	function get_date()
	{
		return date('Ymd');
	}
	function get_time()
	{
		return date('His');
	}
	//URLを構成するかチェックする
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
			$ret .="<option selected value=\"0\">指定なし</option>" . "\r\n";
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
					//一致したら
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
	//配列に格納されたキーワードが存在したかどうかを判定する関数定義
	//引数：検索対象文字列、キーワードの入った連想配列
	//戻値：キーワード存在あり（True)なし（False）
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