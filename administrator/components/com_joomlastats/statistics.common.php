<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}


require_once( dirname( __FILE__ ) .DS. 'statistics.common.html.php' );
require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );




/**
 *	This class generate statistics and show them in joomla back end (administrator panel)
 *
 *	NOTICE: methods from class JoomlaStats_Engine will be moved here
 *
 *	NOTICE: This class should contain only set of static, argument less functions that are called by task/action
 */
class js_JSStatisticsCommon
{
	var $MenuArrIdAndText = array();

	function __construct() {
		$this->getJSStatisticsMenu();
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
	function js_JSStatisticsCommon()
	{
		$args = func_get_args();
		call_user_func_array(array(&$this, '__construct'), $args);
	}
	

	/**
	 * build the menu items
	 *
	 * @param array $MenuArrIdAndText
	 * @since 2.3.x (mic): building the text with JTEXT
	 */
	function getJSStatisticsMenu() {

		$this->MenuArrIdAndText['r01'] = JTEXT::_( 'Summary Year' );
		$this->MenuArrIdAndText['r02'] = JTEXT::_( 'Summary Month' );
		$this->MenuArrIdAndText['r03'] = JTEXT::_( 'Visitors' );
		$this->MenuArrIdAndText['r05'] = JTEXT::_( 'Visitors by country' );
		$this->MenuArrIdAndText['r06'] = JTEXT::_( 'Page Hits' );
		$this->MenuArrIdAndText['r07'] = JTEXT::_( 'Systems' );
		$this->MenuArrIdAndText['r08'] = JTEXT::_( 'Browsers' );
		$this->MenuArrIdAndText['r09'] = JTEXT::_( 'Bots/spiders' );
		$this->MenuArrIdAndText['r10'] = JTEXT::_( 'Referrers' );
		$this->MenuArrIdAndText['r11'] = JTEXT::_( 'Not identified visitors' );
		$this->MenuArrIdAndText['r12'] = JTEXT::_( 'Unknown bots/spiders' );
		$this->MenuArrIdAndText['r14'] = JTEXT::_( 'Searches' );
		//$this->MenuArrIdAndText['r15'] = JTEXT::_( 'Resolutions' );
	}

	/**
	 * collecting and pass thru several datas for building the html.header (incl. <form> tag)
	 *
	 * @param string $FilterSearch
	 * @param string $FilterDate
	 * @param integer $vid
	 * @param string $moreinfo
	 * @param string $DatabaseSizeHtmlCode
	 * @param string $JSVersion
	 * @param string $FilterDomain
	 * @return string
	 */
	function getJSStatisticsHeaderHtmlCode($JSConf, $FilterSearch, $FilterDate, $vid, $moreinfo, $FilterDomain) {

		$JSUtil = new js_JSUtil();
		$DatabaseSizeHtmlCode = $JSUtil->getJSDatabaseSizeHtmlCode();
		$JSVersion = $JSConf->JSVersion;
		$include_summarized = $JSConf->include_summarized;
		$JSDbSOV = new js_JSDbSOV();
		$LastSummarizationDate = false;
		$JSDbSOV->getJSLastSummarizationDate($LastSummarizationDate);
		
			
		$task = JRequest::getVar( 'task', 'js_view_statistics_default' ); // mic: changed to J.1.5-style

		// new mic: adding 'none menu' items called for 'more info'
		$this->MenuArrIdAndText['r03a'] = JTEXT::_( 'Visited pages' );
		$this->MenuArrIdAndText['r03b'] = JTEXT::_( 'Path info' );
		$this->MenuArrIdAndText['r09a'] = JTEXT::_( 'Bots/spiders' );

		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();
		$JSStatisticsCommonTpl->task = $task; // new mic

		$html = $JSStatisticsCommonTpl->getJSStatisticsHeaderHtmlCodeTpl($FilterSearch, $FilterDate, $vid, $moreinfo, $DatabaseSizeHtmlCode, $JSVersion, $FilterDomain, $this->MenuArrIdAndText[$task], $this->MenuArrIdAndText, $LastSummarizationDate, $include_summarized);

		return $html;
	}

	/**
	 * builds the footer (also with the final </form> tag)
	 *
	 * @return string
	 */
	function getJSStatisticsFooterHtmlCode() {
		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();

		$html = $JSStatisticsCommonTpl->getJSStatisticsFooterHtmlCodeTpl();

		return $html;
	}
}
