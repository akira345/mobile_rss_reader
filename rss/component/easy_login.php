<?php
//��ñ�������Ϣ
//������IP�Ǥ�����Ĵ�٤ʤ��Ȥ����ʤ������ʰ��ǤȤ��롣
//
//�ܥ��饹�Υ١�����http://turi2.net/blog/709.html����Ҽڤ��ޤ�����
//
//�ʲ��Τ褦��HTML��ϤäƤ���
//<!-- form���Ǥξ�� -->
//<form method="POST" action="./ktest.php" utn>
//  <input type="submit" value="������" />
//</form>
//
class MobileInformation{


	var $_UserAgent;	//�桼�������������

	function MobileInformation(){
	//���󥹥ȥ饯��
	//�桼������������Ȥ򥻥åȤ������
		$this->_UserAgent = $_SERVER["HTTP_USER_AGENT"];

	}

	//���μ����ֹ�μ���
	function IndividualNum(){
		$line = "";
		$edline = 0;
		$agent = $this->_UserAgent;
		$len = strlen($agent);
		$rtn = 0;//�����
		$prob = mylib::chk_mobile();//����ꥢȽ��
		//
		switch($prob){
			case 2:
			//AU
				if($_SERVER['HTTP_X_UP_SUBNO'] !== ''){
					//���μ����ֹ椬���äƤ��������
					$rtn = $_SERVER['HTTP_X_UP_SUBNO'];
				}
				break;
			case 1:
			//DoCoMo
				if(strpos($agent, '/ser')){
					//��FOMAü����
					$line = strpos($agent, '/ser') + 4;
				}
				if(strpos($agent, ';icc')){
					//Fomaü����
					$line = strpos($agent, ';icc') + 4;
				}
				//�����������������桼������������Ⱦ��󤬼���ʤΤǾä�
				if($line !== ''){
					$rtn = substr($agent, $line, $len-($line+1));
				}
				break;
			case 3:
			//SoftBank
				if(strpos($agent, '/SN')){
				//����
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
			//����¾
		}
		return $rtn;
	}
}
?>