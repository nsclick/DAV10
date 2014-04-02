<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}


require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'access.php' );
require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'db.constants.php' );





/**
 * This class contain database query selects that return many rows
 *
 * All methods are static
 * 
 * js_JSDbSMR JoomlaStats Database Select Many Row 
 */
class js_JSDbSMR extends js_JSDatabaseAccess
{
	function __construct() {
		parent::__construct();
	}
	
	
	function getPagesImpressionsArr_MySql40( $limitstart, $limit, $day, $month, $year, &$arr_obj_result ) {
		//DO NOT CHANGE ANYTHING!!! - this query is 5x faster in compare if we use JOIN syntax! (MySql fault)
		$query = ""
		. " SELECT"
		. "   f.page_id          AS page_id,"
		. "   f.page             AS page_url,"
		. "   f.page_title       AS page_title,"
		. "   s.page_impressions AS page_impressions"
		. " FROM ("
		. "   SELECT p.page, p.page_id, p.page_title"
		. "   FROM #__jstats_pages p"
		. " ) AS f, ("
		. "   SELECT r.page_id, count(*) AS page_impressions"
		. "   FROM #__jstats_page_request r"
		. "   WHERE r.day LIKE '$day'"
		. "   AND r.month LIKE '$month'"
		. "   AND r.year LIKE '$year'"
		. "   GROUP BY r.page_id"
		. "   ORDER BY page_impressions DESC"
		. "   LIMIT $limitstart, $limit"
		. " ) AS s"
		. " WHERE f.page_id = s.page_id"
		. " ORDER BY page_impressions DESC";
		$this->db->setQuery( $query );//$pagination->limitstart, $pagination->limit" already are inside query
		$arr_obj_result = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
			
		return true;
	}
	
	
	/** See *_MySql40 for details
	 *
	 *  @deprecated It is 5 times slower than *_MySql40 in 'MySql 5.0.51' and 'JS DB 20 [MB]'
	 */
	function getPagesImpressionsArr_MySql30( $limitstart, $limit, $day, $month, $year, &$arr_obj_result ) {
		
		$query = ""
		. " SELECT"
		. "   p.page_id    AS page_id,"
		. "   p.page       AS page_url,"
		. "   p.page_title AS page_title,"
		. "   COUNT(*)     AS page_impressions"
		. " FROM"
		. "   #__jstats_pages p"
		. "   LEFT JOIN #__jstats_page_request r ON (p.page_id=r.page_id)"
		. " WHERE"
		. "   r.day LIKE '$day'"
		. "   AND r.month LIKE '$month'"
		. "   AND r.year LIKE '$year'"
		. " GROUP BY page_id"
		. " ORDER BY page_impressions DESC"//with 'ORDER BY page_impressions DESC' execute takes almost 5s regardles of use 'LIMIT' or not. //without 'ORDER BY' and with 'LIMIT 0, 30' execute takes 0.01s //tests done on 20.0MB JS database
		. " LIMIT $limitstart, $limit";
		$this->db->setQuery( $query );//$pagination->limitstart, $pagination->limit" already are inside query
		$arr_obj_result = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
			
		return true;
	}
	
