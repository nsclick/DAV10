<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}


require_once( dirname(__FILE__) .DS. 'base.classes.php' );
require_once( dirname(__FILE__) .DS. 'template.html.php' );
//require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );


define( '_JSAdminImagePath',	JURI::base() . '/components/com_joomlastats/images/' );//works in j1.0.15



/**
 * NOTICE: This class will be divided to 2 classes: js_JSStatistics and js_JSStatisticsTpl
 *         Maybe the code, that You are looking for, already has been moved there!
 */
class JoomlaStats_Engine extends JSBasic
{
	var $d = null; 				// screenselection - day
	var $m = null; 				// screenselection - month
	var $y = null; 				// screenselection - year
	var $dom = null; 			// screenselection - domain
	var $vid = null; 			// screenselection - visitors id
	var $moreinfo = null;		// screenselection - moreinfo (pass vid from r03 to r03a/r03b)
	var $updatemsg= null;		// update message used for purge
	var $hourdiff = 0;		// hourdiff local vs server
	var $task = null;			// task for JoomlaStats_Engine //@todo this member should be removed!!

	// internal
	var $add 		= array(); // holds purged datas
	

	//use getStyleForDetailView() instead of below line
	var $add_dstyle	= '<span style="font-weight:normal; font-style:italic; color:#007BBD">%s</span>';	// style 4 detail view
	
	//use getStyleForSummarizedNumber() instead of below line
	var $add_style	= '&nbsp;<span style="font-weight:normal; font-style:italic;">[ %s ]</span>';		// style 4 summary view

	
	var $JSConf		= null; // 'JS' configuration object. Holds system and user settings

	/** @todo $task argument should be removed */
	function __construct( $task = '', $JSConf = null ) {
		global $mainframe; // new mic 20081005
		
		parent::__construct();

		if ( $JSConf == null ) {
			$this->JSConf = new js_JSConf();
		}else{
			$this->JSConf = $JSConf;
		}

		$this->task = $task;
		$this->hourdiff = $mainframe->getCfg( 'offset' );

		//@at 2 bugs were here - now should be OK
		//  - $this->dom = 'total'; - $this->dom could not have value total (becouse $this->dom is used in SQL querys)
		//  - value of $this->dom could not depend DIRECTLY on $this->JSConf->startdayormonth option (Compare SVN revision 102 and 103 for details)

		// new mic (better compatibility to J.1.5
		$this->dom = JRequest::getVar( 'dom' );
		$this->vid = JRequest::getVar( 'vid' );
	}


