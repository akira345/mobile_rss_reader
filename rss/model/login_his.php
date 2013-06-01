<?php
class Clogin_his extends CModel{
//アカウント管理モデル
	function last_login($id){
		$tmp = array();
		$tmp = array(
				'id' => $id,
				);
		if ($this->getcount($tmp) >1){
			$query = "SELECT MAX(NO) MAX_NO FROM login_his";

			$results = $this->findquery( $query, $tmp );
			$results = $this->findone("id='" . $this->escape($id) ."' AND no < " . $this->escape($results[0]["MAX_NO"])  , "no DESC" );
			return "最終アクセスは" . date("Y/m/d",strtotime($results["touroku_date"])) . "　" . date("H:i:s",strtotime($results["touroku_time"])) . "です。<br>";
		}else{
			return "初回ログインです。<BR>";
		}
	}
}
