<?php
class Clogin_user extends CModel{
//アカウント管理モデル
	private  $err_msg = '';	//エラーメッセージ

	//エラーメッセージ
	//引数：なし
	//戻り値:エラーメッセージ
	function show_msg(){
		return $this->err_msg;
	}

	//リターンコード設定
	//引数：エラーメッセージ
	//戻り値：メッセージあり：FALSEなしTRUE
	private function ret($in_data){
		If ($in_data){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	//引数チェック用
	//引数：対象配列、探すキー
	//戻り値：対象配列にキーがある場合TRUEないFALSE
	private function chk_input($in_data=array(),$key){
		$this->err_msg = "";
		If (array_key_exists($key, $in_data)){
			return TRUE ;
		}else{
			$this->err_msg = "パラメタエラー";
			return FALSE;
		}
	}

	//DB登録日時記録用
	//特に説明は不要でしょう・・
	private function get_date(){
		return date('Ymd');
	}
	private function get_time(){
		return date('His');
	}

	//アカウントログ記録
	//引数：記録するユーザID番号,メモ
	//戻り値：True Or False
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
				'kyaria_cd' => mylib::chk_mobile(),//キャリア判定
				'agent' => $_SERVER["HTTP_USER_AGENT"],
				);
				$this->table = "login_his";//ログテーブル指定
		If($this->insert($tmp)){
			$this->table = "login_user";	//テーブル名を戻す
			return TRUE;
		}else{
			return FALSE;
		}

	}

