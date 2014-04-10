<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



/**
 * This is file with basic classes
 *
 * It is used also in non-joomla environment
 *
 * Basic classes should:
 *	  - be small
 *	  - be well comented
 *	  - not generate any HTML code
 *    - should provide constants, defines
 *    - no bussines logic
 *    - no compatibility
 */
if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

// mic: some generic checks and definitions
if( !defined( 'DS' ) ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}


require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'db.constants.php' );



if( !defined( '_JEXEC') && !class_exists( 'jxTools' ) ) {
	if (!defined( '_JS_STAND_ALONE' )) {
		require_once( dirname( __FILE__ ) .DS. 'tools' .DS. 'jxtools.php' );
	}
}

/**
 * basic class for compatibility J.1.0.x -> J.1.5.x
 *
 * fill in here all what other classes maybe need always
 * or/and is used very often
 *
 * @deprecated - will be removed: a) some parts should not be here  b) the other code is overlaped with class js_JSDatabaseAccess   c)this file provide mostly defines!!!
 */
class JSBasic
{
	/** database placeholder */
	var $db;
	var $mainframe;
	/** sets debug status */
	var $debug;
	/** defines site (index.php [J.1.5.x] or index2.php [J.1.0.x] */
	var $index;

	function __construct() { }

	
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
	function JSBasic()
	{
		$args = func_get_args();
		call_user_func_array(array(&$this, '__construct'), $args);
	}	

		
	/**
	 * defines the database connector
	 *
	 */
	function _getDB() {
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'access.php' );
		$JSDatabaseAccess = new js_JSDatabaseAccess();
		$this->db = $JSDatabaseAccess->db;

		/*
		global $database;

		if( defined('_JEXEC') ) {
			//joomla 1.5
			$this->db = JFactory::getDBO();
		}else{
			//joomla 1.0.x
			$this->db = $database;
		}
		*/
	}

	/**
	 * gets the debug status
	 * @deprecated - will be removed use: js_echoJSDebugInfo()
	 */
	function _debug() {
		global $mainframe;

		if( isJ15() ) {
			$conf =& JFactory::getConfig();
			$this->debug = $conf->getValue('config.debug');
		}else{
			$this->debug = $mainframe->getCfg( 'debug' );
		}

		return $this->debug;
	}

	/**
	 * defines the index page
	 *
	 */
	function _index() {
		if( isJ15() ) {
			$this->index = 'index.php';
		}else{
			$this->index = 'index2.php';
		}

		return $this->index;
	}
}


/**
 * 'Joomla Stats' class that contain SYSTEM CONSTANTS (this class replace define('AAAA'); that are globals)
 *
 * All members are READ ONLY!
 */
class js_JSSystemConst
{
	/**
	 * below string will be written to joomla front page if JS are activated for this particular page
	 * it is written just before counting that page
	 * NOTE: do not add \n or any invicible characters
	 * - they produce additional verical space in IE when they are in <td></td> tag without any other content
	 */
	var $htmlFrontPageJSActivatedString = '<!-- JoomlaStatsActivated -->';
	
	/**
	 *  List of all JS tables
	 *  Use this list to uninstall datbase, optimize database etc.
	 */
	var $allJSDatabaseTables = array( '#__jstats_bots', '#__jstats_browsers', '#__jstats_configuration', '#__jstats_ipaddresses', '#__jstats_iptocountry', '#__jstats_keywords', '#__jstats_page_request', '#__jstats_page_request_c', '#__jstats_pages', '#__jstats_referrer', '#__jstats_search_engines', '#__jstats_systems', '#__jstats_topleveldomains', '#__jstats_visits' );
	
	var $defaultPathToImagesTld     = 'tld-png-16x11-1';
	var $defaultPathToImagesOs      = 'os-png-14x14-1';
	var $defaultPathToImagesBrowser = 'browser-png-14x14-1';
}

/**
 * 'Joomla Stats' class that contain DEFAULT 'Joomla Stats' configuration
 *
 * All members should be READ ONLY!
 */
class js_JSConfDef extends JSBasic
{
	/** constructor do nothing. Only for PHP4.0 */
	function __construct() {
		parent::__construct();
	}
	
	
	/**
	 *	Members initialization values are system default values!
	 */

