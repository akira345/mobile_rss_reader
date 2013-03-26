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
	// ��󥯤�URL
	// @var string
	var $baseUrl		= '';

	// �����꡼ʸ����
	// @var string
	var $queryStr		= 'page';

	// ���ߤΥڡ����ֹ�
	// @var integer
	var $curPage	 	= 1;

	// ñ�̥ꥹ�ȿ�
	// @var integer
	var $perPage	 	= 10;

	// �ꥹ�Ȥι�׿�
	// @var integer
	var $totalRows  	= 0;

	// ����Υ�󥯿�
	// @var integer
	var $numLinks		= 2;

	// ���ޥ꡼��ɽ��
	// @var bool
	var $pageSummary	= TRUE;

	// "�ǽ�" �Υڡ����ؤΥ�󥯥ƥ����ȡ�
	// @var string
	var $firstLink		= '&laquo;';

	// "��" �Υڡ����ؤΥ�󥯥ƥ����ȡ�
	// @var string
	var $prevLink		= '&lsaquo;';

	// "��" �Υڡ����ؤΥ�󥯥ƥ����ȡ�
	// @var string
	var $nextLink		= '&rsaquo;';

	// "�Ǹ�" �Υڡ����ؤΥ�󥯥ƥ����ȡ�
	// @var string
	var $lastLink		= '&raquo;';

	// �ڡ����͡������γ��ϥ�����
	// @var string
	var $fullTagOpen	= '<div class="pagination"><ul>';

	// �ڡ����͡������ν�λ������
	// @var string
	var $fullTagClose	= '</ul></div>';

	// �ڡ�����󥯤γ��ϥ�����
	// @var string
	var $linkTagOpen	= '<li>';

	// �ڡ�����󥯤ν�λ������
	// @var string
	var $linkTagClose	= '</li>';

	// "����" �Υڡ������ֹ�γ��ϥ�����
	// @var string
	var $curTagOpen		= '<span class="curpage">';

	// "����" �Υڡ������ֹ�ν�λ������
	// @var string
	var $curTagClose	= '</span>';


	// ���󥹥ȥ饯��
	// @param	array	$params		��󥯤�CSS�������������
	// @return	void
	function __construct( $params = array() )
	{
		$this->initialize( $params );
	}

	// ���󥹥ȥ饯��
	// @param	array	$params		��󥯤�CSS�������������
	// @return	void
	function Pagination( $params = array() )
	{
		return $this->__construct( $parms );
	}

	// �������
	// @param	array	$params		��󥯤�CSS�������������
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

	// curPage�μ���
	// @return integer
	function getCurPage()
	{
		return $this->curPage;
	}

	// perPage�μ���
	// @return integer
	function getPerPage()
	{
		return $this->perPage;
	}

	// totalRows�μ���
	// @return integer
	function getTotalRows()
	{
		return $this->totalRows;
	}

	// LIMIT SQLʸ�μ���
	// @return string
	function getLimitSql()
	{
		$sql = "LIMIT {$this->getLimit()},{$this->perPage}";
		return $sql;
	}

	// LIMIT���ե��åȤμ���
	// @return integer
	function getLimit()
	{
		$offset = ( $this->curPage - 1 ) * $this->perPage;
		return $offset;
	}

	// ���������󤫤鸽�ߥڡ��������Ǥ�����Ǽ���
	// @param  array	ɽ���оݤ����ǡ���
	// @return array
	function slice( &$data )
	{
		if ( ! is_array( $data ) ) return (array)NULL;
		return array_slice( $data , $this->getLimit() , $this->perPage );
	}

	// �ڡ����͡������HTMLʸ�μ���
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
