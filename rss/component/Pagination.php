<?php
/*
 * 2008/10/12 - v0.10 by T.NISHIHARA
 */

/*
// Credits: Dynamic Drive CSS Library
//  URL: http://www.dynamicdrive.com/style/
<style type="text/css">
.pagination {margin:0;}
.pagination ul {margin:0;padding:0;text-align:left;}
.pagination li {display:inline;list-style-type:none;margin-right:2px;}
.pagination a,
.pagination a:visited {padding:0 5px;color:#2e6ab1;text-decoration:none;border:1px solid #9aafe5;}
.pagination a:hover,
.pagination a:active {color:#000;background-color:#99ffff;border:1px solid #2b66a5;}
.pagination .curpage {padding:0 5px;font-weight:bold;text-decoration:none;color:#fff !important;background-color:#2e6ab1;border:1px solid #2b66a5;}
</style>

	$params = array(
		'baseUrl'		=> '',
		'queryStr'		=> 'page',
		'curPage'		=> 1,
		'perPage'		=> 10,
		'totalRows'  	=> 0,
		'numLinks'		=> 2,
		'pageSummary'	=> TRUE,
		'firstLink'		=> '&laquo;',
		'prevLink'		=> '&lsaquo;',
		'nextLink'		=> '&rsaquo;',
		'lastLink'		=> '&raquo;',
		'fullTagOpen'	=> '<div class="pagination"><ul>',
		'fullTagClose'	=> '</ul></div>',
		'linkTagOpen'	=> '<li>',
		'linkTagClose'	=> '</li>',
		'curTagOpen'	=> '<span class="curpage">',
		'curTagClose'	=> '</span>',
	);
	$pagination = new Pagination( $params );
	echo $pagination->create_links();

*/

class Pagination
{
	// リンクのURL
	// @var string
	var $baseUrl		= '';

	// クエリー文字列
	// @var string
	var $queryStr		= 'page';

	// 現在のページ番号
	// @var integer
	var $curPage	 	= 1;

	// 単位リスト数
	// @var integer
	var $perPage	 	= 10;

	// リストの合計数
	// @var integer
	var $totalRows  	= 0;

	// 前後のリンク数
	// @var integer
	var $numLinks		= 2;

	// サマリーの表示
	// @var bool
	var $pageSummary	= TRUE;

	// "最初" のページへのリンクテキスト。
	// @var string
	var $firstLink		= '&laquo;';

	// "前" のページへのリンクテキスト。
	// @var string
	var $prevLink		= '&lsaquo;';

	// "次" のページへのリンクテキスト。
	// @var string
	var $nextLink		= '&rsaquo;';

	// "最後" のページへのリンクテキスト。
	// @var string
	var $lastLink		= '&raquo;';

	// ページネーションの開始タグ。
	// @var string
	var $fullTagOpen	= '<div class="pagination"><ul>';

	// ページネーションの終了タグ。
	// @var string
	var $fullTagClose	= '</ul></div>';

	// ページリンクの開始タグ。
	// @var string
	var $linkTagOpen	= '<li>';

	// ページリンクの終了タグ。
	// @var string
	var $linkTagClose	= '</li>';

	// "現在" のページの番号の開始タグ。
	// @var string
	var $curTagOpen		= '<span class="curpage">';

	// "現在" のページの番号の終了タグ。
	// @var string
	var $curTagClose	= '</span>';


	// コンストラクタ
	// @param	array	$params		リンクやCSSスタイルの設定
	// @return	void
	function __construct( $params = array() )
	{
		$this->initialize( $params );
	}

	// コンストラクタ
	// @param	array	$params		リンクやCSSスタイルの設定
	// @return	void
	function Pagination( $params = array() )
	{
		return $this->__construct( $parms );
	}

	// 初期設定
	// @param	array	$params		リンクやCSSスタイルの設定
	// @return	void
	function initialize( $params = array() )
	{
		if ( count( $params ) > 0 ) {
			foreach ( $params as $key => $val ) {
				if ( isset( $this->$key ) ) {
					$this->$key = $val;
				}
			}
		}
	}