	/**
	 * this constant was hold by define('_JoomlaStats_V','2.3.0_dev2008-08-12'); in previous releases version of script
	 * this member is not stored to database by function storeConfigurationToDatabase() (security)
	 * version x.y.w.z  z - is SVN version
	 *
	 * NOTICE:
	 *   - Always should be 4 nuber sections!!! - see method JSVersionCompare(...)
	 *   - space is separation character to. Space differ development and release versions!!!
	 * 
	 * eg.: '2.3.0.151 dev' - for development snapshot
	 * eg.: '2.3.0.194'     - for release
	 * 
	 */
	var $JSVersion = '2.3.2.586';// eg '2.3.0.151 dev' 

	/** time online in [minutes] before new visitor */
	var $onlinetime = 15;

	/** option for starting statistics */
	var $startoption = 'r02';

	/** option for selecting 1 day or whole month at JoomlaStats start */
	var $startdayormonth = 'd';

	/** show statistics including summarized/purged data */
	var $include_summarized = true;

	/** show statistics with summarized/purged data in brackets [23244] //$show_summarized HAVE TO be set to false if $include_summarized = false */
	var $show_summarized = true;

	/** enable Whois queries */
	var $enable_whois = true;

	/** enable Joom!Fish i18n support */
	var $enable_i18n = true;
}

/**
 * 'Joomla Stats' class that contain CURRENT 'Joomla Stats' configuration
 */
class js_JSConf extends js_JSConfDef
{
	/** Constructor load current configuration */
	function __construct( $initializeFromDatabase = true ) {
		parent::__construct();
		if( $initializeFromDatabase ) {
			$this->initializeByConfigurationFromDatabase();
		}
	}

	/**
	 *	This function read configuration stored in database and fill this class members
	 */
	function initializeByConfigurationFromDatabase() {

		$this->_getDB();

		$query = 'SELECT *'
		. ' FROM #__jstats_configuration';
		$this->db->setQuery( $query );
		$rows = $this->db->loadAssocList();
		if ($this->db->getErrorNum() > 0) {
			echo $this->db->getErrorMsg() . ', ' . $query; //@at in j1.5.x $query is relevant. SQL is inside ErrorMsg
			return false;
		}


		foreach( $rows as $row ) {
			if( $row['description'] == 'version' ) {
				$this->JSVersion = $row['value'];
			}

			if( $row['description'] == 'onlinetime' ) {
				$this->onlinetime = $row['value'];
			}

			if( $row['description'] == 'startoption' ) {
				$this->startoption = $row['value'];
			}

			if( $row['description'] == 'startdayormonth' ) {
				$this->startdayormonth = $row['value'];
			}

			if( $row['description'] == 'language' ) {
				$this->language = $row['value'];
			}

			if( $row['description'] == 'include_summarized' ) {
				$this->include_summarized = ( $row['value'] === 'true' ) ? true : false;
			}

			if( $row['description'] == 'show_summarized' ) {
				$this->show_summarized = ( $row['value'] === 'true' ) ? true : false;
			}

			if( $row['description'] == 'enable_whois' ) {
				$this->enable_whois = ( $row['value'] === 'true' ) ? true : false;
			}

			if( $row['description'] == 'enable_i18n' ) {
				$this->enable_i18n = ( $row['value'] === 'true' ) ? true : false;
			}
		}

		return true;
	}

	/**
	 * This function write configuration (this class members) to database
	 *
	 * @param string $err_msg
	 * @return string
	 */
	function storeConfigurationToDatabase( &$err_msg ) {

		$err_msg = '';
		$this->_getDB();

		$queri[] = 'UPDATE #__jstats_configuration'
		. ' SET value = \'' . $this->onlinetime . '\''
		. ' WHERE description = \'onlinetime\''
		;

		$queri[] = 'UPDATE #__jstats_configuration'
		. ' SET value = \'' . $this->startoption . '\''
		. ' WHERE description = \'startoption\''
		;

		$queri[] = 'UPDATE #__jstats_configuration'
		. ' SET value = \'' . $this->startdayormonth . '\''
		. ' WHERE description = \'startdayormonth\''
		;

		$queri[] = 'UPDATE #__jstats_configuration'
		. ' SET value = \'' . ( ( $this->include_summarized ) ? 'true' : 'false' ) . '\''
		. ' WHERE description = \'include_summarized\''
		;

		$queri[] = 'UPDATE #__jstats_configuration'
		. ' SET value = \'' . ( ( $this->show_summarized ) ? 'true' : 'false' ) . '\''
		. ' WHERE description = \'show_summarized\''
		;

		$queri[] = 'UPDATE #__jstats_configuration'
		. ' SET value = \'' . ( ( $this->enable_whois ) ? 'true' : 'false' ) . '\''
		. ' WHERE description = \'enable_whois\''
		;

		$queri[] = 'UPDATE #__jstats_configuration'
		. ' SET value = \'' . ( ( $this->enable_i18n ) ? 'true' : 'false' ) . '\''
		. ' WHERE description = \'enable_i18n\''
		;

		foreach( $queri as $query ) {
			$this->db->setQuery( $query );
			$this->db->query();
			if ($this->db->getErrorNum() > 0) {
				$err_msg .= $this->db->getErrorMsg();
			}
		}

		if( strlen( $err_msg ) > 0 ) {
			return false;
		}

		return true;
	}

}