	//アカウント重複チェック
	//引数：アカウント情報配列
	//戻り値：True Or False
	function double_chk($data=array()){
		$this->err_msg = "";
		If(!$this->chk_input($data,"user_name")){
			return false;
		}
		//すでに同名のユーザが存在しないかチェックする
		if ($this->getcount("user_name='" . $this->escape($data["user_name"]) . "'") > 0){
			$this->err_msg = "すでに同名のIDが登録されています<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//アカウント重複チェック(携帯)
	//引数：アカウント情報配列
	//戻り値：True Or False
	function mb_double_chk($data=array()){
		$this->err_msg = "";
		If(!$this->chk_input($data,"mb_key")){
			return false;
		}
		//すでに同じ携帯機種番号が存在しないかチェックする
		if ($this->getcount("mb_key='" . $this->escape($data["mb_key"]) . "'") >0){
			//データ発見
			$this->err_msg = "すでに登録がありました。<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//アカウント登録
	//引数：アカウント情報配列
	//戻り値：True Or False
	//注意：パラメタのチェックは事前に行っておくこと
	function touroku($data=array()){
		$this->err_msg = "";
		$tmp = array();
		If(!$this->chk_input($data,"user_name")){
			return false;
		}
		If(!$this->chk_input($data,"password")){
			return false;
		}
		//重複チェック
		If(!($this->double_chk($data))){
			return FALSE;
		}
		//IDをセット
		$tmp["user_name"] = $data["user_name"];
		//パスワードをMD5で変換する
		$tmp["password"] = md5($data["password"]);

		//登録日セット
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();

		//登録
		If (!$this->insert($tmp)){
			$this->err_msg = "ユーザの登録に失敗しました<BR>";
			return FALSE;
		}
		//登録されたID番号取得
		If(!$tmp = $this->findone($tmp)){
			$this->err_msg = "システムエラー";
			return FALSE;
		}
		//ログに記録
		If (!$this->record($tmp["id"],"ユーザ登録")){
			$this->err_msg = "システムエラー<BR>";
			return FALSE;
		}
		return $this->ret($this->err_msg);
	}
	//アカウント登録(携帯)
	//引数：アカウント情報配列
	//戻り値：True Or False
	function mb_touroku($data=array()){
		$this->err_msg = "";
		$tmp = array();
		If(!$this->chk_input($data,"id")){
			return false;
		}
		If(!$this->chk_input($data,"mb_key")){
			return false;
		}
		//重複チェック
		If(!($this->mb_double_chk($data))){
			return FALSE;
		}
		//キーセット
		$tmp['mb_key'] = $data["mb_key"];
		$tmp["id"] = $data["id"];
		//登録日セット
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();
		//登録
		//ログに記録
		If (!$this->record($tmp["id"],"携帯ユーザ登録")){
			$this->err_msg = "システムエラー<BR>";
			return FALSE;
		}
		If(!$this->updateby($tmp,'id=' . $this->escape($tmp["id"]))){
			$this->err_msg = "携帯の登録に失敗しました<BR>";
		}
		return $this->ret($this->err_msg);
	}

	//アカウント登録(携帯)
	//引数：アカウント情報配列
	//戻り値：True Or False
	//注意：セッション情報も削除される
	function kill_acount($data=array()){
		$this->err_msg = "";
		$tmp =array();
		If(!$this->chk_input($data,"id")){
			return false;
		}
		//削除前存在確認
		if ($this->getcount("id='" . $this->escape($data["id"]) . "'") == 0){
			$this->err_msg = "システムエラー<BR>";
			return False;
		}
		//ログに記録
		If(!$this->record($data["id"],"ユーザ削除")){
			$this->err_msg="システムエラー<BR>";
			return FALSE;
		}
		//削除
		If(!$this->del("id='" . $this->escape($data["id"]) . "'")){
			$this->err_msg = "ユーザの削除に失敗しました<BR>";
			return FALSE;
		}
		//セッション情報削除
		If(!$this->log_out($data)){
			return False;
		}
		return TRUE;
	}
	//ログアウト処理
	//引数：アカウント情報
	//戻り値：True Or False
	//注意：リダイレクトはしない
	function log_out($data=array()){
		$this->err_msg = "";
		//ログアウト処理
		If(!$this->chk_input($data,"id")){
			return false;
		}
		//ここはマニュアルどおりに処理・・・
		// セッションの初期化
		// session_name("something")を使用している場合は特にこれを忘れないように!
		session_start();

		// セッション変数を全て解除する
		$_SESSION = array();

		// セッションを切断するにはセッションクッキーも削除する。
		// Note: セッション情報だけでなくセッションを破壊する。
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}

		// 最終的に、セッションを破壊する
		session_destroy();
		//セッションファイルも削除する
		$session_file = session_save_path() . '/sess_' . $session_id;
		if ( is_file( $session_file ) ) {
			unlink( $session_file );
		}
		//ログに記録
		If(!$this->record($data["id"],"ログアウト")){
			$this->err_msg = "システムエラー<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//パスワード変更処理
	//引数：アカウント情報
	//戻り値：True Or False
	//注意：パラメタのチェックは事前に行っておくこと
	function change_password($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//入力チェック
		If(!$this->chk_input($data,"id")){
			return false;
		}
		If(!$this->chk_input($data,"password")){
			return false;
		}
		//存在確認
		if ($this->getcount("id='" . $this->escape($data["id"]) . "'") == 0){
			$this->err_msg = "システムエラー<BR>";
			return False;
		}
		//キーセット
		//パスワードをMD5で変換する
		$tmp["password"] = md5($data["password"]);
		$tmp["id"] = $data["id"];
		//登録日セット
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();
		//登録
		//ログに記録
		If (!$this->record($tmp["id"],"パスワード変更")){
			$this->err_msg = "システムエラー<BR>";
			return FALSE;
		}
		If (!$this->updateby($tmp,'id=' . $this->escape($tmp["id"]))){
			$this->err_msg = "パスワードの変更に失敗しました<BR>";
		}
		return $this->ret($this->err_msg);
	}
	//ログイン処理
	//引数：アカウント情報
	//戻り値：True Or False
	//注意：パラメタのチェックは事前に行っておくこと
	function login($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//簡単ログインではないまたは非対応端末
		If(!$this->chk_input($data,"password")){
			return false;
		}
		If(!$this->chk_input($data,"user_name")){
			return false;
		}
		//ログインチェック
		$tmp=array(
					'user_name' => $data["user_name"],
					'password' => md5($data["password"])
					);
		if ($this->getcount($tmp) == 1){
			//ログインOK
			//ユーザー情報をセッションへ待避
			IF(!$_SESSION["RSS"]["USER"] = $this->findone($tmp)){
				$this->err_msg="システムエラー<BR>";
				return FALSE;
			}
			//ログに記録
			IF (!$this->record($_SESSION["RSS"]["USER"]["id"],"ログイン")){
				$this->err_msg = "システムエラー<BR>";
				return FALSE;
			}
			return TRUE;
		}else{
			$this->err_msg = "IDまたはパスワードが違います。<BR>";
			return FALSE;
		}
	}

	//ログイン処理(携帯)
	//引数：アカウント情報
	//戻り値：True Or False
	//注意：パラメタのチェックは事前に行っておくこと
	function mb_login($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//簡単ログイン
		//入力チェック
		If(!$this->chk_input($data,"mb_key")){
			return false;
		}
		//ログインチェック
		$tmp=array(
					'mb_key' => $data["mb_key"],
					);
		if ($this->getcount($tmp) == 1){
			//ログインOK
			//ユーザー情報をセッションへ待避
			IF(!$_SESSION["RSS"]["USER"] = $this->findone($tmp)){
				$this->err_msg="システムエラー<BR>";
				return FALSE;
			}
			//ログに記録
			If(!$this->record($_SESSION["RSS"]["USER"]["id"],"携帯よりログイン")){
				$this->err_msg = "システムエラー<BR>";
				return FALSE;
			}
			return TRUE;
		}else{
			$this->err_msg = "該当する携帯は登録されていません<BR>";
			return FALSE;
		}
	}
	//メールアドレス追加処理
	//引数：アカウント情報
	//戻り値：True Or False
	//注意：パラメタのチェックは事前に行っておくこと
	function add_email($data=array()){
		$this->err_msg = "";
		$tmp = array();
		//入力チェック
		If(!$this->chk_input($data,"id")){
			return false;
		}
		If(!$this->chk_input($data,"e-mail")){
			return false;
		}
		//存在確認
		if ($this->getcount("id='" . $this->escape($data["id"]) . "'") == 0){
			$this->err_msg = "システムエラー<BR>";
			return False;
		}
		//キーセット
		$tmp["e-mail"] = $data["e-mail"];
		$tmp["id"] = $data["id"];
		//登録日セット
		$tmp["touroku_date"] = $this->get_date();
		$tmp["touroku_time"] = $this->get_time();
		//登録
		//ログに記録
		If (!$this->record($tmp["id"],"メールアドレス登録")){
			$this->err_msg = "システムエラー<BR>";
			return FALSE;
		}
		If (!$this->updateby($tmp,'id=' . $this->escape($tmp["id"]))){
			$this->err_msg = "メールアドレスの登録に失敗しました<BR>";
		}
		return $this->ret($this->err_msg);
	}

}
