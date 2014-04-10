<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'base.classes.php' );



	
/**
 *	This file contain filters that are used in joomla backend to change searching criteria in statistics
 */


 

 
 
 /**
 *	This class makes working with time period filter easy.
 *	It can generate HTML code, create SQL query, read values from request.
 */
class js_JSFilterDate
{
	var $year;
	var $month;
	var $day;
	
	var $prefix = '';
	var $sufix  = '';
	
	var $year_min = 2003;
	var $year_max = 2010;//will be overriden in constructor
	
	/** we need sufix in case when we create list of date filters (eg. date to each row from sql query) */
	function __construct( $prefix='', $sufix='' ) {
		$this->year_max = date( 'Y', time() );
		
		$this->prefix = $prefix;
		$this->sufix  = $sufix;
	}
	
	/**
	 * A hack to support __construct() on PHP 4
	 *
	 * Hint: descendant classes have no PHP4 class_name() constructors,
	 * so this constructor gets called first and calls the top-layer __construct()
	 * which (if present) should call parent::__construct()
	 *
	 * code from Joomla CMS 1.5.10 (thanks!)
	 *
	 * @access	public
	 * @return	Object
	 * @since	1.5
	 */
	function js_JSFilterDate()
	{
		$args = func_get_args();
		call_user_func_array(array(&$this, '__construct'), $args);
	}
	

	function readDateFromRequest( $alternate_year='', $alternate_month='', $alternate_day='') {
		global $mainframe;

		if (strlen($alternate_year) == 0)
			$alternate_year = date('Y');
		
		if (strlen($alternate_month) == 0)
			$alternate_month = date('n');
			
		if (strlen($alternate_day) == 0)
			$alternate_day = date('j');
			
		$this->year  = $mainframe->getUserStateFromRequest( 'year',  'year',  $alternate_year );
		$this->month = $mainframe->getUserStateFromRequest( 'month', 'month', $alternate_month );
		$this->day   = $mainframe->getUserStateFromRequest( 'day',   'day',   $alternate_day );
	}

	function getDateStr() {
		return $this->year .'-'. ((strlen($this->month)==1) ? '0' : '') . $this->month .'-'. ((strlen($this->day)==1) ? '0' : '') . $this->day;
	}
	