	/**
	 * Displays a percentage bar
	 *
	 * @param integer $percent
	 * @param integer $maxpercent
	 * @return string
	 */
	function PercentBar( $percent, $maxpercent ) {
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'statistics.common.html.php' );
		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();
		return $JSStatisticsCommonTpl->PercentBar($percent, $maxpercent);
	}

	/**
	 * resets/changes date vars for database access
	 *
	 * @param integer	opt
	 * $opt=0: set dmy to text 'total');
	 * $opt=1: set dmy to '%' in order to perform database querry
	 */
	function resetVar( $opt ) {

		if( $opt == 1 ) {
			if( $this->d == 'total' )	{ $this->d = '%'; }
			if( $this->m == 'total' )	{ $this->m = '%'; }
			if( $this->y == 'total' )	{ $this->y = '%'; }
		}else{
			if( $this->d == '%' )	{ $this->d = 'total'; }
			if( $this->m == '%' )	{ $this->m = 'total'; }
			if( $this->y == '%' )	{ $this->y = 'total'; }
		}
	}

	/**
	 * returns first id from current table page_request for checking inside page_request_c
	 * used where queries should be done and result is shown/included with purged data
	 *
	 * @return integer
	 */
	function buid() {
		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR.'select.one.value.php' );

		$buid = 0;
		
		$JSDbSOV = new js_JSDbSOV();
		$JSDbSOV->getBuid($buid);
		
		return $buid;
	}

	/**
	 * builds an order UP/DOWN button
	 *
	 * @param string $view	which task to perform
	 * @param string $type	up/down
	 * @return string
	 *
	 * mic: where the hell is this function used???
	 */
	function JS_order( $view, $type ) {

		if( $type == 'up' ) {
			$type	= JTEXT::_( 'Up A-Z' );
			$JS_img = 'uparrow0.png';
		}else{
			$type	= JTEXT::_( 'Down Z-A' );
			$JS_img = 'downarrow0.png';
		}

		$JS_order_img_path = '<img src="' . JURI::base() . 'images/';

        $JS_order = '<a href="' . $this->_index() . '?option=com_joomlastats&amp;task=r04&amp;' . $view . '=%s'
        . '&amp;d='. $this->d . '&amp;m=' . $this->m . '&amp;y=' . $this->y
        . '" title="' . $type . '">'
        . $JS_order_img_path . $JS_img . '" width="12" height="12" border="0"'
        . ' alt="' . $type . '"/>'
        . '</a>';

        return $JS_order;
	}

	/**
	 * Shows the summary for a year
	 * case r01
	 *
	 * @return string
	 */
	function ysummary() {

		$this->_getDB();
		$JSUtil = new js_JSUtil();

		$where = array();

		if( $this->y == '%' ) {
			$visittime	= ( time() + ( $this->hourdiff * 3600 ) );
			$this->y	= date( 'Y', $visittime );

			$retval = '<div class="jsInfoItem" style="margin-left:150px; text-align:left;">'
			. JTEXT::_( 'You have not choosen a year displaying data of' )
			.': <strong>'. $this->y . '</strong></div>';

			$retval = '<div class="jsinfo" style="text-align:center; background-color:#FFFFDF; margin:3px; padding:3px">'
			. $retval
			. '</div>';
		}else{
			// to make $retval .= possible next time
			$retval = '';
		}

		$v			= 0; // visitor;
		$uv			= 0; // unique visitor
		$b			= 0; // bots
		$ub			= 0; // unique bots
		$p 			= 0; // pages
		$r 			= 0; // referrers
		$tuv		= 0; // total unique visitors
		$tv			= 0; // total visitors
		$tub		= 0; // total unique bots
		$tb			= 0; // total bots
		$tp			= 0; // total pages
		$tr			= 0; // total referrers
		$ppurge		= 0; // purged pages
		$vpurge 	= 0; // purged visitors
		$uvpurge	= 0; // unique visitors purged
		$tuvpurge	= 0; // total unique visitors purged
		$tvpurge	= 0; // total visitors purged
		$tppurge	= 0; // total pages purged
		$bpurge		= 0; // bots purged
		$tbpurge	= 0; // total bots purged
		$ubpurge	= 0; // unique bots purged
		$tubpurge	= 0; // total unique bots purged

		$retval .= '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>'
		. '<th align="center" nowrap="nowrap">' . JTEXT::_( 'Month' ) . '</th>'
		. '<th align="center" nowrap="nowrap" colspan="2">' . JTEXT::_( 'Unique visitors' ) . '</th>'
		. '<th align="center" colspan="2" nowrap="nowrap" title="' . JTEXT::_( 'Number of visitors' ) .'">' . JTEXT::_( 'Visitors' ) . '</th>'
		. '<th align="center" nowrap="nowrap" colspan="2">' . JTEXT::_( 'Visits average' ) . '</th>'
		. '<th align="center" nowrap="nowrap" colspan="2">' . JTEXT::_( 'Pages' ) . '</th>'
		. '<th align="center" nowrap="nowrap">' . JTEXT::_( 'Referrers' ) . '</th>'
		. '<th align="center" nowrap="nowrap" colspan="2">' . JTEXT::_( 'Unique bots/spiders' ) . '</th>'
		. '<th align="center" nowrap="nowrap" colspan="2">' . JTEXT::_( 'Number of bots/spiders' ) . '</th>'
		. '</tr>' . "\n";

		$k = 0;
		for( $i = 1; $i < 13; $i++ ) {
			// get visitors
			$this->resetVar(1);

			$where = NULL;

			$where[] = 'a.type = 1';
			$where[] = 'c.month = \'' . $i . '\'';
			$where[] = 'c.year = \'' . $this->y . '\'';

			if( $this->JSConf->include_summarized ) {
				// mic: show data with purged data
				$where[] = 'a.id = c.ip_id AND c.id >= ' . $this->buid();
			}

			$query  = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a'
			. ' ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			;
			$this->resetVar(0);
			$this->db->setQuery( $query );
			$v = $this->db->loadResult();

			$tv += $v;

			if( $this->JSConf->include_summarized ) {
				// include/show purged data
				$where = NULL;

				$where[] = 'a.type = 1';
				$where[] = 'c.month = \'' . $i . '\'';
				$where[] = 'c.year = \'' . $this->y . '\'';
				$where[] = '(a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

            	$query = 'SELECT count(*)'
            	. ' FROM #__jstats_visits AS c'
            	. ' LEFT JOIN #__jstats_ipaddresses AS a'
            	. ' ON c.ip_id = a.id'
            	. ( count( $where ) ? ' WHERE ' . implode(' AND ', $where ) : '' );
				$this->resetVar(0);
            	$this->db->setQuery( $query );
            	$vpurge = $this->db->loadResult();

				$tvpurge	+= $v;
            	$tvpurge	+= $vpurge;
			}

			$where = NULL;

			$where[] = 'a.type = 1';
			$where[] = 'c.month = ' . $i;
			$where[] = 'c.year = \'' . $this->y . '\'';


            if( $this->JSConf->include_summarized ) {
            	// mic: show data with purged data
				$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
            }

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' GROUP BY c.ip_id'
			;
			$this->resetVar(0);
			$this->db->setQuery( $query );
			$rows = $this->db->loadObjectList();

            $uv		= count( $rows );
			$tuv 	+= $uv;

			if( $this->JSConf->include_summarized ) {
				// include/show purged data
                $where = NULL;

                $where[] = 'a.type = 1';
				$where[] = 'c.month = ' . $i;
                $where[] = 'c.year = \'' . $this->y . '\'';
                $where[] = '(a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

                $query  = 'SELECT count(*)'
                . ' FROM #__jstats_visits AS c'
                . ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
                . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
                . ' GROUP BY c.ip_id'
                ;
                $this->resetVar(0);
				$this->db->setQuery( $query );
                $rows = $this->db->loadObjectList();

                $uvpurge 	= count( $rows );
                $tuvpurge   += $uv;
                $tuvpurge	+= $uvpurge;
			}

			// get bots
			$this->resetVar(1);

			$where = NULL;

			$where[] = 'a.type = 2';
			$where[] = 'c.month = ' . $i;
			$where[] = 'c.year = \'' . $this->y . '\'';

			if( $this->JSConf->include_summarized ) {
				// mic: show only actual data (without already archived/purged)
				$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
			}

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			;
			$this->db->setQuery( $query );
			$b = $this->db->loadResult();

			$this->resetVar(0);

			$tb += $b;

			// get purged bots
			if( $this->JSConf->include_summarized ) {
                $this->resetVar(1);

                $where = NULL;

                $where[] = 'a.type = 2';
                $where[] = 'c.month = ' . $i;
                $where[] = 'c.year = \'' . $this->y . '\'';
                $where[] = '( a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

                $query = 'SELECT count(*)'
                . ' FROM #__jstats_visits AS c'
                . ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
                . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
                ;
                $this->db->setQuery( $query );
                $bpurge = $this->db->loadResult();

                $this->resetVar(0);

                $tbpurge += $bpurge;
                $tbpurge += $b;
            }

			// get Unique bots
			$this->resetVar(1);

			$where = NULL;
			$where[] = 'a.type = 2';
			$where[] = 'c.month = ' . $i;
			$where[] = 'c.year = \'' . $this->y . '\'';

			if( $this->JSConf->include_summarized ) {
				// mic: show only actual data (without already archived/purged)
				$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
			}

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' GROUP BY a.browser'
			;
			$this->resetVar(0);
			$this->db->setQuery( $query );
			$rows = $this->db->loadObjectList();

            $ub		= count( $rows );
			$tub 	+= $ub;

			// get purged Unique bots
			if( $this->JSConf->include_summarized ) {
			    $this->resetVar(1);

                $where = NULL;
                $where[] = 'a.type = 2';
                $where[] = 'c.month = ' . $i;
                $where[] = 'c.year = \'' . $this->y . '\'';
                $where[] = '(a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

                $query = 'SELECT count(*)'
                . ' FROM #__jstats_visits AS c'
                . ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
                . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
                . ' GROUP BY a.browser'
                ;
                $this->resetVar(0);
                $this->db->setQuery( $query );
                $rows = $this->db->loadObjectList();

                $ubpurge	= count( $rows );
                $tubpurge	+= $ubpurge;
                $tubpurge	+= $ub;
            }

			// get Pages
			$this->resetVar(1);

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_page_request'
			. ' WHERE month = ' . $i
			. ' AND year = ' . $this->y
			;
			$this->db->setQuery( $query );
			$p = $this->db->loadResult();

			$this->resetVar(0);

			// $tp += $p; // mic: see below

			// purged pages
            $this->resetVar(1);

            $query = 'SELECT sum(count)'
            . ' FROM #__jstats_page_request_c'
            . ' WHERE month = ' . $i
            . ' AND year = ' . $this->y
            ;
            $this->db->setQuery( $query );
            $ppurge = $this->db->loadResult();

            $this->resetVar(0);

            if( $this->JSConf->include_summarized ) {
                $tppurge    += $p;
                $tppurge    += $ppurge;
            }else{
                $p += $ppurge;
            }

			$tp += $p; // mic: see above




			// get Referrers
			$this->resetVar(1);

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_referrer'
			. ' WHERE month = ' . $i
			. ' AND year = ' . $this->y
			;
			$this->db->setQuery( $query );
			$r = $this->db->loadResult();

			$this->resetVar(0);
			$tr += $r;




			if( $this->JSConf->include_summarized ) {
				$add = null;
			    if( $uvpurge ) {
                    $add['uvpurge']	= sprintf( $this->add_dstyle, $uvpurge );
			    }
                if( $vpurge ) {
			        $add['vpurge']	= sprintf( $this->add_dstyle, $vpurge );
                }
                if( $ppurge ) {
			        $add['ppurge']	= sprintf( $this->add_dstyle, $ppurge );
                }
                if( $bpurge ) {
			        $add['bpurge']	= sprintf( $this->add_dstyle, $bpurge );
                }
                if( $ubpurge ) {
			        $add['ubpurge']	= sprintf( $this->add_dstyle, $ubpurge );
                }
            }



			// Now we have all data, let's show the lines of each month
			
			$MonthsNamesTruntucatd = $JSUtil->getMonthsNamesTruntucatd();

			$retval .= '<tr class="row' . $k . '">'
			. '<td align="center">'	. $MonthsNamesTruntucatd[$i] . '</td>'
			. '<td align="right">'	. ( $uv ? $uv : '.' ) . '</td>'
			. '<td align="left">'	. ( !empty( $add['vpurge'] ) ? $add['vpurge'] : '&nbsp;' ) . '</td>'
			. '<td align="right">'	. ( $v  ? $v  : '.' ) . '</td>'
			. '<td align="left">'	. ( !empty( $add['uvpurge'] ) ? $add['uvpurge'] : '&nbsp;' ) . '</td>'
			. '<td align="center">';

			if( ( $uv != 0 ) && ( $v != 0 ) ) {
				$retval .= number_format( round( ( $v / $uv ), 1), 1);
			}else{
				$retval .= '.';
			}
			$retval .= '</td><td>';

			if( ( $uvpurge != 0 ) && ( $vpurge != 0 ) ) {
				$retval .= sprintf( $this->add_dstyle, number_format( round( ( $vpurge / $uvpurge ), 1), 1 ) );
			}else{
				$retval .= '&nbsp;';
			}

			$retval .= '</td>'
			. '<td align="center">' . ( $p ? $p : '.' ) . ' ' . '</td>'
			. '<td>' . ( !empty( $add['ppurge'] ) ? $add['ppurge'] : '' ) . '</td>'
			. '<td align="center">' . ( $r ? $r : '.' ). '</td>'
			. '<td align="center">' . ( $ub ? $ub : '.' ) . ' ' . '</td><td>'
			. ( !empty( $add['ubpurge'] ) ? $add['ubpurge'] : '' ) . '</td>'
			. '<td align="center">' . ( $b ? $b : '.' ) . ' ' . '</td><td>'
			. ( !empty( $add['bpurge'] ) ? $add['bpurge'] : '' ). '</td>'
			. '</tr>' . "\n";

			$k = 1 - $k;
		}

        if( $this->JSConf->include_summarized ) {
			if( $tuvpurge ) {
				$add['tuvpurge']	= sprintf( $this->add_style, $tuvpurge );
			}
			if( $tppurge ) {
				$add['tppurge']		= sprintf( $this->add_style, $tppurge );
			}
			if( $tvpurge ) {
				$add['tvpurge']		= sprintf( $this->add_style, $tvpurge );
			}
			if( $tbpurge ) {
				$add['tbpurge']		= sprintf( $this->add_style, $tbpurge );
			}
			if( $tubpurge ) {
				$add['tubpurge']	= sprintf( $this->add_style, $tubpurge );
			}
		}

		// re-work coding of next part:
		// Get the values for the totals line
		// somewhere before this next block of code also tuv is calculated (wrongly)

		// get Total Unique visitors for complete month
		$this->resetVar( 1 );

		$query = 'SELECT count(*)'
		. ' FROM #__jstats_visits'
		. ' LEFT JOIN #__jstats_ipaddresses ON (#__jstats_visits.ip_id=#__jstats_ipaddresses.id)'
		. ' WHERE'
		. '   #__jstats_ipaddresses.type = 1'
		. '   AND #__jstats_visits.year = ' . $this->y
		. ' GROUP BY #__jstats_visits.ip_id'
		;
		$this->resetVar( 0 );
		$this->db->setQuery( $query );
		$rows_tuv = $this->db->loadObjectList();
		$tuv = count( $rows_tuv );

		// get Total Unique bots
		$this->resetVar( 1 );

		$query = 'SELECT count(*)'
		. ' FROM #__jstats_visits'
		. ' LEFT JOIN #__jstats_ipaddresses ON (#__jstats_visits.ip_id=#__jstats_ipaddresses.id)'
		. ' WHERE #__jstats_ipaddresses.type = 2'
		. ' AND #__jstats_visits.year = \'' . $this->y . '\''
		. ' GROUP BY #__jstats_ipaddresses.browser'
		;
		$this->resetVar( 0 );
		$this->db->setQuery( $query );
		$rows_tub = $this->db->loadObjectList();
		$tub = count( $rows_tub );


		// Display the totals line
		$retval .= '<tr>'
		// Month
		. '<th align="center">' . $this->y . '</th>'
		// Unique visitors
		. '<th align="right">'. $tuv .'</th>'
		. '<th align="left">'. ( !empty( $add['tuvpurge'] ) ? $add['tuvpurge'] : '&nbsp;' ) . '</th>'
		// Number of visits
		. '<th align="right">'. $tv .'</th>'
		. '<th align="left">'. ( !empty( $add['tvpurge'] ) ? $add['tvpurge'] : '&nbsp;' ) . '</th>'
		// Visits average
		. '<th align="center">';

		if( ( $tuv != 0 ) && ( $tv != 0 ) ) {
			$retval .= number_format( round( ( $tv / $tuv ), 1), 1);
		}else{
			$retval .= '0.0';
		}

		$retval .= '</th><th align="left">';

		if( ( $tuvpurge != 0 ) && ( $tvpurge != 0 ) ) {
			$retval .= $add['tvpurge'] = sprintf( $this->add_style, number_format( round( ( $tvpurge / $tuvpurge ), 1) , 1 ) );
		}else{
			$retval .= '';
		}

		$retval .= '</th>';
		// Pages
		$retval .= '<th align="center">'. $tp . '</th>'
		. '<th align="center">' . ( !empty( $add['tppurge'] ) ? $add['tppurge'] : '&nbsp;' ) . '</th>'
		// Referrers
		. '<th align="center">' . $tr . '</th>'
		// Unique bots
		. '<th align="center">' . $tub . '</th>'
		. "<th align='center'>" . ( !empty( $add['tubpurge'] ) ? $add['tubpurge'] : '&nbsp;' ) . '</th>'
		// Number of bots
		. '<th align="center">' . $tb . '</th>'
		. '<th align="center">' . ( !empty( $add['tbpurge'] ) ? $add['tbpurge'] : '&nbsp;')  .'</th>'
		. '</tr>' . "\n"
		. '</table>' . "\n";

		return $retval;
	}

	/**
	 * displays a month summary
	 * case r02
	 *
	 * @return string
	 */
	function msummary() {

		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR.'select.one.value.php' );

		$this->_getDB();
		$JSUtil = new js_JSUtil();


		$JSDbSOV = new js_JSDbSOV();

		$buid = 0;
		$JSDbSOV->getBuid( $buid );
		

		$where	= array();
		$retval = '';
		$info	= ''; // new mic

		if( $this->m == '%' ) {
			$MonthsNamesLong = $JSUtil->getMonthsNamesLong();

			// user selected whole month ('-')
			$visittime	= ( time() + ( $this->hourdiff * 3600 ) );
			$this->m	= date( 'n', $visittime );

			$info .= '<div class="jsInfoItem" style="margin-left:150px; text-align:left;">'
			. JTEXT::_( 'You have not choosen a month displaying data of' )
			.': <strong>'. $MonthsNamesLong[$this->m] . '</strong></div>';
		}

		if( $this->y == '%' ) {
			$visittime	= ( time() + ( $this->hourdiff * 3600 ) );
			$this->y	= date( 'Y', $visittime );

			$info .= '<div class="jsInfoItem" style="margin-left:150px; text-align:left;">'
			. JTEXT::_( 'You have not choosen a year displaying data of' )
			.': <strong>' . $this->y . '</strong></div>';
		}

		if( $info ) {
			$retval .= '<div class="jsinfo" style="text-align:center; background-color:#FFFFDF; margin:3px; padding:3px">'
			. $info
			. '</div>';

			$info = '';
		}

		$dm = array(0,31,28 + date('L',mktime(0,0,0,(int)$this->m,(int)$this->d,(int)$this->y)),31,30,31,30,31,31,30,31,30,31);

		$v 			= 0; // visitors
		$b 			= 0; // bots
		$p			= 0; // pages
		$r			= 0; // referrer
		$ub 		= 0; // unique bots
		$tub		= 0; // total unique bots
		$uv 		= 0; // unique visitors
		$tv 		= 0; // total visitors
		$tuv		= 0; // total unique visitors
		$tb 		= 0; // total bots
		$tp 		= 0; // total pages
		$tr 		= 0; // total referrers
		$ppurge 	= 0; // purged pages
		$tppurge	= 0; // total pages purged
		$vpurge		= 0; // visitor purged
		$tvpurge	= 0; // total visitor purged
		$uvpurge	= 0; // unique visitor purged
		$tuvpurge	= 0; // total unique visitor purged
		$bpurge		= 0; // bots purged
		$tbpurge	= 0; // total bots purged
		$ubpurge	= 0; // unique bots purged
		$tubpurge	= 0; // total unique bots purged
		$niv		= 0; // not identified visitors
		$tniv		= 0; // total not identified visitors
		$nivpurge	= 0; // not identified visitors purged
		$tnivpurge	= 0; // total not identified visitors purged
		$univ		= 0; // unique not identified visitors
		$tuniv		= 0; // total unique not identified visitors
		$univpurge	= 0; // unique not identified visitors purged
		$tunivpurge	= 0; // total unique not identified visitors purged
		$sum		= 0; // sum
		$tsum		= 0; // total sum
		$usum		= 0; // sum
		$tusum		= 0; // total sum


		$retval .= '<table class="adminlist" cellspacing="0" cellpadding="0" width="100%">' . "\n" . '<tr>'
		. '<th align="center" nowrap="nowrap">' . JTEXT::_( 'Day' ) . '</th>'
		. '<th align="center" colspan="2" nowrap="nowrap" title="' . JTEXT::_( 'Number of unique visitors' ) .'">' . JTEXT::_( 'Unique visitors' ) . '</th>'
		. '<th align="center" colspan="2" nowrap="nowrap" title="' . JTEXT::_( 'Number of visitors' ) .'">' . JTEXT::_( 'Visitors' ) . '</th>'
		. '<th align="center" nowrap="nowrap" title="' . JTEXT::_( 'Number of visitors' ) . ' / ' . JTEXT::_( 'Number of unique visitors' ) . '">' . JTEXT::_( 'Visits average' ) . '</th>'
		. '<th align="center" colspan="2" nowrap="nowrap" title="' . JTEXT::_( 'Number of visited pages' ) .'">' . JTEXT::_( 'Page impressions' ) . '</th>'
		. '<th align="center" nowrap="nowrap">' . JTEXT::_( 'Referrers' ) . '</th>'
		. '<th align="center" colspan="2" nowrap="nowrap" title="' . JTEXT::_( 'Number of unique bots/spiders' ) .'">' . JTEXT::_( 'Unique bots/spiders' ) .'</th>'
		. '<th align="center" colspan="2" nowrap="nowrap" title="' . JTEXT::_( 'Number of bots/spiders' ) .'">' . JTEXT::_( 'Bots/spiders' ) .'</th>'
		. '<th align="center" nowrap="nowrap" title="' . JTEXT::_( 'Number of unique not identified visitors' ) .'">' . JTEXT::_( 'Unique NIV' ) .'</th>'
		. '<th align="center" nowrap="nowrap" title="' . JTEXT::_( 'Number of not identified visitors' ) .'">' . JTEXT::_( 'NIV' ) .'</th>'
		. '<th align="center" nowrap="nowrap">' . JTEXT::_( 'Unique sum' ) . '</th>'
		. '<th align="center" nowrap="nowrap">' . JTEXT::_( 'Sum' ) . '</th>'
		. '</tr>' . "\n";

		for( $i = 1; $i <= $dm[$this->m]; $i++) {

			$this->resetVar( 1 );
			$year = $this->y;
			$month = $this->m;
			$day = $i;
			$this->resetVar( 0 );


			// get Unique visitors
			$this->resetVar( 1 );
			$where = null;
			$where[] = 'a.type = 1';
			$where[] = 'c.day = ' . $i;
			$where[] = 'c.month = \'' . $this->m . '\'';
			$where[] = 'c.year = \'' . $this->y . '\'';

			if( $this->JSConf->include_summarized ) {
            	$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
			}

			$query  = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' GROUP BY c.ip_id'
			;
			$this->db->setQuery( $query );
			$rows = $this->db->loadObjectList();

			$uv		= count( $rows );
			$tuv 	+= $uv;

			// include/show purged data
		    if( $this->JSConf->include_summarized ) {
				$where = null;

                $where[] = 'a.type = 1';
				$where[] = 'c.day = ' . $i;
                $where[] = 'c.month = \'' . $this->m . '\'';
                $where[] = 'c.year = \'' . $this->y . '\'';
                $where[] = '(a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

                $query  = 'SELECT count(*)'
                . ' FROM #__jstats_visits AS c'
                . ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
                . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
                . ' GROUP BY c.ip_id'
                ;
				$this->db->setQuery( $query );
                $rows = $this->db->loadObjectList();

                $uvpurge 	= count( $rows );
                $tuvpurge	+= $uvpurge;
                $tuvpurge   += $uv;
			}

			$this->resetVar( 0 ); // mic: why resetting here???

			// get visitors
			$this->resetVar( 1 );
			$where = NULL;
			$where[] = 'a.type = 1';
			$where[] = 'c.day = ' . $i;
			$where[] = 'c.month = \'' . $this->m . '\'';
			$where[] = 'c.year = \'' . $this->y . '\'';

			if( $this->JSConf->include_summarized ) {
            	$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
			}

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			;
			$this->db->setQuery( $query );
			$v = $this->db->loadResult();

			$tv += $v;

			// include/show purged data
			if( $this->JSConf->include_summarized ) {
            	$where = null;
                $where[] = 'a.type = 1'; // mic: exclude only bots
				$where[] = 'c.day = ' . $i;
				$where[] = 'c.month = \'' . $this->m . '\'';
                $where[] = 'c.year = \'' . $this->y . '\'';
                $where[] = '(a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

                $query = 'SELECT count(*)'
                . ' FROM #__jstats_visits AS c'
                . ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
                . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
                ;
				$this->db->setQuery( $query );
                $vpurge = $this->db->loadResult();

                $tvpurge	+= $vpurge;
                $tvpurge	+= $v;
			}

			$this->resetVar( 0 ); // mic: why resetting here??

			// get bots
			$this->resetVar( 1 );

			$where = NULL;
			$where[] = 'a.type = 2';
			$where[] = 'c.day = ' . $i;
			$where[] = 'c.month = \'' . $this->m . '\'';
			$where[] = 'c.year = \'' . $this->y . '\'';

			if( $this->JSConf->include_summarized ) {
				$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
			}

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			;
			$this->db->setQuery( $query );
			$b = $this->db->loadResult();

			$this->resetVar(0);

			// include/show purged data
		    if( $this->JSConf->include_summarized ) {
                $where = NULL;

                $this->resetVar( 1 );

                $where[] = 'a.type = 2';
			    $where[] = 'c.day = ' . $i;
                $where[] = 'c.month = \'' . $this->m . '\'';
                $where[] = 'c.year = \'' . $this->y . '\'';
                $where[] = '(a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

                $query = 'SELECT count(*)'
			    . ' FROM #__jstats_visits AS c'
                . ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
                . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
            	;
			    $this->db->setQuery( $query );
                $bpurge = $this->db->loadResult();

                $this->resetVar( 0 ); // mic: why resetting here?????

                $tbpurge += $bpurge;
                $tbpurge += $b;
            }

			$tb += $b;

			// get Unique bots
			$this->resetVar( 1 );

			$where = NULL;

			$where[] = 'a.type = 2';
			$where[] = 'c.day = ' . $i;
			$where[] = 'c.month = \'' . $this->m . '\'';
			$where[] = 'c.year = \'' . $this->y . '\'';

			if( $this->JSConf->include_summarized ) {
                $where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
            }

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_visits AS c'
			. ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' GROUP BY a.browser'
			;
			$this->resetVar( 0 ); // mic: why resetting here????

			$this->db->setQuery( $query );
			$rows = $this->db->loadObjectList();
			$ub = count( $rows );

			// include/show purged data - unique bots
		    if( $this->JSConf->include_summarized ) {
                $where = null;

                $this->resetVar( 1 ); // mic: why resetting here????

                $where[] = 'a.type = 2';
                $where[] = 'c.day = ' . $i;
                $where[] = 'c.month = \'' . $this->m . '\'';
                $where[] = 'c.year = \'' . $this->y . '\'';
                $where[] = '(a.id = c.ip_id AND c.id < ' . $this->buid() . ')';

                $query = 'SELECT count(*)'
                . ' FROM #__jstats_visits AS c'
                . ' LEFT JOIN #__jstats_ipaddresses AS a ON c.ip_id = a.id'
                . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
                . ' GROUP BY a.browser'
                ;
                $this->db->setQuery( $query );
                $rows = $this->db->loadObjectList();

                $ubpurge = count( $rows );

                $tubpurge += $ubpurge;
                $tubpurge += $ub;

                $this->resetVar( 0 ); // mic: why resetting here????
            }

			$tub += $ub; // new mic



			// get Pages
			$this->resetVar( 1 );

			$where = null;

			$where[] = 'day = ' . $i;
			$where[] = 'month = \'' . $this->m . '\'';
			$where[] = 'year = \'' . $this->y . '\'';

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_page_request'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			;
			// $this->resetVar( 0 ); // mic: why resetting here ????
			$this->db->setQuery( $query );
			$p = $this->db->loadResult();

			// $tp += $p; // mic_ moved down

			// purged pages
            $this->resetVar( 1 ); // mic: how many times are we resetting??? what for ???

            $where = null;

			$where[] = 'day = ' . $i;
			$where[] = 'month = \'' . $this->m . '\'';
			$where[] = 'year = \'' . $this->y . '\'';

            $query = 'SELECT sum(count)'
            . ' FROM #__jstats_page_request_c'
            . ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
            ;
            $this->db->setQuery( $query );
            $ppurge = $this->db->loadResult();

            $this->resetVar( 0 );

            if( $this->JSConf->include_summarized ){
                //$ppurge     += $p;//In all other cases second column show only purged data, so this line must to be removed
                $tppurge    += $p;
                $tppurge    += $ppurge;
            }else{
                $p += $ppurge;
            }

            $tp += $p; // mic: see below



			// get Referrers
			$this->resetVar( 1 ); // mic: i live this resetting ....... ;-((

			$where = null;
			$where[] = 'day = ' . $i;
			$where[] = 'month = \'' . $this->m . '\'';
			$where[] = 'year = \'' . $this->y . '\'';

			$query = 'SELECT count(*)'
			. ' FROM #__jstats_referrer'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			;
			$this->resetVar( 0 ); // mic: why is this here ????????????????????????????????
			$this->db->setQuery( $query );
			$r = $this->db->loadResult();

			$tr += $r;


			{// not identified visitors
				$visitors_type = _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR;
				$JSDbSOV->selectNumberOfVisitorsForYMD( $visitors_type, $this->JSConf->include_summarized, $buid, $year, $month, $day, $niv );
				$tniv += $niv;

		    	if( $this->JSConf->show_summarized ) {
					$include_summarized = false;
					$tmp = 0;
					$JSDbSOV->selectNumberOfVisitorsForYMD( $visitors_type, $include_summarized, $buid, $year, $month, $day, $tmp );
					$nivpurge = $niv - $tmp;//in previus query $include_summarized was true! (if not, show_summarized will be false and this code will not be executed)
					$tnivpurge += $nivpurge;
				}
			}
			
			{// unique not identified visitors
				$visitors_type = _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR;
				$date_from = $year .'-'. $month .'-'. $day;
				$date_to   = $year .'-'. $month .'-'. $day;
				$JSDbSOV->selectNumberOfUniqueVisitors( $visitors_type, $this->JSConf->include_summarized, $buid, $date_from, $date_to, $univ );
				$tuniv += $univ;

		    	if( $this->JSConf->show_summarized ) {
					$include_summarized = false;
					$tmp = 0;
					$JSDbSOV->selectNumberOfUniqueVisitors( $visitors_type, $include_summarized, $buid, $date_from, $date_to, $tmp );
					$univpurge = $univ - $tmp;//in previus query $include_summarized was true! (if not, show_summarized will be false and this code will not be executed)
					$tunivpurge += $univpurge;
				}
			}

						
			// sums
			$sum  = $v  + $b  + $niv;
			$usum = $uv + $ub + $univ; 
			$tsum  += $sum;
			$tusum += $usum;


			
			// now we have all values, now draw the row (day)
			if( date( 'w', strtotime( "$this->y-$this->m-$i" ) ) == 6 ) {
				$cls = 'row0'; // info: background-color: #F9F9F9;
			}elseif (date( 'w', strtotime( "$this->y-$this->m-$i" ) ) == 0 ) {
				$cls = 'row2" style="background-color:#efefef; border-bottom: 1px dotted #ff0000';
			}else{
				$cls = 'row1'; // info: background-color: #F1F1F1;
			}

			$retval .= '<tr class="' . $cls . '">' . "\n" . '<td align="center">';

			if( strlen( $i ) == 1 ) {
				$retval .= '0' . $i;
			}else{
				$retval .= $i;
			}

			// show also purged data
			if( $this->JSConf->include_summarized ) {
				$add = null;
			    if ( $vpurge ) {
                    $add['vpurge'] = sprintf( $this->add_dstyle, $vpurge );
			    }
                if( $uvpurge ) {
                    $add['uvpurge'] = sprintf( $this->add_dstyle, $uvpurge );
                }
                if( $ppurge ) {
                    $add['ppurge'] = sprintf( $this->add_dstyle, $ppurge );
                }
                if( $ubpurge ) {
                    $add['ubpurge'] = sprintf( $this->add_dstyle, $ubpurge );
                }
                if( $bpurge ) {
                    $add['bpurge'] = sprintf( $this->add_dstyle, $bpurge );
                }
            }

            $retval .= '</td>'
			. '<td align="right">' . ( $uv ? $uv : '.' ) . '</td>'
			. '<td align="left">' . ( !empty( $add['uvpurge'] ) ? $add['uvpurge'] : '&nbsp;' ) . '</td>'
			. '<td align="right">'
			. ( $v ? '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; SelectDay('.$i.');submitbutton(\'r03\');" title="' . JTEXT::_( 'Click for visitors details' ). '">' . $v . '</a>' : '.')
			. '</td>'
			. '<td align="left">'
			. ( !empty( $add['vpurge'] ) ? '&nbsp;<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; SelectDay('.$i.');submitbutton(\'r03\');" title="'
				. JTEXT::_( 'Click for visitors details' ) . '">'
				. $add['vpurge'] . '</a>' : '&nbsp;' )
			. '</td>'
			. '<td align="center">';

			if( ( $uv != 0 ) && ( $v != 0 ) ) {
				$retval .= number_format( round( ( $v / $uv ), 1 ), 1 );
			}else{
				$retval .= '.';
			}

			$retval .= '</td>'
			. '<td align="right">'
			. ( $p ? '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; SelectDay('.$i.');submitbutton(\'r06\');" title="'
				. JTEXT::_( 'Click for page details' ) . '">' . $p . '</a>' : '.' )
			. '</td><td align="left">'
			. ' ' . ( !empty( $add['ppurge'] ) ? $add['ppurge'] : '&nbsp;' )
			. '</td>'
			. '<td align="center">'
			. ( $r ? '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; SelectDay('.$i.');submitbutton(\'r10\');" title="'
				. JTEXT::_( 'Click for referrer details' ) . '">' . $r . '</a>' : '.' ) . '</td>'
			. '<td align="right">' . ( $ub ? $ub : '.' ) . '</td>'
			. '<td align="left">' . ' ' . ( !empty( $add['ubpurge'] ) ? $add['ubpurge'] : '&nbsp;' ) .'</td>'
			. '<td align="right">'
			. ( $b ? '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; SelectDay('.$i.');submitbutton(\'r09\');" title="'
				. JTEXT::_( 'Click for additional details' ) . '">' . $b . '</a>' : '.' ). '</td>'
			. '<td>' . ( !empty( $add['bpurge'] ) ? $add['bpurge'] : '&nbsp;' ) . '</td>'
			. '<td>' . ( ($univ) ? ($univ . ' ['.$univpurge .']') : '.' ) . '</td>'
			. '<td>' . ( ($niv) ? '<a href="javascript:SelectDay('.$i.');submitbutton(\'r11\');" title="' . JTEXT::_( 'Click for additional details' ) . '">' . $niv . '</a> ['.$nivpurge .']' : '.' ) . '</td>'
			. '<td>' . ( $usum ? $usum : '.' ) . '</td>'
			. '<td>' . ( $sum ? $sum : '.' ) . '</td>'
			. '</tr>' . "\n";
		}

		// Get the values for the totals line
		// RB: values acuired higher in this function are wrong. check these and remove them
		// RB: change to new database methode

		// get Total Unique visitors
		$this->resetVar( 1 );

		$query = 'SELECT count(*)'
		. ' FROM #__jstats_visits'
		. ' LEFT JOIN #__jstats_ipaddresses ON (#__jstats_visits.ip_id=#__jstats_ipaddresses.id)'
		. ' WHERE #__jstats_ipaddresses.type = 1'
		. ' AND #__jstats_visits.month = \'' . $this->m . '\''
		. ' AND #__jstats_visits.year = \'' . $this->y . '\''
		. ' GROUP BY #__jstats_visits.ip_id'
		;
		$this->resetVar( 0 );
		$this->db->setQuery( $query );
		$rows_tuv = $this->db->loadObjectList();
		$tuv = count( $rows_tuv );

		// get Total Unique bots
		$this->resetVar( 1 );

		$query = 'SELECT count(*)'
		. ' FROM #__jstats_visits'
		. ' LEFT JOIN #__jstats_ipaddresses ON (#__jstats_visits.ip_id=#__jstats_ipaddresses.id)'
		. ' WHERE #__jstats_ipaddresses.type = 2'
		. ' AND #__jstats_visits.month = \'' . $this->m . '\''
		. ' AND #__jstats_visits.year = \'' . $this->y . '\''
		. ' GROUP BY #__jstats_ipaddresses.browser'
		;
		$this->resetVar( 0 );
		$this->db->setQuery( $query );
		$rows_tub = $this->db->loadObjectList();
		$tub = count( $rows_tub );




		// Display the totals line
		if( $this->JSConf->include_summarized ) {
			// include purged data
			if( $tvpurge ) {
				$add['tvpurge'] = sprintf( $this->add_style, $tvpurge );
			}
			if( $tuvpurge ) {
				$add['tuvpurge'] = sprintf( $this->add_style, $tuvpurge );
			}
			if( $tppurge ) {
				$add['tppurge'] = sprintf( $this->add_style, $tppurge );
			}
			if( $tubpurge ) {
				$add['tubpurge'] = sprintf( $this->add_style, $tubpurge );
			}
			if( $tbpurge ) {
				$add['tbpurge'] = sprintf( $this->add_style, $tbpurge );
			}
		}
		
		$MonthsNamesTruntucatd = $JSUtil->getMonthsNamesTruntucatd();


		$retval .= '<tr>'
			// Day
		. '<th align="center">' . $MonthsNamesTruntucatd[$this->m] . '</th>'
			// Unique visitors
		. '<th align="right">' . $tuv . '</th>'
		. '<th align="left">' . ( !empty( $add['tuvpurge'] ) ? $add['tuvpurge'] : '&nbsp;' ) . '</th>'
			// Number of visits
		. '<th align="right">' . $tv . '</th>'
		. '<th align="left">' . ( !empty( $add['tvpurge'] ) ? $add['tvpurge'] : '&nbsp;' ) . '</th>'
			// Visits average
		. '<th align="center">';

		if( ( $tuv != 0 ) && ( $tv != 0 ) ) {
			$retval .= number_format( round( ( $tv / $tuv ), 1 ), 1 );
		}else{
			$retval .= '0.0';
		}
		$retval .= '</th>'
			// Pages
		. '<th align="right">' . $tp . '</th>'
		. '<th align="left">' . ( !empty( $add['tppurge'] ) ? $add['tppurge'] : '&nbsp;' ) . '</th>'
			// Referrers
		. '<th align="center">' . $tr . '</th>'
			// Unique bots
		. '<th align="right">'. $tub . '</th>'
		. '<th align="left">' . ( !empty( $add['tubpurge'] ) ? $add['tubpurge'] : '&nbsp;' ) . '</th>'
			// Number of bots
		. '<th align="right">' . $tb . '</th>'
		. '<th align="left">' . ( !empty( $add['tbpurge'] ) ? $add['tbpurge'] : '&nbsp;') .'</th>'
		. '<th>' . $tuniv . ' ['.$tunivpurge .']</th>'
		. '<th>' . $tniv . ' ['.$tnivpurge .']</th>'
		. '<th>' . $tusum . '</th>'
		. '<th>' . $tsum . '</th>'
		. '</tr>' . "\n"
		. '</table>' . "\n";

		return $retval;
	}

	/**
	 * shows more infos for a selected visitor
	 * case r03
	 *
	 * vid = nr of 1 selected visitor
	 * @return string
	 */
	function VisitInformation() {
		global $mainframe, $option;

		$this->_getDB();

		$JSTemplate = new js_JSTemplate();
		$JSSystemConst = new js_JSSystemConst();
		$JSUtil = new js_JSUtil();

		$JSTemplate->jsLoadToolTip();


		$retval = '';
		$where	= array();

		$limit		= intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit')));
        $limitstart	= intval($mainframe->getUserStateFromRequest("viewlimitstart", 'limitstart', 0));
		$search		= $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
		$search		= $this->db->getEscaped( trim( strtolower( $search ) ) );
		//$this->vid		= intval($mainframe->getUserStateFromRequest("vid", 'vid', 0));
		//$uid		= intval($mainframe->getUserStateFromRequest("uid", 'uid', 0));

		if( $this->_debug() ) {
			echo '<div class="debug" style="float:left; width:98%; padding:5px; margin: 5px; border-top: 2px solid #FEFF9F; border-bottom: 2px solid #FEFF9F; background-color: #FFFFF6; text-align:left;">'
			. '<strong>DEBUG info from JoomlaStats:</strong><br/>'
			. 'search [' . $search . ']<br />'
			. 'option ['. $option . ']<br />'
			. 'dmy [' . $this->d . '|' . $this->m . '|' . $this->y . ']<br />'
			. 'vid ['. $this->vid . ']'
			. '</div><div style="clear:both"></div>'
			;
		}

		$this->resetVar( 1 );

		// display table title
		if( $this->vid ) {
			// display username instead of userid
			$query = 'SELECT name'
			. ' FROM #__users'
			. ' WHERE id = ' . $this->vid
			;
            $this->db->setQuery( $query ); // mic: was SetQuery here!!
            $name = $this->db->LoadResult();

			$retval .= '<div id="selection" class="selection" style="background-color:#FFFFF6; margin:3px; padding:3px;">'
			. JTEXT::_( 'Remove selection' )
			. ': <a href="javascript:document.adminForm.vid.value=\'0\';submitbutton(\'r03\');"'
			. ' title="' . JTEXT::_( 'Remove selection' ) . '">' . $name . '</a></div>';
		}

		$where[] = 'a.type = 1';		// RoBo: only display real visitors
		$where[] = 'c.day LIKE \'' . $this->d . '\'';
		$where[] = 'c.month LIKE \'' . $this->m . '\'';
		$where[] = 'c.year LIKE \'' . $this->y . '\'';

		/* mic: show only actual data (without already archived/purged)
		 * a.table : jstats_ipadresses
		 * c.table : jstats_visits
		 */
		if( !$this->JSConf->include_summarized ) {
			$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
		}

		//RB: todo: add also username to the search >> mic: table users is NOT in query!! @todo: add users table
		if( $search ) {
			$where[] = '(a.ip LIKE \'%' . $search . '%\''
			. ' OR LOWER(a.browser) LIKE \'%' . $search . '%\''
			. ' OR LOWER(a.system) LIKE \'%' . $search . '%\''
			. ' OR LOWER(a.nslookup) LIKE \'%' . $search . '%\''
			. ' OR LOWER(b.tld) LIKE \'%' . $search . '%\''
			. ' OR LOWER(b.fullname) LIKE \'%' . $search . '%\''
			. ' OR c.time LIKE \'%' . $search . '%\')'
			;
			//RB: is LOWER needed? 'like' should check case insensitive? mic: NO, like IS case sensitive!
		}

		if( $this->vid != 0 ) {
			$where[] = 'c.userid = ' . $this->vid;
		}

		// select total
		$query = 'SELECT COUNT(*)'
		. ' FROM #__jstats_ipaddresses AS a'
		. ' LEFT JOIN #__jstats_topleveldomains AS b ON a.tld = b.tld'
		. ' LEFT JOIN #__jstats_visits AS c ON a.id = c.ip_id'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$this->db->setQuery ($query );
		$total = $this->db->loadResult();

		if( $this->_debug() ) {
			echo '<br />DEBUG info JoomlaStats:<br />';
		    echo 'query: ' . $query . '<br />';
            echo 'total: ' . $total . '<br />';
        }

		if( js_getJoomlaVesrion_IsJ15x() == true ) {
			jimport( 'joomla.html.pagination' );
			$pagination = new JPagination( $total, $limitstart, $limit );
		}else{
			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			$pagination = new mosPageNav( $total, $limitstart, $limit );
		}
		js_echoJSDebugInfo('', $pagination);

		$query  = 'SELECT a.id AS aid, a.tld, a.nslookup, a.system, a.browser, a.ip, a.exclude, b.fullname, c.userid, c.time,c.id'
		. ' FROM #__jstats_ipaddresses AS a'
		. ' LEFT JOIN #__jstats_topleveldomains AS b ON a.tld = b.tld'
		. ' LEFT JOIN #__jstats_visits AS c ON a.id = c.ip_id'
		. ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' )
		. ' ORDER BY c.time DESC'
		;
		$this->db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $this->db->loadObjectList();

		$this->resetVar( 0 );

		if( $this->_debug() ) {
			echo '<br />DEBUG info JoomlaStats:<br />';
			echo 'query:<br />' . $query;
		}

        $cblink = '';
		$path_to_com_profiler = '';
		if( js_getJoomlaVesrion_IsJ15x() == true )
			$path_to_com_profiler = JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_profiler' .DS. 'admin.comprofiler.php';
		else
			$path_to_com_profiler = $GLOBALS['mosConfig_absolute_path'] .DIRECTORY_SEPARATOR. 'administrator' .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_profiler' .DIRECTORY_SEPARATOR. 'admin.comprofiler.php';

        if( file_exists( $path_to_com_profiler ) ) {
            // Community Builder userlink
            // index2.php?option=com_comprofiler&task=edit&cid=xx
            $cblink =
            '&nbsp;'
            . '<a target="popup" href="'
            . $this->_index() . '?option=com_comprofiler&task=edit&cid=%s' // . $row->userid
            . '&amp;hidemainmenu=1"' // &amp;no_html=1 // mic: optional, but should then opened with own css!
            . ' onclick="window.open(\'\',\'popup\''
            . ',\'resizable=yes,status=no,toolbar=no,location=no,scrollbars=yes,width=690,height=560\')"'
            . ' title="' . JTEXT::_( 'Click to view profile' ) . '">'
            . '<img src="'. _JSAdminImagePath .'person1.png" border="0" height="15" width="15" /></a>';
        }

        $notLoggedIn = '<img src="'. _JSAdminImagePath .'disconnect.png" border="0" height="15" width="15"'
        . ' title="' . JTEXT::_( 'Not logged in' ) . '"'
        . '/>';

        // $vid
        $pathInfo = '<a href="javascript:document.adminForm.moreinfo.value=\'%s\';submitbutton(\'r03b\');">'
        . '<img src="'. _JSAdminImagePath .'pathinfo.png" border="0" height="15" width="15"'
        . ' title="' . JTEXT::_( 'Path info' ) . '"'
        . '/>' . '</a>';

        $whois = '<a target="popup" href="'
        . $this->_index() . '?option=com_joomlastats&amp;task=whois&amp;host=%s'
        . '&amp;no_html=1"'
        . ' onclick="window.open(\'\',\'popup\''
        . ',\'resizable=yes,status=no,toolbar=no,location=no,scrollbars=yes,width=690,height=560\')"'
        . ' title="'. JTEXT::_( 'WHOIS query' ) .'">'
        . '<img src="'. _JSAdminImagePath .'whois.png" border="0" height="15" width="15" /></a>';

		$retval .= '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>'
		. '<th align="left">' . JTEXT::_( 'Time' ) . '</th>'
		. '<th align="left">'.JTEXT::_( 'Username' ).'</th>'
		. '<th align="left">' . JTEXT::_( 'TLD' ) . '</th>'
		. '<th align="left">' . JTEXT::_( 'Country' ) . '</th>'
		. '<th align="left">'.JTEXT::_( 'IP' ).'</th>'
		. '<th align="left">' . JTEXT::_( 'NS-Lookup' ) . '</th>'
		. '<th colspan="2" align="left">' . JTEXT::_( 'Pages' ) . '</th>'
		// . '<th align="left">&nbsp;</th>'									// Pathinfo
		. '<th align="left">' . JTEXT::_( 'OS' ) . '</th>'
		. '<th align="left">' . JTEXT::_( 'Browser' ) . '</th>'
		. '<th align="left">'.JTEXT::_( 'Actions' ).'</th>'
		. '</tr>' . "\n";

		if( $rows ) {
			$k = 0;
			$n = count( $rows );

			$browser_name_to_image_arr = array();//@todo this is hack to get image name. Database should be redesigned!
			{
				$query  = 'SELECT browser_fullname, browser_img FROM #__jstats_browsers';
				$this->db->setQuery($query);
				$rowst = $this->db->loadObjectList();
				
				foreach($rowst as $rowt) {
					$browser_name_to_image_arr[$rowt->browser_fullname] = $rowt->browser_img;
				}
			}
			
			$os_name_to_image_arr = array();//@todo this is hack to get image name. Database should be redesigned!
			{
				$query  = 'SELECT sys_fullname, sys_img FROM #__jstats_systems';
				$this->db->setQuery($query);
				$rowst = $this->db->loadObjectList();
				
				foreach($rowst as $rowt) {
					$os_name_to_image_arr[$rowt->sys_fullname] = $rowt->sys_img;
				}
			}
					
			for( $i = 0; $i < $n; $i++ ) {
				$row = &$rows[$i];
                $vid = $row->id;

                // for excluding user
		        $img	= $row->exclude ? 'tick.png' : 'publish_x.png';
		        $task   = $row->exclude ? 'js_do_ip_include' : 'js_do_ip_exclude';
		        $alt    = $row->exclude ? JTEXT::_( 'Click to include' ) : JTEXT::_( 'Click to exclude' );

				$query = 'SELECT count(*) AS count'
				. ' FROM #__jstats_page_request'
				. ' WHERE #__jstats_page_request.ip_id = ' . $vid
				;
                $this->db->setQuery( $query );
				$count = $this->db->loadResult();

				// display username instead of userid
				$name = '';
				if( $row->userid ) {
					$query = 'SELECT name'
					. ' FROM #__users'
					. ' WHERE id = ' . $row->userid
					;
	                $this->db->setQuery( $query ); // mic: was SetQuery !!!
	                $name = $this->db->LoadResult();
				}

				$ulink = ( isJ15() ? '?option=com_users&amp;view=user&amp;task=edit&amp;hidemainmenu=1&amp;cid[]=' : '?option=com_users&amp;task=editA&amp;hidemainmenu=1&amp;id=' );

				// select after user
				$userlink =
				'<a href="javascript:document.adminForm.vid.value=\'' . $row->userid . '\';submitbutton(\'r03\');'
		        . '" title="%s - ' . JTEXT::_( 'Click to select after this user' ) . '">'
		        . ( ( strlen( $name ) > 12 ) ? '<span class="editlinktip hasTip" title="' . $name . '">' . substr( $name, 0, 10 ) . '</span>' : $name )
		        .'</a>'
		        
				// Joomla userlink
				. '&nbsp;'
				. '<a target="popup" href="'
		        . $ulink . $row->userid . '"'
		        . ' onclick="window.open(\'\',\'popup\''
		        . ',\'resizable=yes,status=no,toolbar=no,location=no,scrollbars=yes,width=690,height=560\')"'
		        . ' title="'. JTEXT::_( 'Click to view profile' ) .'">'
		        . '<img src="'. _JSAdminImagePath .'person1.png" border="0" /></a>';

		        $html_tld_img = '<img src="'.$JSUtil->getImageWithUrl($row->tld, $JSSystemConst->defaultPathToImagesTld).'" border="0" />';
	
				$retval .= '<tr class="row' . $k . '"'
				. ( $count ? '' : ' style="color:#666666; background-color:#EFFFFF"'
				. ' title="' . JTEXT::_( 'Data already purged' ) . '"' ) . '>'
				. '<td align="left" nowrap="nowrap">' . $row->time . '</td>'
				. '<td align="left" nowrap="nowrap">'
                . ( $name ? sprintf( $userlink, $row->userid ) . ( $cblink ? sprintf( $cblink, $row->userid ) : '' ) : $notLoggedIn) // JTEXT::_( 'Not logged in' ) )
                . '</td>'
				. '<td align="left" nowrap="nowrap">.' . $row->tld . '</td>'
                . '<td align="left" nowrap="nowrap">' . $html_tld_img . ' '
                . ( $row->ip == '127.0.0.1' ? JTEXT::_( 'Local' ): JTEXT::_( $row->tld ) ) .'</td>' // this output translated $row->fullname
                . '<td align="left" nowrap="nowrap">' . $row->ip . '</td>'
                . '<td align="left">';

                if( strlen( $row->nslookup ) > 20 ) {
                    //$retval .= '<acronym title="' . $row->nslookup . '">'
                    $retval .= '<span class="editlinktip hasTip" title="' . $row->nslookup . '">'
                    . substr( $row->nslookup, 0, 19 )
                    //. '<strong style="color:#FF0000">&raquo;</strong>'
					//. '</acronym>';
					. '</span>'
					. '<strong style="color:#FF0000">&raquo;</strong>';
				}else{
                	$retval .= $row->nslookup;
				}


	        	$html_browser_img = '<img src="'.$JSUtil->getImageWithUrl('unknown', $JSSystemConst->defaultPathToImagesBrowser).'" border="0" />';
	        	{
		        	$brow_with_ver = $row->browser;//could be also without version
					if (isset($browser_name_to_image_arr[$brow_with_ver])) {
		        		$html_browser_img = '<img src="'.$JSUtil->getImageWithUrl($browser_name_to_image_arr[$brow_with_ver], $JSSystemConst->defaultPathToImagesBrowser).'" border="0" />';
	        		} else {
		        		$pos = strrpos( $brow_with_ver, ' ' );
		        		if( $pos !== false ) {
		        			$brow_without_ver = substr($brow_with_ver, 0, $pos);//could be also broken name
							if (isset($browser_name_to_image_arr[$brow_without_ver])) {
				        		$html_browser_img = '<img src="'.$JSUtil->getImageWithUrl($browser_name_to_image_arr[$brow_without_ver], $JSSystemConst->defaultPathToImagesBrowser).'" border="0" />';
			        		}
		        		}
		        		
	        		}
        		}

		        	
	        	$html_os_img = '<img src="'.$JSUtil->getImageWithUrl('unknown', $JSSystemConst->defaultPathToImagesOs).'" border="0" />';
				if (isset($os_name_to_image_arr[$row->system]))
		        	$html_os_img = '<img src="'.$JSUtil->getImageWithUrl($os_name_to_image_arr[$row->system], $JSSystemConst->defaultPathToImagesOs).'" border="0" />';
		        	
				
				// mic: *** placeholder for archived/purged items
				$retval .= '</td>'
                . '<td align="left" nowrap="nowrap">'
                . ( $count ? '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.moreinfo.value=\'' . $vid . '\';submitbutton(\'r03a\');"'
                . ' title="' . JTEXT::_( 'Click for additional details' ) . '">' . $count . '</a>' : '***' )
                . '</td>'
                . '<td align="left" nowrap="nowrap">'
                //. ( $count ? '<a title="' . JTEXT::_( 'Path info' )
                //. '" href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.moreinfo.value=\''
                //. $vid . '\';submitbutton(\'r03b\');">'
                //. JTEXT::_( 'Path info' ) . '</a>' : '***' )
                . ( $count ? sprintf( $pathInfo, $vid ) : '***' )
                . '</td>'
                . '<td align="left" nowrap="nowrap">' . $html_os_img . ' ' . $row->system . '</td>'
                . '<td align="left" nowrap="nowrap">' . $html_browser_img . ' ' . $row->browser . '</td>'
                . '<td>'
            	. '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.vid.value=\''
            	. $row->aid . '\';submitbutton(\'' . $task . '\');" title="' . $alt . '">'
            	. '<img src="images/' . $img . '" width="12" height="12" border="0" alt="' . $alt . '" /></a>'
            	. ( ( $row->nslookup && ( $row->nslookup != '127.0.0.1' && $row->nslookup != 'localhost' ) )
            		? '&nbsp;' . sprintf( $whois, $row->nslookup )
            		: ( ( $row->ip && $row->ip != '127.0.0.1' ) ? '&nbsp;' . sprintf( $whois, $row->ip ) : '' )
            	)
            	. '</td>'
            	. '</tr>' . "\n";

				$k = 1 - $k;
			}
		}else{
           	$retval .= '<tr><td colspan="12" style="text-align:center">'
           	. JTEXT::_( 'No data' )
          	. '</td></tr>' . "\n";
        }

        $retval .= '<tr><td colspan="12">'
        . $pagination->getListFooter()
		. '</td></tr>' . "\n"
        . '</table>' . "\n";

		return $retval;
	}

	/**
	 * show visitors by country/TLD
	 *
	 * case r05
	 * @return string
	 */
	function getVisitorsByTld() {
		global $mainframe;
		global $option;

		$this->_getDB();

		require_once( dirname(__FILE__) .DS. 'util.classes.php' );
		require_once( dirname(__FILE__) .DS. 'base.classes.php' );

		$JSUtil = new js_JSUtil();
		$JSSystemConst = new js_JSSystemConst();


        // mic: search not activated as of 2006.12.23, prepared for later
        //$search		= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
        //$search		= $this->db->getEscaped( trim( strtolower( $search ) ) );

		$this->resetVar( 1 );

		$where   = array();
		$where[] = 'a.type = 1';
		$where[] = 'c.day LIKE \'' . $this->d . '\'';
		$where[] = 'c.month LIKE \'' . $this->m . '\'';
		$where[] = 'c.year LIKE \'' . $this->y . '\'';

		/* mic: show only actual data (without already archived/purged)
		 * a.table : jstats_ipadresses
		 * c.table : jstats_visits
		 */
		if( !$this->JSConf->include_summarized ) {
			$where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
		}

		$query = 'SELECT count(*) AS numbers, a.tld, b.fullname'
		. ' FROM #__jstats_ipaddresses AS a'
		. ' LEFT JOIN #__jstats_topleveldomains AS b ON a.tld = b.tld'
		. ' LEFT JOIN #__jstats_visits AS c ON a.id = c.ip_id'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		. ' GROUP BY a.tld'
		. ' ORDER BY numbers DESC, b.fullname ASC'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();

		$total = 0;
		$max_value = 0;
		$sum_all_values = 0;
		if ( $rows ) {
			$total = count( $rows );

            foreach( $rows as $row ) {
                $sum_all_values   += $row->numbers;

                if( $row->numbers > $max_value ) {
                    $max_value = $row->numbers;
                }
            }
		}

		$this->resetVar( 0 );
		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();

		$retval  = '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>'
		. '<th align="left"	width="2%">' . JTEXT::_ ('Flag' ) . '</th>'
		. '<th align="left" width="3%">' . JTEXT::_( 'Code' ) . '</th>'
		. '<th align="center" width="10%" nowrap="nowrap" title="' . JTEXT::_( 'Number of visitors' ) .'">' . JTEXT::_( 'Visitors' ) . '</th>'
		. '<th align="left"	width="20%">' . JTEXT::_( 'Percent' ) . '</th>'
		. '<th align="left"	width="65%">' . JTEXT::_( 'Country/Domain' ) . '</th>'
		. '</tr>' . "\n";

		if( $rows ) {

		    $k		= 0;
            foreach( $rows as $row ) {

				$style = '';
				if( $row->tld == '' ) {
					$style = ' style="background-color:#FFEFEF;"';
				}

                $retval .= '<tr class="row' . $k . '"' . $style . '>' . "\n"
                . '<td align="center"><img src="';
                
                if( $row->tld == '' ) {
                    $retval .= $JSUtil->getImageWithUrl('unknown', $JSSystemConst->defaultPathToImagesTld) .'"'
                    . ' alt="' . JTEXT::_( 'Unknown' ) . '"'
                    . ' title="' . JTEXT::_( 'Unknown' ) . '"';
                }else{
                    $retval .= $JSUtil->getImageWithUrl($row->tld, $JSSystemConst->defaultPathToImagesTld) . '"'
                    . ' alt="'. $row->tld .'"'
                    . ' title="'. $row->tld .'"';
                }


                $retval .= '" />'
				. '</td>'
        		. '<td align="left">&nbsp;' . $row->tld . '</td>'
        		. '<td align="center">&nbsp;' . $row->numbers . '</td>'
        		. '<td align="left">' . $JSStatisticsCommonTpl->getPercentBarWithPercentNbr( $row->numbers, $max_value, $sum_all_values ) . '</td>'
                . '<td align="left">&nbsp;'
                . ( ( ( $row->tld == 'localhost' ) || $row->tld == '127.0.0.1' )
                	? JTEXT::_( 'Local' )
                	: ( $row->tld ? JTEXT::_( $row->tld ) : '<span style="color:#FF0000;">' . JTEXT::_( 'Unknown' ) . '</span>' ) ) // $row->fullname
                . '</td>'
                . '</tr>' . "\n";

				$k = 1 - $k;
            }
        }else{
        	$retval .= '<tr>' . "\n"
        	. '<td colspan="5" style="text-align:center">'
        	. JTEXT::_( 'No data' )
        	. '</td></tr>' . "\n";
        }

		$retval .='<tr>';

		if( $total == 0 ) {
			$retval .= '<th colspan="5" align="left">&nbsp;' . JTEXT::_( 'No countries/domains' ) . '</th>';
		} else {
			$retval .= ''
			. '<th colspan="2">' . JTEXT::_( 'Total' ) . '</th>'
			. '<th align="center">' . $sum_all_values . '</th>'
			. '<th>&nbsp;</th>'
			. '<th align="left">'
				. $total . '&nbsp;'
				. ( $total == 1 ? JTEXT::_( 'Country' ) : JTEXT::_( 'Countries' ) )
			. '</th>';
		}

		$retval .= '</tr></table>' . "\n";

		return $retval;
	}


		
	/**
	 * show browsers
	 * case r08
	 *
	 * @return string
	 */
	function getBrowsers() {

		$this->_getDB();

		$where			= array();
		$totalbrowsers 	= 0;
		$totalnmb		= 0;
		$totalmax 		= 0;

		$this->resetVar(1);

		$where[] = 'c.ip_id = a.id';
		$where[] = 'a.type = 1';
		$where[] = 'c.day LIKE \'' . $this->d . '\'';
		$where[] = 'c.month LIKE \'' . $this->m . '\'';
		$where[] = 'c.year LIKE \'' . $this->y . '\'';

		/* mic: show only actual data (without already archived/purged)
		 * a.table : jstats_ipadresses
		 * c.table : jstats_visits
		 */
		if( !$this->JSConf->include_summarized ) {
			$where[] = 'a.id = c.ip_id AND c.id >= ' . $this->buid();
		}

		$query = 'SELECT a.browser, count(*) numbers'
		. ' FROM #__jstats_ipaddresses AS a, #__jstats_visits AS c'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		. ' GROUP BY a.browser'
		. ' ORDER BY numbers DESC, a.browser ASC'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();

		$this->resetVar( 0 ); // mic: why is this crazy thing here ?????

		if( count( $rows ) > 0 ) {
			foreach( $rows as $row ) {
            	$totalbrowsers++;
                $totalnmb += $row->numbers;

            	if( $row->numbers > $totalmax ) {
                    $totalmax = $row->numbers;
            	}
        	}
		}

		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n"
		. '<tr>'
		. '<th width="8%">' . JTEXT::_( 'Count' ).'</th>'
		. '<th width="37%" colspan="2">' . JTEXT::_( 'Percent' ).'</th>'
		. '<th align="left" width="55%">' . JTEXT::_( 'Browser' ).'</th>'
		. '</tr>';

        if( $totalnmb != 0 ) {
        	$totalmaxpercent = round( ( ( $totalmax / $totalnmb ) * 100 ), 2 );
        	//??? How do I get fixed result not "1" but "1,0"   If i get this to work right align looks better.
        	// mic: who asks this? it is already shown 1.0
        	$k = 0;

			if( count( $rows ) > 0 ) {
            	foreach( $rows as $row ) {
            		$percent = round( ( ( $row->numbers / $totalnmb ) * 100 ), 2 );

            		$style = '';
					if( !$row->browser ) {
						$style = ' style="background-color:#FFEFEF"';
					}

        			$retval .= '<tr class="row' . $k . '"' . $style . '>'
					. '<td align="center" nowrap="nowrap">&nbsp;' . $row->numbers . '</td>'
					. '<td align="left" nowrap="nowrap">&nbsp;'
					. $this->PercentBar( $percent, $totalmaxpercent )
					. '</td><td style="text-align:right">'
					. number_format( $percent, 2, ',', '' ) . '%'
					. '</td>'
					. '<td align="left" nowrap="nowrap">&nbsp;' . $row->browser . '</td>'
					. '</tr>' . "\n";

					$k = 1 - $k;
				}
			}
		}

		// Summary Bar
		$retval .= '<tr><th align="center">&nbsp;' . $totalnmb . '</th>'
		. '<th>&nbsp;</th>'
		. '<th align="left" colspan="2">' . $totalbrowsers . '&nbsp;';

		if( $totalbrowsers != 0 ) {
			$retval .= ( $totalbrowsers == 1 ? JTEXT::_( 'Browsertyp' ) : JTEXT::_( 'Browsertypes' ) );
		}else{
			$retval .= JTEXT::_( 'Browsertyp' );
		}

		$retval .= '</th></tr>' . "\n"
		. '</table>' . "\n";

		return $retval;
	}

	/**
	 * Robots/Spiders
	 * case r09		overview
	 * for details see case r09a
	 *
	 * @return string
	 */
	function getBots() {
		global $mainframe;

		$this->_getDB();

		// $this->$dom is used as transfer variable for browser (is name of Bot)
		// $this->$vid is used as transfer variable for ip_id

		$where		= array();
		$do_bots	= 0; // 0: not doing bot || 1: do bots (overview of all Bots)

		$unknown = array(
			JTEXT::_( 'Unknown - Identified as robot' ),
			JTEXT::_( 'Unknown - Identified as crawler' ),
			JTEXT::_( 'Unknown - Identified as spider' ),
			JTEXT::_( 'Unknown - Identified as bot' )
		);

		if ($this->dom == '') {
			// If function not called before, then start with overview bots/spiders table
			$this->dom	= '%';
			$do_bots	= 1;
		}

		if( $this->vid == '' ) {
			$this->vid = '%';
		}else{
			$do_detailed = 1;
		}

		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();
		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n";

		if( $do_bots ) {
			// The first screen, list all bots
			$retval .= '<tr><th width="5%">' . JTEXT::_( 'Count' ) . '</th>'
			. '<th width="10%">' . JTEXT::_( 'Percent' ) . '</th>'
			. '<th align="left" width="85%">' . JTEXT::_( 'Bot/Spider' ) . '</th>'
			. '</tr>';

			$this->resetVar( 1 );

			$where[] = 'c.ip_id = a.id';
			$where[] = 'a.browser != \'\'';
			$where[] = 'a.type = 2';
			$where[] = 'c.day LIKE \'' . $this->d . '\'';
			$where[] = 'c.month LIKE \'' . $this->m . '\'';
			$where[] = 'c.year LIKE \'' . $this->y . '\'';

			/* mic: show only actual data (without already archived/purged)
		     * a.table : jstats_ipadresses
             * c.table : jstats_visits
             */
            if ( !$this->JSConf->include_summarized ) {
                $where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
            }

			$query = 'SELECT a.browser, count(*) numbers'
			. ' FROM #__jstats_ipaddresses AS a, #__jstats_visits AS c'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' GROUP BY a.browser'
			. ' ORDER BY numbers DESC, a.browser ASC'
			;
			$this->db->setQuery( $query );
			$rows = $this->db->loadObjectList();

			$total = 0;
			$max_value = 0;
			$sum_all_values = 0;
			if ( $rows ) {
				$total = count( $rows );
	
	            foreach( $rows as $row ) {
	                $sum_all_values   += $row->numbers;
	
	                if( $row->numbers > $max_value ) {
	                    $max_value = $row->numbers;
	                }
	            }
			}


			$this->resetVar( 0 ); // mic: i love it .....

            if( $sum_all_values != 0 ) {
				$k = 0;

				if( count( $rows ) > 0 ) {
					foreach( $rows as $row ) {

						$style = '';
						if( in_array( $row->browser, $unknown ) ) {
							$style = ' style="background-color:#FFEFEF"';
						}

						$retval .= '<tr class="row' . $k . '"' . $style . '>'
						. '<td align="center" nowrap="nowrap">&nbsp;' . $row->numbers . '</td>'
		        		. '<td align="left">' . $JSStatisticsCommonTpl->getPercentBarWithPercentNbr( $row->numbers, $max_value, $sum_all_values ) . '</td>'
                    	. '<td align="left" nowrap="nowrap">&nbsp;'
                    	. '<a title="' . JTEXT::_( 'Details' )
                    	. '" href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\''
                    	. rawurlencode( $row->browser ) . '\';submitbutton(\'r09\');">'
                    	. $row->browser
                    	. '</a></td>'
                    	. '</tr>' . "\n";

                    	$k = 1 - $k;
					}
				}
			}

			$retval .= '<tr><th style="text-align: center;">' . $sum_all_values . '</th>'
			.'<th>&nbsp;</th>'
			.'<th align="left">' . ( $total ? $total : '' ) . '&nbsp;';

			if( $total != 0 ) {
				$retval .= ( $total == 1 ? JTEXT::_( 'Bot' ) : JTEXT::_( 'Different Bots' ) );
			}else{
				$retval .= JTEXT::_( 'No Bots' );
			}

			$retval .= '</th></tr></table>' . "\n";
		}else{
			$this->resetVar( 1 );

			$where[] = 'a.browser LIKE \'' . $this->dom . '\'';
			$where[] = 'a.type = 2';
			$where[] = 'c.day LIKE \'' . $this->d . '\'';
			$where[] = 'c.month LIKE \'' . $this->m . '\'';
			$where[] = 'c.year LIKE \'' . $this->y . '\'';

			/* mic: show only actual data (without already archived/purged)
		     * a.table : jstats_ipadresses
             * c.table : jstats_visits
             */
            if( !$this->JSConf->include_summarized ) {
                $where[] = '(a.id = c.ip_id AND c.id >= ' . $this->buid() . ')';
            }

			$query  = 'SELECT a.tld, a.browser, a.useragent, b.fullname, c.time, c.id'
			. ' FROM #__jstats_ipaddresses AS a'
			. ' LEFT JOIN #__jstats_topleveldomains AS b ON a.tld = b.tld'
			. ' LEFT JOIN #__jstats_visits AS c ON c.ip_id = a.id'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' ORDER BY c.time DESC'
			;
			$this->db->setQuery( $query );
			$rows = $this->db->loadObjectList();

			$this->resetVar( 0 );

			if( $this->_debug() ) {
				echo 'DEBUG Info from JS:<br /><query:<br />';
				echo $query;
				echo '<br />*************************************<br />';
				echo 'result query [rows]:<br />';
				print_r( $rows );
			}

			// detail screen, list all visits from selected bot
			$retval .= '<tr><th width="3%" align="left">' . JTEXT::_( 'TLD' ) . '</th>'
			. '<th width="10%" align="left">' . JTEXT::_( 'Country/Domain' ) . '</th>'
			. '<th align="left">' . JTEXT::_( 'Pages' ) . '</th>'
			. '<th align="left">' . JTEXT::_( 'Time' ) . '</th>'
			. '<th width="75%" align="left">' . JTEXT::_( 'Bot name' ) . '</th>'
			. '</tr>' . "\n";

			$k = 0;
			foreach ( $rows as $row ) {
				$vid = $row->id;

				$query = 'SELECT count(*) AS count'
				. ' FROM #__jstats_page_request'
				. ' WHERE #__jstats_page_request.ip_id = ' . $vid
				;
				$this->db->setQuery( $query );
				$rowCount = $this->db->loadResult();

				$href = 'javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\''
				. rawurlencode( $this->dom ) . '\';document.adminForm.vid.value=\''
				. $vid . '\';submitbutton(\'r09a\');';

				$retval .= '<tr class="row' . $k . '"'
				. ( $rowCount ? '' : ' style="color:#666666" title="' . JTEXT::_( 'Data already purged' ) . '"' )
				. '>'
				. '<td style="text-align:left; white-space:nowrap">&nbsp;' . $row->tld . '</td>'
				. '<td style="text-align:left; white-space:nowrap">&nbsp;' . JTEXT::_( $row->tld ) . '</td>' // $row->fullname
				. '<td style="text-align:left; white-space:nowrap"'
				. ( $rowCount ? '>' . '<a title="' . JTEXT::_( 'Details' )
					. '" href="' . $href
					. '">' . $rowCount . '</a>' : '>***' )
				. '</td>'
				. '<td style="text-align:left; white-space:nowrap">' . $row->time . '</td>'
				. '<td style="text-align:left; white-space:nowrap">&nbsp;'
				. $row->browser . ( $row->useragent ? ' (' . substr( $row->useragent, 0, 70 ) . ')' : '' )
				. '</td>'
				. '</tr>' . "\n";

				$k = 1 - $k;
			}

			$retval .= '</table>'
			. '<div style="text-align:center; background-color:#ECECEC">[&nbsp;'
			// . '<a href="javascript:submitbutton(\'r09\');">' . JTEXT::_( 'Back' ) . '</a>'
			. '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\'\';document.adminForm.submit();submitbutton(\'r09\');">' . JTEXT::_( 'Back' ) . '</a>'
			. '&nbsp;]</div>';
		}

		return $retval;
	}

	/**
	 * show referrer
	 * case r10
	 *
	 * @return string
	 */
	function getReferrers() {
		global $mainframe;
		global $option;

		$this->_getDB();

		$limit		= intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg( 'list_limit')));
        $limitstart	= intval($mainframe->getUserStateFromRequest("viewlimitstart", 'limitstart', 0));
        // mic: search not activated as of 2006.12.23, prepared for later
        //$search		= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
        //$search		= $this->db->getEscaped( trim( strtolower( $search ) ) );

		$doreffererdomain 	= 0;
		$selector 			= 'referrer';
		$retval 			= '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>';

		if( $this->dom == '' ) {
			$doreffererdomain	= 1;
			$this->dom			= '%';
		}

		if( $doreffererdomain ) {
			$selector = 'domain';
		}

		$this->resetVar( 1 );

		$where   = array();
		$where[] = 'day LIKE \'' . $this->d . '\'';
        $where[] = 'month LIKE \'' . $this->m . '\'';
        $where[] = 'year LIKE \'' . $this->y . '\'';
        $where[] = 'domain LIKE \'' . $this->dom . '\'';

		$query = 'SELECT ' . $selector . ', count(*) counter'
		. ' FROM #__jstats_referrer'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
        . ' GROUP BY ' . $selector
        . ' ORDER BY counter DESC,' . $selector .' ASC'
        ;
		$this->db->setQuery( $query ); //, $pagination->limitstart, $pagination->limit );
		$rows = $this->db->loadObjectList();

		$total = 0;
		$max_value = 0;
		$sum_all_values = 0;
		if ( $rows ) {
			$total = count( $rows );

            foreach( $rows as $row ) {
                $sum_all_values   += $row->counter;

                if( $row->counter > $max_value ) {
                    $max_value = $row->counter;
                }
            }
		}
		
		if( js_getJoomlaVesrion_IsJ15x() == true ) {
			jimport( 'joomla.html.pagination' );
			$pagination = new JPagination( $total, $limitstart, $limit );
		}else{
			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			$pagination = new mosPageNav( $total, $limitstart, $limit );
		}
		
		$this->resetVar( 0 );
		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();

		if( $doreffererdomain ) {
			$retval .= '<th width="5%" nowrap="nowrap">' . JTEXT::_( 'Count' ) . '</th>'
			. '<th width="10%">' . JTEXT::_( 'Percent' ) . '</th>'
			. '<th align="left" width="85%">' . JTEXT::_( 'Referrer domain' ) . '</th>'
			. '</tr>';

			if( count( $rows ) > 0 ) {
				if( $sum_all_values != 0 ) {
					$k					= 0;

					//foreach( $rows AS $row ){
					$end	= $limitstart + $limit;
					$n		= count( $rows );

					for( $ii = 0; $ii < $n; $ii++ ) {
						$row = &$rows[$ii];

						if( $ii >= $limitstart && $ii <= $end ) {

	                        $retval .= '<tr class="row' . $k . '">' . "\n"
	            	    	. '<td align="center" nowrap="nowrap">&nbsp;' . $row->counter . '</td>'
			        		. '<td align="center">' . $JSStatisticsCommonTpl->getPercentBarWithPercentNbr( $row->counter, $max_value, $sum_all_values ) . '</td>'
	                		. '<td nowrap>&nbsp;'
	                    	. '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\''
	                		. $row->$selector . '\'; document.adminForm.limitstart.value=0; submitbutton(\'r10\');"'
	                		. ' title="' . JTEXT::_( 'Click to view referring page' ) . '"'
	                		. '>' . $row->$selector . '</a>'
	                		. '</td></tr>' . "\n";

	                        $k = 1 - $k;

	                        if( $ii + 1 >= $end ) {
	                            break;
	                        }
	                    }
					}
				}else{
	                $retval .= '<tr><td colspan="4" style="text-align:center">'
	    	    	. JTEXT::_( 'No data' )
	        		. '</td></tr>' . "\n";
	            }

				$retval .= '<tr><th style="text-align:center">' . $sum_all_values . '</th>'
				. '<th>&nbsp;</th>'
				. '<th align="left">';

				if( $total > 0 ) {
					$retval .= '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\'\'; submitbutton(\'r10\');">'
					. JTEXT::_( 'View all referring websites' ) . '[ ' . $total . ' ]' . '</a>';
				}else{
					$retval .= JTEXT::_( 'No referring domains' );
				}

				$retval .= '</th></tr>' . "\n";
			}else{
				$retval .= '<tr><td colspan="4" style="text-align:center">'
                . JTEXT::_( 'No data' )
                . '</td></tr>' . "\n";
			}
		}else{
			// do referrer page
			// shows each link which refers
			$retval .= '<th width="5%" nowrap="nowrap">' . JTEXT::_( 'Count' ) . '</th>'
			. '<th width="10%">' . JTEXT::_( 'Percent' ) . '</th>'
			. '<th align="left" width="85%">' . JTEXT::_( 'Referrer page' ) . '</th>'
			. '</tr>';

			if( count( $rows ) > 0 ) {
				
				if( $sum_all_values != 0 ) {
					$end 				= $limitstart + $limit;
					$k					= 0;
					$n					= count( $rows );

					for( $ii = 0; $ii < $n; $ii++ ) {
						$row = &$rows[$ii];

						if( $ii >= $limitstart && $ii <= $end ) {

	                        $retval .= '<tr class="row'. $ii .'">'
	                		. '<td align="center" nowrap="nowrap">&nbsp;'. $row->counter .'</td>'
			        		. '<td align="center">' . $JSStatisticsCommonTpl->getPercentBarWithPercentNbr( $row->counter, $max_value, $sum_all_values ) . '</td>'
	                		. '<td nowrap="nowrap">'
	                		. '<a href="'. $row->referrer .'" target="_blank" title="'
	                		. JTEXT::_( 'Opens URL in new window' ) . '">' . $row->$selector . '</a>'
	                		. '</td></tr>' . "\n";

							$k = 1 - $k;

	                        if( $ii + 1 >= $end ) {
	                            break;
	                        }
	                    }
					}
				}else{
	                $retval .= '<tr><td colspan="4" style="text-align:center">'
	                . JTEXT::_( 'No data' )
	                . '</td></tr>' . "\n";
	            }
			}else{
				$retval .= '<tr><td colspan="4" style="text-align:center">'
	            . JTEXT::_( 'No data' )
	            . '</td></tr>' . "\n";
			}

			// TotalLine
			$retval .= '<tr><th style="text-align:center">' . $sum_all_values . '</th>'
			. '<th>&nbsp;</th>'
			. '<th align="left">' . $total . '&nbsp;'
			. ( $total == 1 ? JTEXT::_( 'referring URL' ) : JTEXT::_( 'referring URLs' ) )
			. '</th></tr>' . "\n";
		}

        $retval .= '<tr><td colspan="3">'
        . $pagination->getListFooter()
		. '</td></tr>' . "\n"
        . '</table>' . "\n";
		
		$retval .= '</table>' . "\n";

		return $retval;
	}

	/**** case r11 see statistics.php ***/

	/**
	 * shows unknown bots
	 * case r12
	 *
	 * @return string
	 */
	function getUnknown() {
		global $mainframe;
		global $option;

		$this->_getDB();

		$limit	= intval( $mainframe->getUserStateFromRequest( 'viewlistlimit', 'limit', $mainframe->getCfg( 'list_limit' )));
        $limitstart	= intval( $mainframe->getUserStateFromRequest( 'viewlimitstart', 'limitstart', 0 ) );

		$where = array();

		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>'
		. '<th align="left" width="10%">' .JTEXT::_( 'Time' ) . '</th>'
		. '<th align="left" width="5%">' . JTEXT::_( 'Code' ) . '</th>'
		. '<th align="left" width="10%">' . JTEXT::_( 'Country/Domain' ) . '</th>'
		. '<th align="left" width="75%">' . JTEXT::_( 'Useragent' ) . '</th>'
		. '</tr>';

		$this->resetVar( 1 );

		$where[] = 'a.tld = b.tld';
		$where[] = 'c.ip_id = a.id';
		$where[] = '(a.browser LIKE \'Unknown%\' OR a.browser = \'\')';
		$where[] = 'c.day LIKE \'' . $this->d . '\'';
		$where[] = 'c.month LIKE \'' . $this->m . '\'';
		$where[] = 'c.year LIKE \'' . $this->y . '\'';

		/* mic: show only actual data (without already archived/purged)
		 * a.table : jstats_ipadresses
         * c.table : jstats_visits
         */
        if( !$this->JSConf->include_summarized ) {
            $where[] = 'a.id = c.ip_id AND c.id >= ' . $this->buid();
        }

        // get total records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__jstats_ipaddresses AS a, #__jstats_topleveldomains AS b, #__jstats_visits AS c'
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

		$query = 'SELECT a.tld, b.fullname, a.useragent, c.time'
		. ' FROM #__jstats_ipaddresses AS a, #__jstats_topleveldomains AS b, #__jstats_visits AS c'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		. ' ORDER BY c.time DESC'
		;
		$this->db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $this->db->loadObjectList();

		$this->resetVar( 0 );

		if( $rows ) {
			$k = 0;
		    foreach( $rows as $row ) {
                $retval .= '<tr class="row' . $k . '">'
                . '<td nowrap="nowrap">' . $row->time . '</td>'
                . '<td nowrap="nowrap">&nbsp;' . $row->tld . '</td>'
                . '<td nowrap="nowrap">&nbsp;' . JTEXT::_( $row->tld ) . '</td>' // $row->fullname
                . '<td nowrap="nowrap">&nbsp;' . $row->useragent . '</td>'
                . '</tr>';

                $k = 1 - $k;
            }
		}else{
        	$retval .= '<tr><td colspan="4" style="text-align:center">'
        	. JTEXT::_( 'No data' )
        	. '</td></tr>';
        }

		$retval .= '<tr><td colspan="4">'
        . $pagination->getListFooter()
		. '</td></tr>' . "\n"
        . '</table>' . "\n";

		return $retval;
	}

	/**
	 * case r03a & task r09a
	 *
	 * Displays pages with counts
	 * called by case r03 & r09
	 * input: $this->vid : id
	 * 		  $this->dom : name (optional)
	 *
	 * @return string
	 */
	function moreVisitInfo() {
		global $task, $mainframe;

		$this->_getDB();

		$moreinfo = intval( $mainframe->getUserStateFromRequest( 'moreinfo', 'moreinfo', '' ) );
		if( !$moreinfo ) {
			$moreinfo = $this->moreinfo;
		}
		// mic 20081015: and once again
		if( !$moreinfo ) {
			$moreinfo = $this->vid;
		}

		if( $this->_debug() ) {
			echo 'DEBUG Info from JS<br />'
		    . 'this->vid [ ' . $this->vid . ' ]<br />'
            . 'this->dom [ ' . $this->dom . ' ]<br />'
            . 'moreinfo [' . $moreinfo . ']<br />';
        }

		$totalnmb = 0;

		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>'
		. '<th align="left">' . JTEXT::_( 'Count' ) . '</th>'
		. '<th align="left" width="100%">' . JTEXT::_( 'Page' ) . '</th>'
		. '</tr>';

		$this->resetVar( 1 );

		$query = 'SELECT count(*) AS count, b.page, b.page_title'
		. ' FROM #__jstats_page_request AS a'
		. ' LEFT JOIN #__jstats_pages AS b ON b.page_id = a.page_id'
		. ' WHERE a.ip_id = ' . $moreinfo
		. ' GROUP BY b.page'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();

		$this->resetVar( 0 );

		if( $rows ) {
		    foreach( $rows as $row ) {
                $retval .= '<tr><td nowrap="nowrap">&nbsp;' . $row->count . '</td>'
                . '<td nowrap="nowrap">'
                . '<a href="' . htmlentities($row->page) . '" target="_blank"'
                . 'title="' . JTEXT::_( 'Click opens new window' ) . '">'
                . ( $row->page_title == '' ? $row->page : $row->page_title )
                . '</a>'
                . '</td></tr>' . "\n";
            }
        }else{
        	$retval .= '<tr><td colspan="4" style="text-align:center">'
        	. JTEXT::_( 'No data' )
        	. '</td></tr>';
        }

		$retval .= '<tr><th colspan="2">&nbsp;</th></tr>' . "\n"
		. '</table>' . "\n";

		$retval .= '<div style="text-align:center">[&nbsp;';

		if( $task == 'r09a' ) {
			$retval .= '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\'' . rawurlencode( $this->dom )
			. '\';document.adminForm.vid.value=\'' . $this->moreinfo . '\';submitbutton(\'r09\');">';
		}else{
			$retval .= '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; submitbutton(\'r03\');">';
		}

		$retval .= JTEXT::_( 'Back' ) . '</a>'
		. '&nbsp;]</div>';

		return $retval;
	}

	/**
	 * shows the path info
	 * case r03b (direct from case 03)
	 *
	 * @return string
	 */
	function morePathInfo() {
		global $task, $mainframe;

		$this->_getDB();

		$totalnmb = 0;
		$moreinfo = intval( $mainframe->getUserStateFromRequest( 'moreinfo', 'moreinfo', '' ) );

		if( !$moreinfo ) {
			$moreinfo = $this->moreinfo;
		}

		$this->resetVar( 0 );

		$query = 'SELECT b.page, b.page_title'
		. ' FROM #__jstats_page_request AS a'
		. ' LEFT JOIN #__jstats_pages AS b ON b.page_id = a.page_id'
		. ' WHERE a.ip_id = ' . $moreinfo
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();

		$this->resetVar( 0 ); // mic: can someone tell me WHY this thing is called so many times???

		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>'
		. '<th align="left" width="100%">' . JTEXT::_( 'Page' ) . '</th>'
		. '</tr>' . "\n";

		if( $rows ) {
		    foreach( $rows as $row ) {
		    	$retval .= '<tr><td nowrap="nowrap">'
                . '<a href="' . htmlentities( $row->page ) . '" target="_blank" title="'
                . JTEXT::_( 'Opens URL in new window' ) . '">'
                . ( $row->page_title == '' ? $row->page : $row->page_title )
                . '</a>'
                . '</td></tr>' . "\n";
            }
        }else{
        	$retval .= '<tr><td colspan="4" style="text-align:center">'
        	. JTEXT::_( 'No data' )
        	. '</td></tr>' . "\n";
        }

        $retval .= '<tr><th>&nbsp;</th></tr>' . "\n"
		. '</table>' . "\n"
		. '<div style="text-align:center">[&nbsp;'
		. '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; submitbutton(\'r03\');">' . JTEXT::_( 'Back' ) . '</a>'
		. '&nbsp;]</div>';

		return $retval;
	}

	/**
	 * shows the items from search engines
	 * case r14
	 *
	 * @return unknown
	 */
	function getSearches() {
		global $mainframe;

		$this->_getDB();

		$where				= array();
		$totalnmb 			= 0;
		$totalmax 			= 0;
		$do_search_engines 	= 0;
		$totalsearches 		= 0;

		$this->dom = JRequest::getVar( 'dom' );

		if( !$this->dom ) { // == ''
			// If function not called before, then start with search engines table
			$this->dom 			= '%';
			$do_search_engines	= 1;
		}

		$this->resetVar( 1 );

		$where[] = 'YEAR(a.kwdate) LIKE \'' . $this->y . '\'';
		$where[] = 'MONTH(a.kwdate) LIKE \'' . $this->m . '\'';
		$where[] = 'DAYOFMONTH(a.kwdate) LIKE \'' . $this->d . '\'';

		if( !$do_search_engines ) {
			$where[] = 'b.description LIKE \''. $this->dom . '\'';
		}

		if( $do_search_engines) {
			// Search Engines
			//$query = 'SELECT ' . ( $do_search_engines ? 'b.description' : 'a.keywords' ) . ', count(*) AS count'
			$query = 'SELECT b.description, count(*) AS count'
			. ' FROM #__jstats_keywords AS a'
			. ' LEFT JOIN #__jstats_search_engines AS b ON a.searchid = b.searchid'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' GROUP BY b.description'
			. ' ORDER BY count DESC'
			;
		}else{
			// Search Keyphrases
			$query = 'SELECT a.keywords, count(*) AS count'
			. ' FROM #__jstats_keywords AS a'
			. ' LEFT JOIN #__jstats_search_engines AS b ON a.searchid = b.searchid'
			. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			. ' GROUP BY a.keywords'
			. ' ORDER BY count DESC'
			;
		}

		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();

		$this->resetVar( 0 );

		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n" . '<tr>';

		if( $do_search_engines ) {
			// Search Engines
			$retval .= '<th width="5%" nowrap="nowrap">' . JTEXT::_( 'Count' ) . '</th>'
			. '<th width="10%">' . JTEXT::_( 'Percent' ) . '</th>'
			. '<th align="left" width="85%">' . JTEXT::_( 'Search Engine' ) . '</th>'
			. '</tr>' . "\n";

			foreach( $rows as $row ) {
				$totalnmb += $row->count;

				if( $row->count > $totalmax ) {
					$totalmax = $row->count;
				}
				$totalsearches++;
			}

			if( $totalnmb != 0 ) {
				$totalmaxpercent 	= round( ( ( $totalmax / $totalnmb ) * 100 ), 1 );
				$k					= 0;

				foreach( $rows as $row ) {
				    $percent = round( ( ( $row->count / $totalnmb ) * 100 ), 1 );

                    $retval .= '<tr class="row' . $k . '">' . "\n"
                    . '<td align="center" nowrap="nowrap">&nbsp;' . $row->count . '</td>'
                    . '<td align="left" nowrap="nowrap">&nbsp;'
                    . $this->PercentBar( $percent, $totalmaxpercent )
                    . '&nbsp;&nbsp;' . number_format( $percent, 1, ',', '' ) . '%'
                    . '</td>'
                  	. '<td align="left" nowrap="nowrap">&nbsp;'
                    . '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\''
                    . $row->description
                    . '\';submitbutton(\'r14\');" title="' . JTEXT::_( 'View search items' ) . '">'
                    . $row->description . '</a>'
                    . '</td></tr>' . "\n";

                    $k = 1 - $k;
				}
			}else{
                $retval .= '<tr><td colspan="4" style="text-align:center">'
                . JTEXT::_( 'No data' )
                . '</td></tr>' . "\n";
            }

			// TotalLine
			$retval .= '<tr><th>&nbsp;' . $totalnmb . '</th><th>&nbsp;</th>';

			if( $totalsearches > 0 ) {
				$retval .= '<th colspan="2" align="left">'
				. '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\'%\';submitbutton(\'r14\');">'
				. JTEXT::_( 'All search items' ) . '</a>'
				. '</th></tr>' . "\n";
			}else{
				$retval .= '<th align="left">' . JTEXT::_( 'No search engine entries' ) . '</th></tr>' . "\n";
			}

			$retval .= '</table>' . "\n";
		}else{
			// Search Keyphrases
			$retval .= '<th width="5%" nowrap="nowrap">' . JTEXT::_( 'Count' ) . '</th>'
			. '<th width="15%">' . JTEXT::_( 'Percent' ) . '</th>'
			. '<th align="left" width="85%">' . JTEXT::_( 'Search Keyphrases' ) . '</th>'
			. '</tr>';

			foreach( $rows as $row ) {
				$totalnmb += $row->count;

				if( $row->count > $totalmax ) {
					$totalmax = $row->count;
				}
				$totalsearches++;
			}

			if( $totalnmb !=0 && $rows ) {
				$totalmaxpercent 	= round( ( ( $totalmax / $totalnmb ) * 100 ), 1 );
				$k					= 0;

				foreach( $rows as $row ) {
				    $percent = round( ( ( $row->count / $totalnmb ) * 100), 1 );

                    $retval .= '<tr class="row' . $k . '">'
                    . '<td align="center" nowrap="nowrap">&nbsp;' . $row->count . '</td>'
                    . '<td align="left" nowrap="nowrap">&nbsp;'
                    . $this->PercentBar( $percent, $totalmaxpercent )
                    //. '</td><td align="right" nowrap="nowrap">'
                    . '&nbsp;&nbsp;' . number_format( $percent, 1, ',', '' ) . '%'
                    . '</td>'
                    . '<td width="100%" align="left" nowrap="nowrap">'
                    . wordwrap( $row->keywords, 100, '<br />' ) . '</td>'
                    . '</tr>' . "\n";

                    $k = 1 - $k;
				}
			}else{
                $retval .= '<tr><td colspan="4" style="text-align:center">'
                . JTEXT::_( 'No data' )
                . '</td></tr>' . "\n";
            }

			// TotalLine
			$retval .= '<tr><th>&nbsp;' . $totalnmb . '</th>'
            . '<th>&nbsp;</th>'
            . '<th align="left">' . $totalsearches . '&nbsp;'
            . ( $totalsearches == 1 ? JTEXT::_( 'Search engine entry' ) : JTEXT::_( 'Different search engine entries' ) )
            . '</th></tr>' . "\n"
			. '</table>' . "\n"
			. '<div style="text-align:center">[&nbsp;'
            . '<a href="javascript: if(document.adminForm.limitstart) document.adminForm.limitstart.value=0; document.adminForm.dom.value=\'\'; submitbutton();">'
            . JTEXT::_( 'Back' )
            . '</a>'
            . '&nbsp;]</div>';
		}

		return $retval;
	}
}