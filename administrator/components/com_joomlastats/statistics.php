<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

require_once( dirname( __FILE__ ) .DS. 'base.classes.php' );
require_once( dirname( __FILE__ ) .DS. 'statistics.common.php' );
require_once( dirname( __FILE__ ) .DS. 'statistics.html.php' );


/**
 *  This class generate statistics and show them in joomla back end (administrator panel)
 *
 *  NOTICE: methods from class JoomlaStats_Engine will be moved here
 *
 *  NOTICE: This class should contain only set of static, argument less functions that are called by task/action
 */
class js_JSStatistics
{
	
	/**
	 * this function return HTML table with 'Page Hits'
	 * (case r06)
	 *
	 * old function name 'getPageHits();'
	 *
	 * @param $JSConf - only for performance
	 * @return html page
	 */
	function viewPageHits($JSConf = null) {
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.many.rows.php' );
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'api' .DIRECTORY_SEPARATOR. 'general.php' );
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'filters.php' );
		
		if ($JSConf == null)
			$JSConf = new js_JSConf();
			
		global $mainframe;
		global $option;
		
		
		// ###  Filters
		$TimePeriod = new js_JSFilterTimePeriodDeprecated();
		$TimePeriod->SetDMY2Now( $mainframe->getCfg( 'offset' ) );
		$TimePeriod->readDateFromRequest( $mainframe->getCfg( 'offset' ), $JSConf->startdayormonth );
		$day = 1;
		$month = 1;
		$year = 1;
		$TimePeriod->getOldFormat( $day, $month, $year );
		
		$FilterSearch = null;
		$FilterDomain = null;
		$vid = '';
		$moreinfo = '';
		
		$limit	= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mainframe->getCfg( 'list_limit' )));
        $limitstart	= intval( $mainframe->getUserStateFromRequest( "viewlimitstart", 'limitstart', 0 ) );

        

		// ###  Content
		$nbr_visited_pages 			= 0;
		$sum_all_pages_impressions	= 0;
		$max_page_impressions		= 0;
		$result_arr					= array();
		$summarized_info 			= array('count' => '', 'pages' => '');
		

		$include_summarized = $JSConf->include_summarized;
		
		$sums = null;
		$JSApiGlobal = new js_JSApiGeneral();
		$JSApiGlobal->getPagesImpressionsSums($day, $month, $year, $include_summarized, $sums );
		
		$nbr_visited_pages = $sums->nbr_visited_pages;
		$sum_all_pages_impressions = $sums->sum_all_pages_impressions;
		$max_page_impressions = $sums->max_page_impressions;

				
		$total = $nbr_visited_pages;
		$pagination = null;
		if( defined( '_JEXEC' ) ) {
			jimport( 'joomla.html.pagination' );
			$pagination = new JPagination( $total, $limitstart, $limit );
		} else {
			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			$pagination = new mosPageNav( $total, $limitstart, $limit );
		}
		
		$JSApiGlobal->getPagesImpressionsArr($pagination->limitstart, $pagination->limit, $day, $month, $year, $include_summarized, $result_arr );
		
		if ($include_summarized) {
			//additional processing for page with summarized data
			$summarized_info['count'] = $sums->sum_all_pages_impressions_only_summarized;
			$summarized_info['pages'] = $sums->nbr_visited_pages_only_summarized;
		}


		// ###  Template
		$JSStatisticsCommon = new js_JSStatisticsCommon();
        $JSStatisticsTpl = new js_JSStatisticsTpl();
		$result_html  = '';
		$result_html .= $JSStatisticsCommon->getJSStatisticsHeaderHtmlCode($JSConf, $FilterSearch, $TimePeriod, $vid, $moreinfo, $FilterDomain);
        $result_html .= $JSStatisticsTpl->viewPageHitsPageTpl( $nbr_visited_pages, $sum_all_pages_impressions, $max_page_impressions, $result_arr, $summarized_info, $pagination );
        $result_html .= $JSStatisticsCommon->getJSStatisticsFooterHtmlCode();
        
        return $result_html;
	}
	
	
	/**
	 *  This function return HTML table with 'Operating Systems' (show all operating systems)
	 *  (case r07)
	 *
	 *  old function name 'getSystems();'
	 *
	 *  There is no pagination - max number is less than 40
	 *
	 *  @param $JSConf - only for performance
	 *  @return html page
	 */
	function viewSystems($JSConf = null) {
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'api' .DIRECTORY_SEPARATOR. 'general.php' );
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'filters.php' );
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'db.constants.php' );
		
		if ($JSConf == null)
			$JSConf = new js_JSConf();
			
		global $mainframe;
		global $option;
		
		$result = '';
				
		//global $mosConfig_offset;
		$TimePeriod = new js_JSFilterTimePeriodDeprecated();
		$TimePeriod->SetDMY2Now( $mainframe->getCfg( 'offset' ) );
		$TimePeriod->readDateFromRequest( $mainframe->getCfg( 'offset' ), $JSConf->startdayormonth );
		
		$JSStatisticsCommon = new js_JSStatisticsCommon();
		$FilterSearch = null;
		$FilterDomain = null;
		$vid = '';
		$moreinfo = '';
		$result .= $JSStatisticsCommon->getJSStatisticsHeaderHtmlCode($JSConf, $FilterSearch, $TimePeriod, $vid, $moreinfo, $FilterDomain);

		$date_from = '';
		$date_to = '';
		$TimePeriod->getTimePeriodsDates( $date_from, $date_to );

		$include_summarized = $JSConf->include_summarized;
		
		$result_arr = array();
		$JSApiGlobal = new js_JSApiGeneral();
		$JSApiGlobal->getOperatingSystemVisistsArr( $date_from, $date_to, $include_summarized, '', $result_arr );
		
		
		$sum_all_system_visits	= 0;
		$max_system_visits		= 0;

		if( count( $result_arr ) > 0 ) {
			foreach( $result_arr as $row ) {
            	$sum_all_system_visits += $row->os_visits;

            	if( $row->os_visits > $max_system_visits ) {
                    $max_system_visits = $row->os_visits;
            	}
        	}
		}

		$ostype_name_arr = array();
		{
			$__jstats_ostype = unserialize(_JS_DB_TABLE__OSTYPE);
			foreach( $__jstats_ostype as $ostype )
				$ostype_name_arr[] = $ostype['ostype_name'];
		}
		
        $JSStatisticsTpl = new js_JSStatisticsTpl();
        $result .= $JSStatisticsTpl->viewSystemsPageTpl( $sum_all_system_visits, $max_system_visits, $ostype_name_arr, $result_arr );
        
        $result .= $JSStatisticsCommon->getJSStatisticsFooterHtmlCode();
        
        return $result;
	}

		
	/**
	 * this function return HTML table with 'Not identified visitors'
	 * (case r11)
	 *
	 * old function name 'getNotIdentified();'
	 *
	 * @param $JSConf - only for performance
	 * @return html page
	 */
	function viewNotIdentifiedVisitors($JSConf = null) {
		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );
		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.many.rows.php' );
		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'filters.php' );
		
		if ($JSConf == null)
			$JSConf = new js_JSConf();
			
		global $mainframe;
		global $option;
		
		$result = '';
				
		//global $mosConfig_offset;
		$TimePeriod = new js_JSFilterTimePeriodDeprecated();
		$TimePeriod->SetDMY2Now( $mainframe->getCfg( 'offset' ) );
		$TimePeriod->readDateFromRequest( $mainframe->getCfg( 'offset' ), $JSConf->startdayormonth );
		
		$JSStatisticsCommon = new js_JSStatisticsCommon();
		$FilterSearch = null;
		$FilterDomain = null;
		$vid = '';
		$moreinfo = '';
		$result .= $JSStatisticsCommon->getJSStatisticsHeaderHtmlCode($JSConf, $FilterSearch, $TimePeriod, $vid, $moreinfo, $FilterDomain);

		
		$limit	= intval( $mainframe->getUserStateFromRequest( 'viewlistlimit', 'limit', $mainframe->getCfg( 'list_limit' ) ) );
        $limitstart	= intval( $mainframe->getUserStateFromRequest( 'viewlimitstart', 'limitstart', 0 ) );

		$include_summarized = $JSConf->include_summarized;
        
		$NumberOfNotIdentifiedVisitors = 0;
		$JSDbSOV = new js_JSDbSOV();
		$JSDbSOV->selectNumberOfNotIdentifiedVisitors( $TimePeriod, $include_summarized, $NumberOfNotIdentifiedVisitors );
		
		if( defined( '_JEXEC' ) ) {
			jimport( 'joomla.html.pagination' );
			$pagination = new JPagination( $NumberOfNotIdentifiedVisitors, $limitstart, $limit );
		}else{
			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			$pagination = new mosPageNav( $NumberOfNotIdentifiedVisitors, $limitstart, $limit );
		}

		$JSDbSMR = new js_JSDbSMR();
		$rows = null;
		$JSDbSMR->selectNotIdentifiedVisitorsArr($pagination->limitstart, $pagination->limit, $TimePeriod, $include_summarized, $rows );

		
        $JSStatisticsTpl = new js_JSStatisticsTpl();
        $result .= $JSStatisticsTpl->viewNotIdentifiedVisitorsPageTpl( $rows, $pagination );
        
        $result .= $JSStatisticsCommon->getJSStatisticsFooterHtmlCode();
        
        return $result;
	}
	
	

	/**
	 * this function return table with 'Resolutions'
	 * case r15
	 *
	 * @param $JSConf - only for performance
	 * @param object $TimePeriod
	 * @param integer $buid
	 * @return html
	 * @since 2.3.x
	 */
	function viewResolutions( $JSConf = null, $TimePeriod, $buid ) {
		global $mainframe;
		global $option;

		if ($JSConf == null)
			$JSConf = new js_JSConf();
			
		$this->_getDB();

		$limit	= intval( $mainframe->getUserStateFromRequest( 'viewlistlimit', 'limit', $mainframe->getCfg( 'list_limit' ) ) );
        $limitstart	= intval( $mainframe->getUserStateFromRequest( 'viewlimitstart', 'limitstart', 0 ) );

		$where				= array();
		$summary			= array();
		$summary['screens']		= 0;
		$summary['number']		= 0;
		$summary['maximum']		= 0;

		$this->resetVar(1);

		$where[] = 'c.ip_id = a.id';
		//$where[] = 'a.type = 1';
		//$where[] = 'c.day LIKE \'' . $this->d . '\'';
		//$where[] = 'c.month LIKE \'' . $this->m . '\'';
		//$where[] = 'c.year LIKE \'' . $this->y . '\'';

		//echo 'JSengine->d [' . $this->d . ']<br />';

		if( !$JSConf->include_summarized ) {
			$where[] = 'a.id = c.ip_id AND c.id >= ' . $this->buid();
		}

		// get total records
		/*
		// mic 20081015: not used, but ready to use
		$query = 'SELECT COUNT(*)'
		. ' FROM #__jstats_ipaddresses AS a, #__jstats_visits AS c'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		;
		$this->db->setQuery( $query );
		$total = $this->db->loadResult();

		if( defined( '_JEXEC' ) ) {
			jimport( 'joomla.html.pagination' );
			$pagination = new JPagination( $total, $limitstart, $limit );
		}else{
			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			$pagination = new mosPageNav( $total, $limitstart, $limit );
		}
		*/
		$pagination = null; // mic: set here to null ONLY if pagination IS NOT USED!

		$query = 'SELECT a.screen, count(*) AS numbers'
		. ' FROM #__jstats_ipaddresses AS a, #__jstats_visits AS c'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		. ' GROUP BY a.screen'
		. ' ORDER BY numbers DESC, a.screen ASC'
		;
		$this->db->setQuery( $query ); // , $pagination->limitstart, $pagination->limit ); // mic: ready to use
		$rows = $this->db->loadObjectList();

		$this->resetVar( 0 ); // mic: why is this crazy thing here ?????

		if( count( $rows ) > 0 ) {
			foreach( $rows as $row ) {
            	++$summary['screens'];
                $summary['number'] += $row->numbers;

            	if( $row->numbers > $summary['maximum'] ) {
                    $summary['maximum'] = $row->numbers;
            	}
        	}
		}

        $JSStatisticsTpl = new js_JSStatisticsTpl();
        return $JSStatisticsTpl->viewResolutionsTpl( $rows, $pagination, $summary );
	}
}