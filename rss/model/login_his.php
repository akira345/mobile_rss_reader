<?php
class Clogin_his extends CModel{
//��������ȴ�����ǥ�
	function last_login($id){
		$tmp = array();
		$tmp = array(
				'id' => $id,
				);
		if ($this->getcount($tmp) >1){
			$query = "SELECT MAX(NO) MAX_NO FROM login_his";

			$results = $this->findquery( $query, $tmp );
			$results = $this->findone("id='" . $this->escape($id) ."' AND no < " . $this->escape($results[0]["MAX_NO"])  , "no DESC" );
			return "�ǽ�����������" . date("Y/m/d",strtotime($results["touroku_date"])) . "��" . date("H:i:s",strtotime($results["touroku_time"])) . "�Ǥ���<br>";
		}else{
			return "��������Ǥ���<BR>";
		}
	}
}
