<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}


require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'access.php' );
require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'db.constants.php' );





/**
 * This class contain database query selects that return one row
 *
 * All methods are static
 * 
 * js_JSDbSOR JoomlaStats Database Select One Row 
 */
class js_JSDbSOR extends js_JSDatabaseAccess
{
	function __construct() {
		parent::__construct();
	}
	
	function getPagesImpressionsSums_MySql40( $day, $month, $year, &$obj_result ) {
		$query = ""
		. " SELECT"
		. "   COUNT(s.page_id)        AS nbr_visited_pages,"			//return number of visited pages (in time period)
		. "   SUM(s.page_impressions) AS sum_all_pages_impressions,"	//return sum of page impressions of all visited pages (in time period)
		. "   MAX(s.page_impressions) AS max_page_impressions"			//always there is page with the higest visits number - that visit number is returned here
		. " FROM ("
		. "   SELECT"
		. "     r.page_id, COUNT(*) AS page_impressions"
		. "   FROM #__jstats_page_request r"
		. "   WHERE"
		. "     r.day LIKE '$day'"
		. "     AND r.month LIKE '$month'"
		. "     AND r.year LIKE '$year'"
		. "   GROUP BY r.page_id"
		. " ) AS s";
		$this->db->setQuery( $query );
		$tot = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
		
		$obj_result = $tot[0];
		
		if ($obj_result->nbr_visited_pages == 0) { //set missing data
			$obj_result->sum_all_pages_impressions = 0;
			$obj_result->max_page_impressions = 0;
		}
			
		return true;
	}
	
	function getPagesImpressionsSums_MySql30( $day, $month, $year, &$obj_result ) {
		$query = ""
		. " SELECT"
		. "   COUNT(*)   AS page_impressions,"
		. "   r.page_id  AS page_id"//!!
		. " FROM"
		. "   #__jstats_page_request r"
		. " WHERE"
		. "   r.day LIKE '$day'"
		. "   AND r.month LIKE '$month'"
		. "   AND r.year LIKE '$year'"
		. " GROUP BY"
		. "   r.page_id";
		$this->db->setQuery( $query );
		$tot = $this->db->loadResultArray();//!!    //loadRowList();//loadResultArray();//loadAssocList();
		if ($this->db->getErrorNum() > 0)
			return false;
		
		$sum = 0;
		foreach ($tot as $t)
			$sum = $sum + $t;
		
		$obj_result = null;
		$obj_result = new stdClass();
		$obj_result->nbr_visited_pages = count($tot);
		$obj_result->sum_all_pages_impressions = $sum;
		$obj_result->max_page_impressions = (count($tot)>0) ? max($tot) : 0;//prevent warning
		
		unset($tot);//free large part of memory
		return true;
	}
	
	
	/**
	 *
	 * NOTICE: "only_summarized + without_summarized != with_summarized" !!! some pages could be in both places in the same time!
	 */
	function getPagesImpressionsSumsWithSummarized_MySql40( $day, $month, $year, &$obj_result ) {
		$query = ""
		. " SELECT"
		. "   COUNT(s.page_id)                            AS nbr_visited_pages_with_summarized,"
		. "   SUM(s.page_impressions_with_summarized)     AS sum_all_pages_impressions_with_summarized,"
		. "   SUM(s.nbr_visited_pages_without_summarized) AS nbr_visited_pages_without_summarized,"
		. "   SUM(s.nbr_visited_pages_only_summarized)    AS nbr_visited_pages_only_summarized,"
		. "   SUM(s.page_impressions_without_summarized)  AS sum_all_pages_impressions_without_summarized,"
		. "   SUM(s.page_impressions_only_summarized)     AS sum_all_pages_impressions_only_summarized,"
		. "   MAX(s.page_impressions_with_summarized)     AS max_page_impressions_with_summarized"
		. " FROM ("
		. "     SELECT"
		. "       u.page_id,"
		. "       SUM(IFNULL(u.page_impressions_without_summarized,0))+SUM(IFNULL(u.page_impressions_only_summarized,0)) AS page_impressions_with_summarized,"
		. "       SUM(IFNULL(u.page_impressions_without_summarized,0))  AS page_impressions_without_summarized, "
		. "       SUM(IFNULL(u.page_impressions_only_summarized,0))     AS page_impressions_only_summarized, "
		. "       COUNT(u.page_impressions_without_summarized)          AS nbr_visited_pages_without_summarized,"
		. "       COUNT(u.page_impressions_only_summarized)             AS nbr_visited_pages_only_summarized"
		. "     FROM ("
		. "       ("
		. "         SELECT r.page_id AS page_id, COUNT(*) AS page_impressions_without_summarized, NULL AS page_impressions_only_summarized"
		. "         FROM #__jstats_page_request r"
		. "         WHERE r.day LIKE '$day'"
		. "         AND r.month LIKE '$month'"
		. "         AND r.year LIKE '$year'"
		. "         GROUP BY r.page_id"
		. "       ) UNION ("
		. "         SELECT c.page_id AS page_id, NULL AS page_impressions_without_summarized, SUM(count) AS page_impressions_only_summarized"
		. "         FROM #__jstats_page_request_c c"
		. "         WHERE c.day LIKE '$day'"
		. "         AND c.month LIKE '$month'"
		. "         AND c.year LIKE '$year'"
		. "         GROUP BY c.page_id"
		. "       )"
		. "     ) AS u"
		. "     GROUP BY u.page_id"
		. " ) AS s";
		$this->db->setQuery( $query );
		$tot = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
			
		$obj_result = $tot[0];
		
		//compatibility with getPagesImpressionsSums_MySql40()
		$obj_result->nbr_visited_pages = $obj_result->nbr_visited_pages_with_summarized;
		$obj_result->sum_all_pages_impressions = $obj_result->sum_all_pages_impressions_with_summarized;
		$obj_result->max_page_impressions = $obj_result->max_page_impressions_with_summarized;
		
		return true;
	}

	/**
	 *
	 * NOTICE: "only_summarized + without_summarized != with_summarized" !!! some pages could be in both places in the same time!
	 */
	function getPagesImpressionsSumsWithSummarized_MySql30( $day, $month, $year, &$obj_result ) {
		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR.'select.many.rows.php' );
		$JSDbSMR = new js_JSDbSMR();
		
		$arr_result = null;
		$limitstart = 0; //value does not matter
		$limit = 30; //value does not matter
		return $JSDbSMR->_private_getPagesImpressionsArrWithSummarized_MySql30($limitstart, $limit, $day, $month, $year, $arr_result, $obj_result);
	}
	
}

