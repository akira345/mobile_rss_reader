<?php
	require_once( "./config/config.php" );
	require_once( "../cheetan/cheetan.php" );
//ユーザ認証をかける
function is_secure(&$c){
	return true;
}
function action( &$c )
{
	//コントローラ
	$data = array();
	//ユーザＩＤセット
	$id = $_SESSION["RSS"]["USER"]["id"];
	//履歴参照
	$data = array(
					'id' => $id
				);
	If ($data = $c->login_his->find($data, "touroku_date DESC,touroku_time DESC" )){
		//データあり
		//ページング処理
		if (count($_GET['page'])){
			$now_page = $c->s->gett("page");
		}else{
			$now_page = 1;
		}
		//全データ件数取得
		$all_count = count($data);

		//表示ページ数
		$page = 30;	//たちまちこれくらい

		//ページング設定
		$option = array(
			'baseUrl'	=> 'view_his.php',	// リンクのURL
			'queryStr'	=> htmlspecialchars( SID,ENT_QUOTES ) . '&page',			// クエリー文字列
			'curPage'	=> $now_page,		// 現在のページ番号
			'perPage'	=> $page,			// 1画面当たりのリスト数
			'totalRows'  	=> $all_count,	// リストの合計数
			'numLinks'	=> 2,				// 前後のリンク数
			'pageSummary'	=> TRUE,		// サマリーの表示
			'firstLink'	=> '≪',			// "最初" のページへのリンク文字列
			'prevLink'	=> '＜',			// "前" のページへのリンク文字列
			'nextLink'	=> '＞',			// "次" のページへのリンク文字列
			'lastLink'	=> '≫',			// "最後" のページへのリンク文字列
			'fullTagOpen'	=> '<ul>',		// ページネーションの開始タグ
			'fullTagClose'	=> '</ul>',		// ページネーションの終了タグ
			'linkTagOpen'	=> '',			// ページリンクの開始タグ
			'linkTagClose'	=> '',			// ページリンクの終了タグ
			'curTagOpen'	=> '<B>',		// "現在" のページの番号の開始タグ
			'curTagClose'	=> '</B>',		// "現在" のページの番号の終了タグ
		);
		//ページング初期化
		$c->pagination->initialize( $option );
		//履歴データをページごとに分割する
		$data = $c->pagination->slice( $data);

		//ページング表示
		$c->set("pager",$c->pagination->create_links(),'TRUE');
		//データ
		$c->set("datas",$data,'FALSE');//リンクは許可しない
	}else{
		//データなし
	}
	//テンプレートファイル指定
	$c->SetViewFile( "./tmplate/view_his.html" );
}
?>
