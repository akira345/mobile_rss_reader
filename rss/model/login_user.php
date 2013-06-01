<?php
class Clogin_user extends CModel{
//��������ȴ�����ǥ�
	private  $err_msg = '';	//���顼��å�����

	//���顼��å�����
	//�������ʤ�
	//�����:���顼��å�����
	function show_msg(){
		return $this->err_msg;
	}

	//�꥿���󥳡�������
	//���������顼��å�����
	//����͡���å��������ꡧFALSE�ʤ�TRUE
	private function ret($in_data){
		If ($in_data){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	//���������å���
	//�������о�����õ������
	//����͡��о�����˥�����������TRUE�ʤ�FALSE
	private function chk_input($in_data=array(),$key){
		$this->err_msg = "";
		If (array_key_exists($key, $in_data)){
			return TRUE ;
		}else{
			$this->err_msg = "�ѥ�᥿���顼";
			return FALSE;
		}
	}

	//DB��Ͽ������Ͽ��
	//�ä����������פǤ��礦����
	private function get_date(){
		return date('Ymd');
	}
	private function get_time(){
		return date('His');
	}

	//��������ȥ���Ͽ
	//��������Ͽ����桼��ID�ֹ�,���
	//����͡�True Or False
	function record($id,$memo){
		$tmp =array();
		If($id == '' || $memo == ''){
			return FALSE;
		}
		$tmp = array(
				'id' => $id,
				'memo' => $memo,
				'touroku_date' => date('Ymd'),
				'touroku_time' => date('His'),
				'ip' => $_SERVER["REMOTE_ADDR"],
				'kyaria_cd' => mylib::chk_mobile(),//����ꥢȽ��
				'agent' => $_SERVER["HTTP_USER_AGENT"],
				);
				$this->table = "login_his";//���ơ��֥����
		If($this->insert($tmp)){
			$this->table = "login_user";	//�ơ��֥�̾���᤹
			return TRUE;
		}else{
			return FALSE;
		}

	}

	//��������Ƚ�ʣ�����å�
	//��������������Ⱦ�������
	//����͡�True Or False
	function double_chk($data=array()){
		$this->err_msg = "";
		If(!$this->chk_input($data,"user_name")){
			return false;
		}
		//���Ǥ�Ʊ̾�Υ桼����¸�ߤ��ʤ��������å�����
		if ($this->getcount("user_name='" . $this->escape($data["user_name"]) . "'") > 0){
			$this->err_msg = "���Ǥ�Ʊ̾��ID����Ͽ����Ƥ��ޤ�<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//��������Ƚ�ʣ�����å�(����)
	//��������������Ⱦ�������
	//����͡�True Or False
	function mb_double_chk($data=array()){
		$this->err_msg = "";
		If(!$this->chk_input($data,"mb_key")){
			return false;
		}
		//���Ǥ�Ʊ�����ӵ����ֹ椬¸�ߤ��ʤ��������å�����
		if ($this->getcount("mb_key='" . $this->escape($data["mb_key"]) . "'") >0){
			//�ǡ���ȯ��
			$this->err_msg = "���Ǥ���Ͽ������ޤ�����<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//�����������Ͽ
	//��������������Ⱦ�������
	//����͡�True Or False
	//��ա��ѥ�᥿�Υ����å��ϻ����˹ԤäƤ�������
	function touroku($data=array()){
		$this->err_msg = "";
		$tmp = array();
		If(!$this->chk_input($data,"user_name")){
			return false;
		}
		If(!$this->chk_input($data,"password")){
			return false;
		}
		//��ʣ�����å�
		If(!($this->double_chk($data))){
			return FALSE;
		}
		//ID�򥻥å�
		$tmp["user_name"] = $data["user_name"];
		//�ѥ���ɤ�MD5���Ѵ�����
		$tmp["password"] = md5($data["password"]);

		//��Ͽ�����å�
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();

		//��Ͽ
		If (!$this->insert($tmp)){
			$this->err_msg = "�桼������Ͽ�˼��Ԥ��ޤ���<BR>";
			return FALSE;
		}
		//��Ͽ���줿ID�ֹ����
		If(!$tmp = $this->findone($tmp)){
			$this->err_msg = "�����ƥ२�顼";
			return FALSE;
		}
		//���˵�Ͽ
		If (!$this->record($tmp["id"],"�桼����Ͽ")){
			$this->err_msg = "�����ƥ२�顼<BR>";
			return FALSE;
		}
		return $this->ret($this->err_msg);
	}
	//�����������Ͽ(����)
	//��������������Ⱦ�������
	//����͡�True Or False
	function mb_touroku($data=array()){
		$this->err_msg = "";
		$tmp = array();
		If(!$this->chk_input($data,"id")){
			return false;
		}
		If(!$this->chk_input($data,"mb_key")){
			return false;
		}
		//��ʣ�����å�
		If(!($this->mb_double_chk($data))){
			return FALSE;
		}
		//�������å�
		$tmp['mb_key'] = $data["mb_key"];
		$tmp["id"] = $data["id"];
		//��Ͽ�����å�
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();
		//��Ͽ
		//���˵�Ͽ
		If (!$this->record($tmp["id"],"���ӥ桼����Ͽ")){
			$this->err_msg = "�����ƥ२�顼<BR>";
			return FALSE;
		}
		If(!$this->updateby($tmp,'id=' . $this->escape($tmp["id"]))){
			$this->err_msg = "���Ӥ���Ͽ�˼��Ԥ��ޤ���<BR>";
		}
		return $this->ret($this->err_msg);
	}

	//�����������Ͽ(����)
	//��������������Ⱦ�������
	//����͡�True Or False
	//��ա����å���������������
	function kill_acount($data=array()){
		$this->err_msg = "";
		$tmp =array();
		If(!$this->chk_input($data,"id")){
			return false;
		}
		//�����¸�߳�ǧ
		if ($this->getcount("id='" . $this->escape($data["id"]) . "'") == 0){
			$this->err_msg = "�����ƥ२�顼<BR>";
			return False;
		}
		//���˵�Ͽ
		If(!$this->record($data["id"],"�桼�����")){
			$this->err_msg="�����ƥ२�顼<BR>";
			return FALSE;
		}
		//���
		If(!$this->del("id='" . $this->escape($data["id"]) . "'")){
			$this->err_msg = "�桼���κ���˼��Ԥ��ޤ���<BR>";
			return FALSE;
		}
		//���å���������
		If(!$this->log_out($data)){
			return False;
		}
		return TRUE;
	}
	//�������Ƚ���
	//��������������Ⱦ���
	//����͡�True Or False
	//��ա�������쥯�ȤϤ��ʤ�
	function log_out($data=array()){
		$this->err_msg = "";
		//�������Ƚ���
		If(!$this->chk_input($data,"id")){
			return false;
		}
		//�����ϥޥ˥奢��ɤ���˽���������
		// ���å����ν����
		// session_name("something")����Ѥ��Ƥ�������äˤ����˺��ʤ��褦��!
		session_start();

		// ���å�����ѿ������Ʋ������
		$_SESSION = array();

		// ���å��������Ǥ���ˤϥ��å���󥯥å����������롣
		// Note: ���å�����������Ǥʤ����å������˲����롣
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}

		// �ǽ�Ū�ˡ����å������˲�����
		session_destroy();
		//���å����ե������������
		$session_file = session_save_path() . '/sess_' . $session_id;
		if ( is_file( $session_file ) ) {
			unlink( $session_file );
		}
		//���˵�Ͽ
		If(!$this->record($data["id"],"��������")){
			$this->err_msg = "�����ƥ२�顼<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//�ѥ�����ѹ�����
	//��������������Ⱦ���
	//����͡�True Or False
	//��ա��ѥ�᥿�Υ����å��ϻ����˹ԤäƤ�������
	function change_password($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//���ϥ����å�
		If(!$this->chk_input($data,"id")){
			return false;
		}
		If(!$this->chk_input($data,"password")){
			return false;
		}
		//¸�߳�ǧ
		if ($this->getcount("id='" . $this->escape($data["id"]) . "'") == 0){
			$this->err_msg = "�����ƥ२�顼<BR>";
			return False;
		}
		//�������å�
		//�ѥ���ɤ�MD5���Ѵ�����
		$tmp["password"] = md5($data["password"]);
		$tmp["id"] = $data["id"];
		//��Ͽ�����å�
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();
		//��Ͽ
		//���˵�Ͽ
		If (!$this->record($tmp["id"],"�ѥ�����ѹ�")){
			$this->err_msg = "�����ƥ२�顼<BR>";
			return FALSE;
		}
		If (!$this->updateby($tmp,'id=' . $this->escape($tmp["id"]))){
			$this->err_msg = "�ѥ���ɤ��ѹ��˼��Ԥ��ޤ���<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//���������
	//��������������Ⱦ���
	//����͡�True Or False
	//��ա��ѥ�᥿�Υ����å��ϻ����˹ԤäƤ�������
	function login($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//��ñ������ǤϤʤ��ޤ������б�ü��
		If(!$this->chk_input($data,"password")){
			return false;
		}
		If(!$this->chk_input($data,"user_name")){
			return false;
		}
		//����������å�
		$tmp=array(
					'user_name' => $data["user_name"],
					'password' => md5($data["password"])
					);
		if ($this->getcount($tmp) == 1){
			//������OK
			//�桼��������򥻥å���������
			IF(!$_SESSION["RSS"]["USER"] = $this->findone($tmp)){
				$this->err_msg="�����ƥ२�顼<BR>";
				return FALSE;
			}
			//���˵�Ͽ
			IF (!$this->record($_SESSION["RSS"]["USER"]["id"],"������")){
				$this->err_msg = "�����ƥ२�顼<BR>";
				return FALSE;
			}
			return TRUE;
		}else{
			$this->err_msg = "ID�ޤ��ϥѥ���ɤ��㤤�ޤ���<BR>";
			return FALSE;
		}
	}

	//���������(����)
	//��������������Ⱦ���
	//����͡�True Or False
	//��ա��ѥ�᥿�Υ����å��ϻ����˹ԤäƤ�������
	function mb_login($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//��ñ������
		//���ϥ����å�
		If(!$this->chk_input($data,"mb_key")){
			return false;
		}
		//����������å�
		$tmp=array(
					'mb_key' => $data["mb_key"],
					);
		if ($this->getcount($tmp) == 1){
			//������OK
			//�桼��������򥻥å���������
			IF(!$_SESSION["RSS"]["USER"] = $this->findone($tmp)){
				$this->err_msg="�����ƥ२�顼<BR>";
				return FALSE;
			}
			//���˵�Ͽ
			If(!$this->record($_SESSION["RSS"]["USER"]["id"],"���Ӥ�������")){
				$this->err_msg = "�����ƥ२�顼<BR>";
				return FALSE;
			}
			return TRUE;
		}else{
			$this->err_msg = "����������Ӥ���Ͽ����Ƥ��ޤ���<BR>";
			return FALSE;
		}
	}
	//�᡼�륢�ɥ쥹�ɲý���
	//��������������Ⱦ���
	//����͡�True Or False
	//��ա��ѥ�᥿�Υ����å��ϻ����˹ԤäƤ�������
	function add_email($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//���ϥ����å�
		If(!$this->chk_input($data,"id")){
			return false;
		}
		If(!$this->chk_input($data,"e-mail")){
			return false;
		}
		//¸�߳�ǧ
		if ($this->getcount("id='" . $this->escape($data["id"]) . "'") == 0){
			$this->err_msg = "�����ƥ२�顼<BR>";
			return False;
		}
		//�������å�
		$tmp["e-mail"] = $data["e-mail"];
		$tmp["id"] = $data["id"];
		//��Ͽ�����å�
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();
		//��Ͽ
		//���˵�Ͽ
		If (!$this->record($tmp["id"],"�᡼�륢�ɥ쥹��Ͽ")){
			$this->err_msg = "�����ƥ२�顼<BR>";
			return FALSE;
		}
		If (!$this->updateby($tmp,'id=' . $this->escape($tmp["id"]))){
			$this->err_msg = "�᡼�륢�ɥ쥹����Ͽ�˼��Ԥ��ޤ���<BR>";
		}
		return $this->ret($this->err_msg);
	}

}