	// curPageの取得
	// @return integer
	function getCurPage()
	{
		return $this->curPage;
	}

	// perPageの取得
	// @return integer
	function getPerPage()
	{
		return $this->perPage;
	}

	// totalRowsの取得
	// @return integer
	function getTotalRows()
	{
		return $this->totalRows;
	}

	// LIMIT SQL文の取得
	// @return string
	function getLimitSql()
	{
		$sql = "LIMIT {$this->getLimit()},{$this->perPage}";
		return $sql;
	}

	// LIMITオフセットの取得
	// @return integer
	function getLimit()
	{
		$offset = ( $this->curPage - 1 ) * $this->perPage;
		return $offset;
	}

	// 引数の配列から現在ページの要素を配列で取得
	// @param  array	表示対象の全データ
	// @return array
	function slice( &$data )
	{
		if ( ! is_array( $data ) ) return (array)NULL;
		return array_slice( $data , $this->getLimit() , $this->perPage );
	}

	// ページネーションHTML文の取得
	// @return 	string
	function create_links()
	{
		$totalItems		= $this->totalRows;
		$perPage		= $this->perPage;
		$currentPage	= $this->curPage;
		$link			= "{$this->baseUrl}?{$this->queryStr}=%s";

		if ( $totalItems == 0 || $perPage == 0 ) {
			return '';
		}

		$totalPages = ceil( $totalItems / $perPage );

		if ( $totalPages == 1 ) {
			return '';
		}

		$output = '';
		if ( $this->pageSummary ) {
			$output .= "{$this->linkTagOpen}Page&nbsp;({$currentPage}/{$totalPages}){$this->linkTagClose}";
			$output .= "{$this->linkTagOpen}&nbsp;{$this->linkTagClose}";
		}

		$loopStart = 1;
		$loopEnd = $totalPages;

		if ( $totalPages > ( $this->numLinks * 2 + 1 ) ) {
			if ( $currentPage <= ( $this->numLinks + 1 ) ) {
				$loopStart = 1;
				$loopEnd = $this->numLinks * 2 + 1;
			} else if ( $currentPage >= ( $totalPages - $this->numLinks ) ) {
				$loopStart = $totalPages - $this->numLinks * 2;
				$loopEnd = $totalPages;
			} else {
				$loopStart = $currentPage - $this->numLinks;
				$loopEnd = $currentPage + $this->numLinks;
			}
		}

		if ( $loopStart != 1 ) {
			$output .= sprintf( "{$this->linkTagOpen}<a href=\"{$link}\">{$this->firstLink}</a>{$this->linkTagClose}", 1 );
		}

		if ( $currentPage > 1 ) {
			$output .= sprintf( "{$this->linkTagOpen}<a href=\"{$link}\">{$this->prevLink}</a>{$this->linkTagClose}", $currentPage - 1 );
		}

		for ( $i = $loopStart; $i <= $loopEnd; $i++ ) {
			if ( $i == $currentPage ) {
				$output .= $this->linkTagOpen . $this->curTagOpen . $i . $this->curTagClose . $this->linkTagClose;
			} else {
				$output .= sprintf( "{$this->linkTagOpen}<a href=\"{$link}\">{$i}</a>{$this->linkTagClose}", $i );
			}
		}

		if ( $currentPage < $totalPages ) {
			$output .= sprintf( "{$this->linkTagOpen}<a href=\"{$link}\">{$this->nextLink}</a>{$this->linkTagClose}", $currentPage + 1 );
		}

		if ( $loopEnd != $totalPages ){
			$output .= sprintf( "{$this->linkTagOpen}<a href=\"{$link}\">{$this->lastLink}</a>{$this->linkTagClose}", $totalPages );
		}

		// Add the wrapper HTML if exists
		$output = $this->fullTagOpen . $output . $this->fullTagClose . "\n";

		return $output;
	}
}