/**
 *  This class contain (hold) data about visitor
 *
 *  This class is only container for data - to pass data through methods etc.
 *
 *  Members of this class corespond to database table #__jstats_ipaddresses (will be renamed to #__jstats_visitors) column names
 *
 *  NOTICE:
 *     Creating new object create unknown Visitor. This is proper feature.
 */
class js_Visitor
{
	/** visitor ID */
	var $visitor_id         = 0;

	/** visitor IP address //value directly taken from visitor //@todo: example is missing (v6 also?) //@todo: missing value initialization */
	var $visitor_ip         = null;

	/** hold string //value directly taken from visitor //eg.: "mozilla/5.0 (windows; u; windows nt 5.1; en-gb; rv:1.8.1.15) gecko/20080623 firefox/2.0.0.15" */
	var $visitor_useragent  = '';	// User agent (i.e. browser)

	/** Requested page URL //value directly taken from visitor //@todo: example is missing */
	//var $RequestedPage    = null;

	/** true if user is excluded from counting statistics */
	var $visitor_exclude    = 0;//probably there must be int //@todo: define should be used

	/** Visitor type: _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR, _JS_DB_IPADD__TYPE_REGULAR_VISITOR, _JS_DB_IPADD__TYPE_BOT_VISITOR; Defines are in db.constants.php file */
	var $visitor_type       = _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR;

	/** It holds object of class js_OS */
	var $OS                 = null;
	
	/** null for bot, for regular visitor it contain object of class js_Browser (when $visitor_type = _JS_DB_IPADD__TYPE_REGULAR_VISITOR; - one data hold in two member - @todo)*/
	var $Browser            = null;

	/** It holds object of class js_Tld */
	var $Tld                = null;
	
	/** Valid only when $Type = _JS_DB_IPADD__TYPE_REGULAR_VISITOR; eg.: "7.0" //(Connected with $BrowserName gives "Internet Explorer 7.0") */
	//var $browser_version  = '';
	
	/** Bot Id - integer; Valid only when $Type = _JS_DB_IPADD__TYPE_BOT_VISITOR; Value from table #__jstats_bots from column bot_id */
	var $bot_id			= 0;

	/** Bot Name - string; Valid only when $Type = _JS_DB_IPADD__TYPE_BOT_VISITOR; eg.: "Googlebot (Google)" */
	var $bot_name		= null;

	/** ? URL @todo: example is missing. See JS trackers for details */
	//var $screen_x		= 0;
	//var $screen_y		= 0;

	/** String returned by PHP method gethostbyaddr( $visitor_ip ); If gethostbyaddr( $visitor_ip ); return $visitor_ip this member will contain empty string (''). eg.: "crawl-66-249-70-72.googlebot.com", "sewer.com.eu", but not "66.249.70.72" */
	var $nslookup		= '';//in PHP documentation it is called 'Internet host name'
}


/**
 *  This class contain (hold) data about visitor that are known by JS
 *
 *  This class is only container for data - to pass data through methods etc.
 */
class js_VisitorEx extends js_Visitor
{
	/** User ID if user is logged into 'Joomla CMS'. If user is not logged value is 0 */
	//var $cms_userid			= null; this member should belong to other class

	/** Valid url to image. It can be used in <img src. Path could be without top level domain but it will work! eg.: "/components/com_joomlastats/images/os-png-16x16-1/windowsxp.png" */
	var $os_img_url			= null;