	/**
	 * Create the Day dropdown
	 *
	 * @access private
	 * @return string
	 */
	function CreateDayCmb() {

		$html = '';

		for( $i = 1; $i <= 31; $i++ ) {
			$html .= '<option value="' . $i . '"';
			if( $this->day == $i ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $i . '</option>' . "\n";
		}

		return $html;
	}

	/**
	 * Creates the dropdown for months
	 *
	 * @access private
	 * @return string
	 */
	function CreateMonthCmb() {

		$html = '';
		
		$JSUtil = new js_JSUtil();
		$MonthsNamesTruntucatd = $JSUtil->getMonthsNamesTruntucatd();

		for( $i=1; $i<13; $i++ ) {
			$html .= '<option value="' . $i . '"';
			if( $this->month == $i ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $MonthsNamesTruntucatd[$i] . '</option>' . "\n";
		}

		return $html;
	}

	/**
	 * Creates the year drop down
	 *
	 * @access private
	 * @return string
	 */
	function CreateYearCmb() {

		$html		= '';

		for( $i = $this->year_min; $i <= $this->year_max; $i++ ) {
			$html .= '<option value="' . $i . '"';
			if( $this->year == $i ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $i . '</option>' . "\n";
		}

		return $html;
	}

	/** $date: '2008-06-19' */
	function SetYMD( $date='now', $hourdiff=0 ) {

		$data_arr = explode('-', $date);
		if (count($data_arr) == 3) {
			$this->year  = $data_arr[0];
			$this->month = $data_arr[1];
			$this->day   = $data_arr[2];
		} else {
			$visittime = ( time() + ( $hourdiff * 3600 ) );
			$this->year  = date( 'Y', $visittime );
			$this->month = date( 'n', $visittime );
			$this->day   = date( 'j', $visittime );
		}
	}

	/**
	 * creates a javascript and dropdowns for date selection
	 *
	 * @since 2.3.x: if all months are selected, also all days will be checked
	 * @return string
	 */
	function getHtmlDateFilterCode() {

		$html  = '';

		$html .= '<select name="day">' . $this->CreateDayCmb() . '</select>';//<!-- combo day here -->
		$html .= '&nbsp;';
		$html .= '<select name="month">' . $this->CreateMonthCmb() . '</select>';//<!-- combo month here -->
		$html .= '&nbsp;';
		$html .= '<select name="year">' . $this->CreateYearCmb() . '</select>';//<!-- combo year here -->

		return $html;
	}
}

 
 

 
 
 

  
  
/**
 *	This class makes working with time period filter easy.
 *	It can generate HTML code, create SQL query, read values from request.
 *
 *	NOTICE: This class is deprecated, we need a new one (with time period and calendar).
 */
class js_JSFilterTimePeriodDeprecated
{
	var $d;
	var $m;
	var $y;

	function readDateFromRequest( $hourdiff, $startdayormonth ) {
		global $mainframe;

		// get JS specific values
		$visittime = ( time() + ( $hourdiff * 3600 ) );
		if( !isset( $_POST['d'] ) ) {
			if( $startdayormonth == 'm' ) {
				$this->d = 'total';
			}else{
				$this->d = date( 'j', $visittime );
			}
		}

		$this->m = $mainframe->getUserStateFromRequest( $this->m, 'm', date( 'n', $visittime ) );
		$this->y = $mainframe->getUserStateFromRequest( $this->y, 'y', date( 'Y', $visittime ) );
	}

	
	/**
	 * Returns percents to SQL queries
	 *
	 * @deprecated - this function will be removed soon
	 * @access public
	 * @return unknown
	 */
	function getOldFormat( &$day, &$month, &$year ) {
		$day = $this->d;
		if( $this->d == 'total' ) {
			$day = '%';
		}
		
		$month = $this->m;
		if( $this->m == 'total' ) {
			$month = '%';
		}
		$year = $this->y;
		if( $this->y == 'total' ) {
			$year = '%';
		}
	}
	
	/**
	 * Builds a sql.query string
	 * Only called from statistics.php
	 *
	 * @param string $table_prefix
	 * @access private
	 * @return unknown
	 */
	function getSqlConditionString( $table_prefix ) {
		$day = $this->d;
		if( $this->d == 'total' ) {
			$day = '%';
		}
		$month = $this->m;
		if( $this->m == 'total' ) {
			$month = '%';
		}
		$year = $this->y;
		if( $this->y == 'total' ) {
			$year = '%';
		}

		return $table_prefix . '.day LIKE \'' . $day . '\''
		. ' AND ' . $table_prefix . '.month LIKE \'' . $month . '\''
		. ' AND ' . $table_prefix . '.year LIKE \'' . $year . '\'';
	}

	function getTimePeriodsDates( &$date_from, &$date_to ) {
		$day_from = $this->d;
		$day_to = $this->d;
		if( $this->d == 'total' ) {
			$day_from = '1';
			$day_to = '31';
		}
		$month_from = $this->m;
		$month_to = $this->m;
		if( $this->m == 'total' ) {
			$month_from = '1';
			$month_to = '12';
		}
		$year_from = $this->y;
		$year_to = $this->y;
		if( $this->y == 'total' ) {
			$date_from = '';
			$date_to = '';
			return;
		}

		$date_from = $year_from.'-'.$month_from.'-'.$day_from;
		$date_to = $year_to.'-'.$month_to.'-'.$day_to;
	}
	
	/**
	 * Create the Day dropdown
	 *
	 * @access private
	 * @return string
	 */
	function CreateDayCmb() {

		$html = '';

		$html .= '<option value="total"';
		if( $this->d == 'total' )
			 $html .= ' selected="selected"';
		$html .= '>' . JTEXT::_( 'All' ) . '</option>' . "\n";

		for( $i = 1; $i <= 31; $i++ ) {
			$html .= '<option value="' . $i . '"';
			if( $this->d == $i ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $i . '</option>' . "\n";
		}

		return $html;
	}

	/**
	 * Creates the dropdown for months
	 *
	 * @access private
	 * @return string
	 */
	function CreateMonthCmb() {

		$html = '';
		
		$JSUtil = new js_JSUtil();
		$MonthsNamesTruntucatd = $JSUtil->getMonthsNamesTruntucatd();

		$html .= '<option value="total"';
		if( $this->m == 'total' )
			 $html .= ' selected="selected"';
		$html .= '>' . JTEXT::_( 'All' ) . '</option>' . "\n";
		
		for( $i=1; $i<13; $i++ ) {
			$html .= '<option value="' . $i . '"';
			if( $this->m == $i ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $MonthsNamesTruntucatd[$i] . '</option>' . "\n";
		}

		return $html;
	}

	/**
	 * Creates the year drop down
	 *
	 * @access private
	 * @return string
	 */
	function CreateYearCmb() {

		$html		= '';
		$date_min	= 2003;
		$date_max	= date( 'Y', time() );

		$html .= '<option value="total"';
		if( $this->y == 'total' )
			 $html .= ' selected="selected"';
		$html .= '>' . JTEXT::_( 'All' ) . '</option>' . "\n";

		for( $i = $date_min; $i <= $date_max; $i++ ) {
			$html .= '<option value="' . $i . '"';
			if( $this->y == $i ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $i . '</option>' . "\n";
		}

		return $html;
	}

	/**
	 * Function to set $this->d; $this->m; $this->y values to now
	 * calculate time diff from server time to local time
	 *
	 * @param integer $hourdiff
	 */
	function SetDMY2Now( $hourdiff ) {

		$visittime = ( time() + ( $hourdiff * 3600 ) );

		// new mic: security
		$d = JRequest::getVar( 'd', '' );
		$m = JRequest::getVar( 'm', '' );
		$y = JRequest::getVar( 'y', '' );

		if( !$d ) {
			$this->d = date( 'j', $visittime );
		}else{
			$this->d = $d;
		}

		if( !$m ) {
			$this->m = date( 'n', $visittime );
		}else{
			$this->m = $m;
		}

		if( !$y ) {
			$this->y = date('Y',$visittime);
		}else{
			$this->y = $y;
		}
	}

	/**
	 * creates a javascript and dropdowns for date selection
	 *
	 * @todo mic: javascript should be outside into the header and NOT direct in the code
	 * @AT: of course not - on page could be 2 date filters
	 *
	 * @return string
	 */
	function getHtmlDateFilterCode() {

		$html  = '';

		$html .= '
			<script type="text/javascript">
				/* <![CDATA[ */
				function SelectDay(Value) {
					for (index=0; index<document.adminForm.d.length; index++) {
						/* walk the list */
						if (document.adminForm.d[index].value == Value) {
							/* if the day is the day we want to select */
							document.adminForm.d.selectedIndex = index;
							/* then mark it selected */
						}
					}
				};

				function onDChange() {
					if (document.adminForm.d.value == "total") {
					} else {
						if (document.adminForm.m.value == "total")
							document.adminForm.m[1].selected = true;
						if (document.adminForm.y.value == "total")
							document.adminForm.y[1].selected = true;
					}
				};
				
				function onMChange() {
					if (document.adminForm.m.value == "total") {
						document.adminForm.d.value = "total";
					} else {
						if (document.adminForm.y.value == "total")
							document.adminForm.y[1].selected = true;
					}
				};
				
				function onYChange() {
					if (document.adminForm.y.value == "total") {
						document.adminForm.d.value = "total";
						document.adminForm.m.value = "total";
					} else {
					}
				};
				
				/* ]]> */
			</script>
		';

		$html .= ''
		. '<select name="d" onChange="onDChange();">' . $this->CreateDayCmb() . '</select>' 
		. '&nbsp;&nbsp;'
		. '<select name="m" onChange="onMChange();">' . $this->CreateMonthCmb() . '</select>'
		. '&nbsp;&nbsp;'
		. '<select name="y" onChange="onYChange();">' . $this->CreateYearCmb() . '</select>'
		. '&nbsp;&nbsp;'
		. '<input type="submit" name="Submit" id="Submit" value="'.JTEXT::_('Go').'" /> '
		;

		return $html;
	}
}




/**
 *	This class makes working with domain filter easy.
 *	It can generate HTML code, create SQL query, read values from request.
 */
class js_JSFilterDomain
{
	/** This membes hold user entered (user selected) string
	 *	This string is used when database is queried
	 *	@access private */
	var $_domain_string = '';

	/** gets var from request string */
	function readDomainStringFromRequest() {
		$this->_domain_string = JRequest::getVar( 'dom' ); // mic: changed to J.1.5-style
	}

	/** return 'Domain String' */
	function getDomainString() {
		return $this->_domain_string;
	}

	/** Domain filter is always hidden */
	function getHtmlDomainFilterCode() {
		return '<input type="hidden" name="dom" id="dom" value="' . $this->_domain_string . '" />';
	}

	/**
	 * generates a image with link to reset detailed view back to single view
	 * mic: primary the same as the 'back' button
	 *
	 * @param string $task
	 * @return string (html)
	 *
	 * @todo AT: add button to reset domain (to view all domains) mic: should per 2008.10.15 done
	 * @since 2.3.x mic: added task
	 */
	function getHtmlDomainCodeToHeader( $task = '' ) {

		$domain = $this->getDomainString();
		$alt	= JTEXT::_( 'Click to see complete statistic' );

		// new mic
		if( $domain == '%' ) {
			$domain = JTEXT::_( 'All' );
		}

		$image = '<img src="'
		. JURI::base( true )
		. '/components/com_joomlastats/images/icon-16-js_reset-domain.png" width="16" height="16" border="0"'
		. ' alt="' . $alt . '" title="' . $alt . '" />';

		//we must also reset begining of list!!
		$reset_domain_button = '<a href="javascript:document.adminForm.dom.value=\'\'; document.adminForm.limitstart.value=0; document.adminForm.submit(\'' . $task . '\');">'.$image.'</a>';

		if( $domain != '' ) {
			return '&nbsp;&nbsp;&lt;&nbsp;'.$domain.'&nbsp;&gt;'.'&nbsp;'.$reset_domain_button.'&nbsp;&nbsp;';
		}else{
			return '';
		}
	}
}




/**
 *	This class makes working with search filter easy.
 *	It can generate HTML code, create SQL query, read values from request.
 */
class js_JSFilterSearch
{
	/**
	 * This membes hold user entered sting to search input
	 * This string is used when database is queried
	 * @access private
	 */
	var $_search_string = '';

	/**
	 * This membes hold hint that is displayed on search mouse over action
	 * eg. 'Search (IP/TLD/NS-Lookup/OS)'
	 * @access private
	 */
	var $_search_hint = '';

	/**
	 * This membes decide when search filter should be shown. Set it to 'true' if You want have search filter visible
	 *	@access public
	 */
	var $show_search_filter = false;


	function readSearchStringFromRequest() {
		global $mainframe;
		global $option;

		$this->_search_string = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
	}

	/** return 'Search String' */
	function getSearchString() {
		return $this->_search_string;
	}

	/**
	 * Set hint that is displayed on search mouse over action
	 * eg. 'Search (IP/TLD/NS-Lookup/OS)'
	 * @param string
	 */
	function setSearchHint( $search_hint ) {
		$this->_search_hint = $search_hint;
	}

	/**
	 * builds an input field for search
	 *
	 * @return string
	 */
	function getHtmlSearchFilterCode() {

		$hint = ( $this->_search_hint == '' ) ? '' : ( ' title="' . $this->_search_hint . '"' );

		$html  = JTEXT::_( 'Search' )
		. ':&nbsp;'
		. '<input type="text" name="search" id="search" value="' . $this->_search_string . '"'
		. ' class="text_area" onChange="document.adminForm.submit();"' . $hint . ' />';

		return $html;
	}

	/**
	 * builds a hidden field holding the search item
	 *
	 * @return string
	 */
	function getHtmlSearchFilterHiddenCode() {
		return '<input type="hidden" name="search" id="search" value="' . $this->_search_string . '" />';
	}
}
	