	function getPagesImpressionsArrWithSummarized_MySql40( $limitstart, $limit, $day, $month, $year, &$arr_obj_result ) {
		//// not working for JS databases about 20MB and d-m-y = all-all-2008 (takes over 30s because MySql do not optimize this query correctly) 
		//$query = ""
		//. "\n SELECT p.page_id, p.page AS page_url, p.page_title AS page_title, COUNT(*)+IFNULL(s.page_impressions_summarized,0) AS page_impressions"//should be page_impressions_with_summarized
		//. "\n FROM jos_jstats_pages p"
		//. "\n LEFT JOIN jos_jstats_page_request r ON (p.page_id=r.page_id)"
		//. "\n LEFT JOIN "
		//. "\n   ("
		//. "\n   SELECT s.page_id, sum(count) AS page_impressions_summarized"
		//. "\n   FROM jos_jstats_page_request_c s"
		//. "\n   WHERE s.day LIKE '$this->d'"
		//. "\n   AND s.month LIKE '$this->m'"
		//. "\n   AND s.year LIKE '$this->y'"
		//. "\n   GROUP BY page_id"
		//. "\n   ) AS s ON (p.page_id=s.page_id)"
		//. "\n WHERE r.day LIKE '$this->d'"
		//. "\n AND r.month LIKE '$this->m'"
		//. "\n AND r.year LIKE '$this->y'"
		//. "\n GROUP BY p.page_id"
		//. "\n ORDER BY page_impressions DESC";//should be page_impressions_with_summarized
		////. "\n LIMIT 0 , 30";
		
		/** I know that below query look strange, but it is the fastet (I tested over 6 queries, each for 3 types of data (small, medium, large) and decide to choose the fastest) */
		$query = ""
		. " SELECT"
		. "   p.page_id                           AS page_id,"
		. "   p.page_url                          AS page_url,"
		. "   p.page_title                        AS page_title,"
		. "   s.page_impressions_with_summarized  AS page_impressions_with_summarized,"
		. "   s.page_impressions_only_summarized  AS page_impressions_only_summarized,"
		. "   s.page_impressions_with_summarized  AS page_impressions"	//duplicated to compatibility with 'getPagesImpressionsArr_MySql*' function
		. " FROM ("
		. "   SELECT a.page_id, a.page AS page_url, a.page_title AS page_title"
		. "   FROM #__jstats_pages a"
		. " ) AS p, ("
		. "   SELECT u.page_id,"
		. "     SUM(u.page_impressions_without_summarized)+SUM(u.page_impressions_only_summarized) AS page_impressions_with_summarized,"
		. "     SUM(u.page_impressions_only_summarized) AS page_impressions_only_summarized"
		. "   FROM ("
		. "     ("
		. "       SELECT r.page_id AS page_id, COUNT(*) AS page_impressions_without_summarized, 0 AS page_impressions_only_summarized"
		. "       FROM #__jstats_page_request r"
		. "       WHERE r.day LIKE '$day'"
		. "       AND r.month LIKE '$month'"
		. "       AND r.year LIKE '$year'"
		. "       GROUP BY r.page_id"
		. "     ) UNION ("
		. "       SELECT c.page_id AS page_id, 0 AS page_impressions_without_summarized, SUM(count) AS page_impressions_only_summarized"
		. "       FROM #__jstats_page_request_c c"
		. "       WHERE c.day LIKE '$day'"
		. "       AND c.month LIKE '$month'"
		. "       AND c.year LIKE '$year'"
		. "       GROUP BY c.page_id"
		. "     )"
		. "   ) AS u"
		. "   GROUP BY u.page_id"
		. "   ORDER BY page_impressions_with_summarized DESC"
		. "   LIMIT $limitstart, $limit"
		. " ) AS s"
		. " WHERE p.page_id=s.page_id"
		. " ORDER BY page_impressions_with_summarized DESC";//should be page_impressions_with_summarized
		//. " LIMIT 0 , 30";//limit is applayed earlier
		$this->db->setQuery( $query );//, $pagination->limitstart, $pagination->limit );//limit is applayed earlier
		$arr_obj_result = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
			
		return true;
	}

	function getPagesImpressionsArrWithSummarized_MySql30( $limitstart, $limit, $day, $month, $year, &$arr_obj_result ) {
		$sums_result = null;
		return $this->_private_getPagesImpressionsArrWithSummarized_MySql30( $limitstart, $limit, $day, $month, $year, $arr_obj_result, $sums_result);
	}
	
	function selectNotIdentifiedVisitorsArr( $limitstart, $limit, $FilterDate, $include_summarized, &$arr_obj_result ) {

		$query = ''
		. ' SELECT'
		. '   a.tld,'
		. '   b.fullname,'
		. '   a.useragent,'
		. '   c.time'
		. ' FROM'
		. '   #__jstats_ipaddresses AS a,'
		. '   #__jstats_topleveldomains AS b,'
		. '   #__jstats_visits AS c'
		. ' WHERE'
		. '   a.tld = b.tld'
		. '   AND c.ip_id = a.id'
		. '   AND a.type = 0'
		. '   AND '.$FilterDate->getSqlConditionString( 'c' );
		if ($include_summarized == false) {
			require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );
			//if show only current data (without purged)
			$buid = 0;
			$JSDbSOV = new js_JSDbSOV();
			$JSDbSOV->getBuid($buid);
            $query .= ' AND a.id = c.ip_id AND c.id >= '.$buid;
		}
		$query .= ' ORDER BY c.time DESC';
		$this->db->setQuery( $query, $limitstart, $limit );
		$arr_obj_result = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
			
