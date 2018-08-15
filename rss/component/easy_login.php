<?php
//簡単ログイン関連
//本当はIPでちゃんと調べないといけないが、簡易版とする。
//
//本クラスのベースはhttp://turi2.net/blog/709.htmlより拝借しました。
//
//以下のようなHTMLをはっておく
//<!-- form要素の場合 -->
//<form method="POST" action="./ktest.php" utn>
//  <input type="submit" value="ログイン" />
//</form>
//
class MobileInformation{


	var $_UserAgent;	//ユーザエージェント

	function MobileInformation(){
	//コンストラクタ
	//ユーザエージェントをセットするだけ
		$this->_UserAgent = $_SERVER["HTTP_USER_AGENT"];

	}

	//固体識別番号の取得
	function IndividualNum(){
		$line = "";
		$edline = 0;
		$agent = $this->_UserAgent;
		$len = strlen($agent);
		$rtn = 0;//戻り値
		$prob = mylib::chk_mobile();//キャリア判定
		//
		switch($prob){
			case 2:
			//AU
				if($_SERVER['HTTP_X_UP_SUBNO'] !== ''){
					//固体識別番号が入っていたら取得
					$rtn = $_SERVER['HTTP_X_UP_SUBNO'];
				}
				break;
			case 1:
			//DoCoMo
				if(strpos($agent, '/ser')){
					//非FOMA端末用
					$line = strpos($agent, '/ser') + 4;
				}
				if(strpos($agent, ';icc')){
					//Foma端末用
					$line = strpos($agent, ';icc') + 4;
				}
				//取得した情報の中よりユーザエージェント情報が邪魔なので消す
				if($line !== ''){
					$rtn = substr($agent, $line, $len-($line+1));
				}
				break;
			case 3:
			//SoftBank
				if(strpos($agent, '/SN')){
				//取得
					$line = strpos($agent, '/SN') + 3;
				}
				if($line !== ''){
					$edline = strpos($agent, ' ', $line);
				}
				if($edline !== ''){
					$rtn = substr($agent, $line, $edline-$line);
				}
				break;
			default:
			//その他
		}
		return $rtn;
	}
}
?>