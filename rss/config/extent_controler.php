<?php
//ちいたんのコントローラクラスを拡張する。
class CMyController extends CController
{
	var $encoding;	//エンコーディング

    function CMyController()
    {
		//コンストラクタ
    }

	function setEncoding($encode = 'EUC-JP'){//デフォルトはＥＵＣにする
		$this->encoding = $encode;
	}
	function getEncoding(){
		return $this->encoding;
	}
	function setEscape($value){
	//http://soft.fpso.jp/develop/php/entry_1891.htmlを参考に作成してみた
		If (is_array($value)){
			foreach ($value as $key => $val){
				//配列を展開する
				If (is_array($val)){
					//展開した値が配列だった
					//再帰的に呼び出す
					$val = $this->setEscape($val);
				}else{
					$val = htmlentities($val, ENT_QUOTES,$this->encoding);
				}
				//展開した配列を元に戻す
				$value[$key] = $val;
			}
			return $value;
		}else{
			return htmlentities($value, ENT_QUOTES,$this->encoding);
		}
	}
	function set( $name, $value, $out_tag_flg = FALSE )
	{
		//出力時にhtmlentitiesを通す。ただし、
		//タグ出力フラグがONの場合はスルーする
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
		###madhatterさんのコードを拝借し、一部修正
		if(!$_COOKIE[session_name()]){
			$url .= ( strpos($url, "?") != false ? "&" : "?" ) . urlencode(session_name()) . "=" . htmlspecialchars(session_id(),ENT_QUOTES);
		}
		###
		header( "Location: " . $url );
		exit();
	}

}
?>