	/** HTML image tag. Ready to using in template eg.: "<img src="/components/com_joomlastats/images/os-png-16x16-1/windowsxp.png" alt="Windows XP" />" */
	var $os_img_html     	= null;

	/** Valid url to image. It can be used in <img src. Path could be without top level domain but it will work! eg.: "/components/com_joomlastats/images/os-png-16x16-1/pda.png" */
	var $ostype_img_url  	= null;

	/** HTML image tag. Ready to using in template eg.: "<img src="/components/com_joomlastats/images/os-png-16x16-1/pda.png" alt="PDA or Phone" />" */
	var $ostype_img_html 	= null;

	/** Valid url to image. It can be used in <img src. Path could be without top level domain but it will work! eg.: "/components/com_joomlastats/images/browser-png-16x16-1/explorer.png" */
	var $browser_img_url	= null;

	/** HTML image tag. Ready to using in template eg.: "<img src="/components/com_joomlastats/images/browser-png-16x16-1/explorer.png" alt="Internet Explorer" />" */
	var $browser_img_html	= null;

	var $browsertype_img_url  	= null;//probably null up to v2.5.0 - not enough time to implement
	var $browsertype_img_html 	= null;//probably null up to v2.5.0 - not enough time to implement
}

/**
 *  This class contain (hold) data about Operating System (OS)
 *
 *  This class is only container for data - to pass data through methods etc.
 *
 *  Members of this class corespond to database table #__jstats_systems (will be renamed to #__jstats_os) column names
 *     and virtual table #__jstats_ostype (those tables will be merged soon)
 *
 *  NOTICE:
 *     Creating new object create unknown OS. This is proper feature.
 */
class js_OS
{
	/** Primary Key from table #__jstats_os from column sys_id */
	var $os_id          = _JS_DB_OS__ID_UNKNOWN;//_JS_DB_OS__ID_UNKNOWN is equeal 0

	/** Primary Key from table #__jstats_ostype from column sys_id */
	var $ostype_id      = _JS_DB_OSTYP__ID_UNKNOWN;

	/** String that idetify OS eg.: "winme"; "windows nt 6.0"; "linux"; */
	var $os_key         = _JS_DB_OS__KEY_UNKNOWN;

	/** Human friendly OS name eg.: "Windows XP"; "Windows Vista"; "Mac OS"; "Linux"; */
	var $os_name        = _JS_DB_OS__NAME_UNKNOWN;

	/** Name of image file without extension eg.: "windowsxp"; "windowsvista"; "mac"; Extension is taken from directory name */
	var $os_img         = _JS_DB_OS__IMG_UNKNOWN;

	/** Human friendly OS Type name eg.: "Windows"; "PDA or Phone"; "Other"; */
	var $ostype_name    = _JS_DB_OSTYP__NAME_UNKNOWN;

	/** Name of image file without extension eg.: "unknown"; "windowsxp"; "linux"; "other"; "pda"; See defines _JS_DB_OSTYP for all available names. Extension is taken from directory name. */
	var $ostype_img     = _JS_DB_OSTYP__IMG_UNKNOWN;
}


/**
 *  This class contain (hold) data about Browsers
 *
 *  This class is only container for data - to pass data through methods etc.
 *
 *  Members of this class corespond to database table #__jstats_browsers merged with #__jstats_browserstype (virtual table) column names
 *
 *  NOTICE:
 *     Creating new object create unknown Browser. This is proper feature.
 */
class js_Browser
{
	/** Primary Key from table #__jstats_browser from column browser_id */
	var $browser_id        = _JS_DB_BRWSR__ID_UNKNOWN;//_JS_DB_BRWSR__ID_UNKNOWN is equeal 0

	/** Primary Key from table #__jstats_browsertype from column browsertype_id */
	var $browsertype_id    = _JS_DB_BRTYP__ID_UNKNOWN;

	/** String that idetify browser eg.: "msie"; "firefox" */
	var $browser_key       = _JS_DB_BRWSR__KEY_UNKNOWN;

	/** Human friendly Browser name eg.: "Internet Explorer"; "Google Chrome"; "FireFox"; "Netscape" */
	var $browser_name      = _JS_DB_BRWSR__NAME_UNKNOWN;

	/** Name of image file without extension eg.: "explorer"; "netscape"; "noimage"; "firefox"; Extension is taken from directory name. */
	var $browser_img       = _JS_DB_BRWSR__IMG_UNKNOWN;