		return true;
	}
	
	
	/** This is method from v2.2.0. It is used only on MySql 3.x (deprecated) and if option 'Show with summarized data' is turn on
	 *
	 *  New method is implemented in getPagesImpressionsArrWithSummarized_MySql40 function.
	 */
	function _private_getPagesImpressionsArrWithSummarized_MySql30( $limitstart, $limit, $day, $month, $year, &$arr_obj_result, &$sums_result ) {
		
		$arr_obj_result 			= array();
		$rettable 				= array();
		$total_sum				= 0;
		$totalrowb				= 0;
		$totalmax				= 0;
		$nbr_visited_pages_only_summarized = 0;
		$nbr_visited_pages_without_summarized = 0;
		$sum_all_pages_impressions_without_summarized = 0;
		
		$query = "SELECT page, page_id, page_title"
		. "\n FROM #__jstats_pages";
		$this->db->setQuery($query);
		$rows = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;

		foreach( $rows AS $row )
		{
			//working in different way that is discribed in PHP manual?
			//if (!ini_get('safe_mode'))
				//set_time_limit(5);
			set_time_limit(0);//in MySqlAdmin they use such statement
				
			$query = "SELECT count(*) numbers"
			. "\n FROM #__jstats_page_request AS a"
			. "\n WHERE a.page_id = $row->page_id"
			. "\n AND a.day LIKE '$day'"
			. "\n AND a.month LIKE '$month'"
			. "\n AND a.year LIKE '$year'";
            $this->db->setQuery($query);
			$rowa = $this->db->LoadResult();
			if ($this->db->getErrorNum() > 0)
				return false;

			$sum_all_pages_impressions_without_summarized += $rowa;
			if ($rowa > 0)
				$nbr_visited_pages_without_summarized++;

			// get also archived/purged data
		    $query = "SELECT sum(count)"
            . "\n FROM #__jstats_page_request_c AS a"
            . "\n WHERE a.day LIKE '$day'"
            . "\n AND a.month LIKE '$month'"
            . "\n AND a.year LIKE '$year'"
            . "\n AND a.page_id = $row->page_id";
            $this->db->setQuery($query);
            $rowb = $this->db->LoadResult();
			if ($this->db->getErrorNum() > 0)
				return false;


			$totalrowb += $rowb;
			if ($rowb > 0)
				$nbr_visited_pages_only_summarized++;

			if (($rowa + $rowb) > 0)
				$rettable[$row->page .'#/#'. $row->page_title .'#/#'. $rowb] = ($rowa + $rowb);				
		}
		
		if ( count($rettable) > 0 )
		{
			arsort( $rettable );
			reset( $rettable );

			while ( list( $key, $val ) = each( $rettable ) )
			{
				$total_sum += $val;
				if( $val > $totalmax )
					$totalmax = $val;					
			}
			reset( $rettable );

			$end	= $limitstart + $limit;

			for ( $ii = 0; $ii < count( $rettable ); $ii++ )
			{
				list( $key, $val ) = each( $rettable );
				$explodedkey = explode( '#/#', $key );

				if ( $ii >= $limitstart && $ii < $end )
				{
					$row_result = new stdClass();
					$row_result->page_id = $row->page_id;
					$row_result->page_url = $explodedkey[0];
					$row_result->page_title = $explodedkey[1];
					$row_result->page_impressions_with_summarized = $val;
					$row_result->page_impressions_only_summarized = $explodedkey[2];
					$row_result->page_impressions = $val;
					
					$arr_obj_result[] = $row_result;
				}
			}
		}
		
		//compatibility with getPagesImpressionsSums_MySql40()
		$sums_result = null;
		$sums_result = new stdClass();
		$sums_result->nbr_visited_pages = count($rettable);
		$sums_result->sum_all_pages_impressions = $total_sum;
		$sums_result->max_page_impressions = $totalmax;
		
		//compatibility with getPagesImpressionsSumsWithSummarized_MySql40()
		$sums_result->nbr_visited_pages_with_summarized = $sums_result->nbr_visited_pages;
		$sums_result->sum_all_pages_impressions_with_summarized = $sums_result->sum_all_pages_impressions;
		$sums_result->nbr_visited_pages_without_summarized = $nbr_visited_pages_without_summarized;
		$sums_result->nbr_visited_pages_only_summarized = $nbr_visited_pages_only_summarized;
		$sums_result->sum_all_pages_impressions_without_summarized = $sum_all_pages_impressions_without_summarized;
		$sums_result->sum_all_pages_impressions_only_summarized = $totalrowb;
		$sums_result->max_page_impressions_with_summarized = $sums_result->max_page_impressions;
		
		return true;
	}
	
	/** Database should be organized in different way - than this function will be much simpler! */
	function getOperatingSystemVisistsArr( $date_from, $date_to, $include_summarized, &$arr_obj_result ) {
		$query = ""
		. " SELECT"
		. "   a.system AS os_name,"
		. "   count(*) AS os_visits"
		. " FROM"
		. "   #__jstats_ipaddresses AS a, "
		. "   #__jstats_visits AS c"
		. " WHERE"
		. '   a.id = c.ip_id'
		. '   AND a.type = '._JS_DB_IPADD__TYPE_REGULAR_VISITOR;
		
		/* mic: show only actual data (without already archived/purged) */
		if( $include_summarized == false ) {
			require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );
			$buid = 0;
			$JSDbSOV = new js_JSDbSOV();
			$JSDbSOV->getBuid($buid);
			$query .= ' AND (a.id = c.ip_id AND c.id >= '.$buid.')';
		}
		
		$query .= ''
		. ' AND '.$this->getConditionStringFromDates('c', $date_from, $date_to)
		. ' GROUP BY a.system'
		. ' ORDER BY os_visits DESC, a.system ASC'
		;
		$this->db->setQuery( $query );
		$db_result = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;

		{//this part simulate JOIN (join could not be performed due to wrong database structure)
			$ava_sys = array();
			$res = $this->getAvailableOperatingSystemArr( $ava_sys );
			if ($res == false)
				return false;
				
			$os_name_to_image_arr = array();
			$os_name_to_ostype_id = array();
			$os_name_to_os_id = array();
			foreach($ava_sys as $rowt) {
				$os_name_to_image_arr[$rowt->os_name] = $rowt->os_img;
				$os_name_to_ostype_id[$rowt->os_name] = $rowt->ostype_id;
				$os_name_to_os_id[$rowt->os_name] = $rowt->os_id;
			}
			$__jstats_ostype = unserialize(_JS_DB_TABLE__OSTYPE);//whole table #__jstats_ostype (with entries)
				
			$arr_obj_result = array();
			foreach($db_result as $obj) {
				$obj->os_img = (isset($os_name_to_image_arr[$obj->os_name])) ? $os_name_to_image_arr[$obj->os_name] : 'unknown';
				$ostype_id = (isset($os_name_to_ostype_id[$obj->os_name])) ? $os_name_to_ostype_id[$obj->os_name] : _JS_DB_OSTYP__ID_UNKNOWN;
				$obj->ostype_img = $__jstats_ostype[$ostype_id]['ostype_img'];
				//$os_id = (isset($os_name_to_os_id[$obj->os_name])) ? $os_name_to_os_id[$obj->os_name] : 0;
				$obj->ostype_name = $__jstats_ostype[$ostype_id]['ostype_name'];
				$arr_obj_result[] = $obj;
			}
		}			
				
		return true;
	}
	
	function getAvailableOperatingSystemArr( &$arr_obj_result ) {
		$query = ''
		. ' SELECT'
		. '   o.sys_id        AS os_id,'
		. '   o.sys_string    AS os_key,'
		. '   o.sys_fullname  AS os_name,'
		. '   o.sys_type      AS ostype_id,'
		. '   o.sys_img       AS os_img'
		. ' FROM'
		. '   #__jstats_systems o'
		;
		$this->db->setQuery( $query );
		$arr_obj_result = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
			
		return true;
	}
	
	/** the same as getAvailableSystemArr(), but sorted and distinct */
	function getAvailableOperatingSystemArrForHuman( &$arr_obj_result ) {
		$query = ''
		. ' SELECT DISTINCT'
		. '   o.sys_fullname  AS os_name,'
		. '   o.sys_type      AS ostype_id,'
		. '   o.sys_img       AS os_img'
		. ' FROM'
		. '   #__jstats_systems o'
		. ' WHERE'
		. '   o.sys_id > 0'
		. ' ORDER BY'
		. '   os_name ASC'
		;
		$this->db->setQuery( $query );
		$arr_obj_result = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;
			
		return true;
	}
}