	/** not enough time to implement - @todo */
	var $browsertype_name  = _JS_DB_BRTYP__NAME_UNKNOWN;

	/** not enough time to implement - @todo */
	/** Name of image file without extension eg.: "unknown"; "explorer"; "other"; "pda"; See defines _JS_DB_BRWSR__TYPE_ for all available names. Extension is taken from directory name. */
	var $browsertype_img   = _JS_DB_BRTYP__IMG_UNKNOWN;
}



/**
 *  This class contain (hold) data about Top Level Domains (TLD)
 *
 *  This class is only container for data - to pass data through methods etc.
 *
 *  Members of this class corespond to database table #__jstats_topleveldomains (will be renamed to #__jstats_tlds) column names
 *
 *  NOTICE:
 *     Creating new object create unknown TLD. This is proper feature.
 */
class js_Tld
{
	/** Primary Key from #__jstats_tldstable - integer. */
	var $tld_id    = _JS_DB_TLD__ID_UNKNOWN;

	/** Shortcuted name. Always lowercase eg.: "us", "de", "pl", "" (empty for unknown) */
	var $tld       = _JS_DB_TLD__TLD_UNKNOWN;

	/** Human redable country name eg.: "United States", "Germany", "Unknown" */
	var $tld_name  = _JS_DB_TLD__NAME_UNKNOWN;

	/** NOTICE: This variable is only for code clarity - it contains the same as $tld! Name of image file without extension eg.: "us"; "de"; "pl"; "unknown"; Extension is taken from directory name. */
	var $tld_img   = _JS_DB_TLD__TLD_UNKNOWN;
}


/** This function return true if debug mode is turned on */
function js_isJSDebugOn() {
	$isJSDebugOn = false;
	
	if( defined( '_JEXEC' ) ) {
		// Joomla! 1.5
		$conf =& JFactory::getConfig();
		$isJSDebugOn = (boolean) $conf->getValue('config.debug');
	} else if( defined( '_VALID_MOS' ) ) {
		// Joomla! 1.0
		global $mainframe;
		$isJSDebugOn = (boolean) $mainframe->getCfg( 'debug' );
	} else if( defined( '_JS_STAND_ALONE' ) ) {
		//stand alone
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'stand.alone.configuration.php' );
		$JSStandAloneConfiguration = new js_JSStandAloneConfiguration();
		$isJSDebugOn = (boolean) $JSStandAloneConfiguration->JConfigArr['debug'];
	}
	
	return $isJSDebugOn;
}


/**
 *  Print info when Debug is turned on.
 *  $title - use '' to not display title in bold (<b></b>)
 *  $pre   - use '' to not display pre in preformated block (tabulations, spaces and end of lines are visible) (<pre></pre>)
 *
 *  $pre accept also objects!!
 */
function js_echoJSDebugInfo($title, $pre='') {
	
	if (js_isJSDebugOn()) {
		$msg = '<br/>DEBUG info JoomlaStats: <b>'.$title.'</b>';
		if ( $pre !== '' ) {
			if ( (is_object($pre) == true) || (is_array($pre) == true)) {
				$msg .= '<pre>'.print_r($pre, true).'</pre>';
			} else {
				$msg .= ': \''.$pre.'\'';
			}
		}
		$msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			
		echo $msg;
	}
}


/** I have feeling that this function will be widely used in version for j1.0 and 1.5 */
// mic 20081004: not do i .... see function below
function js_getJoomlaVesrion_IsJ15x() {
	if( defined( '_JEXEC' ) ) {
		// Joomla! 1.5
		return true;
	}else{
		// Joomla! 1.0
		return false;
	}
}

if( !function_exists( 'isJ15' ) ) {
	/**
	 * helper function to determine if Joomla 1.5.x is running
	 *
	 * @since JS 0.99.001_def
	 * @return bool
	 */
	function isJ15() {
		if( defined( '_JEXEC' ) ) {
			// yeah, we have Joomla! 1.5
			return true;
		}else{
			// sorry, only Joomla! 1.0
			return false;
		}
	}
}


// needed if php4 is used, because stripos is a php5 > only function
if( !function_exists( 'stripos' ) ) {
	function stripos( $haystack, $needle, $offset = 0 ) {
		return strpos( strtolower( $haystack ), strtolower( $needle ), $offset );
	}
}


