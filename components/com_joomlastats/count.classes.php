<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}


require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. 'administrator' .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_joomlastats' .DIRECTORY_SEPARATOR. 'base.classes.php' );
require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. 'administrator' .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_joomlastats' .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'db.constants.php' );



class js_JSCountVisitor extends JSBasic
{
	// useragent
	var $UserAgent		= null;	// User agent (i.e. browser)
	var $UserId			= 0;	// User ID if user is logged in
	var $IpAddress		= null;	// IP address
	var $RequestedPage	= null; // Requested page URL
	var $hourdiff		= 0; // Offset to GMT (must be int!)
	var $JSConf			= null; // 'JS' configuration object. Holds system and user settings

	
	function __construct() {
		parent::__construct();
		global $mainframe;

		if ( defined('_JS_STAND_ALONE') ) {//outside joomla we can set this member manualy
			$JSStandAloneConfiguration = new js_JSStandAloneConfiguration();
			$this->hourdiff = $JSStandAloneConfiguration->JConfigArr['offset'];
		} else {
			$this->hourdiff = $mainframe->getCfg( 'offset' );
		}

		if (js_isJSDebugOn()) { //show on front page if Joomla CMS cache is ON
			$cache_txt = 'Joomla CMS cache is OFF';
			if ( !defined('_JS_STAND_ALONE') ) {//in stand alone version cache is always off
				if ( js_getJoomlaVesrion_IsJ15x() ) {
					//Joomla CMS 1.5
					global $mainframe;
					if ($mainframe->getCfg('caching'))
						$cache_txt = 'Joomla CMS 1.5 cache is <span style="color: red;">ON</span>';
				} else {
					//Joomla CMS 1.0
					global $mosConfig_caching;
					if ( $mosConfig_caching )
						$cache_txt = 'Joomla CMS 1.0 cache is <span style="color: red;">ON</span>';
				}
			}
			js_echoJSDebugInfo($cache_txt);
		}

		$this->JSConf = new js_JSConf();
	}


	/**
	 * Count visitor that visit 'Joomla CMS':
	 *    - recognize visitor
	 *    - update JS DB about visitor
	 *    - update JS DB about page that visior request
	 *
	 * @return bool - true on success
	 */
	function countVisitor($PrintActivatedString = true)
	{
		$bResult = true;

		//there are many methods to activate JS. If anyone will be performed Write below string to page
		if ($PrintActivatedString == true) {
			$JSSystemConst = new js_JSSystemConst();
			echo $JSSystemConst->htmlFrontPageJSActivatedString;//print "<!-- JoomlaStatsActivated -->"
		}

		// get requested page
		$RequestedPage = '';
		$this->getVisitorRequestedPage( $RequestedPage );
		$this->RequestedPage = $RequestedPage;
		
		if ($RequestedPage != '')
		{
			$ignore = strpos($RequestedPage, 'jstatsIgnore=true', 0);
			// Do not make counting on marked pages
			if ($ignore > 0) {
				js_echoJSDebugInfo('This page is excluded from counting', '');
				return true;
			}
		}		
		
		// get user agent of visitor
		$VisitorUserAgent = '';
		$this->getVisitorUserAgent( $VisitorUserAgent );
		$this->UserAgent = $VisitorUserAgent;

		// get user ID
		$this->UserId = $this->GetJoomlaCmsUserId();

		// get IP adress of visitor
		$VisitorIp = null;
		$this->getVisitorIp($VisitorIp);
		$this->IpAddress = $VisitorIp;

		

		// check IP address; if not excluded, go on with registration
		//if ($RequestedPage != '') //why we count visitors only when $RequestedPage != ''? If someone visit CMS we should count him

		$isKnownVisitor = false;
		$visitor_id = null;
		$visitor_exclude = null;
		$bResult = $this->isKnownVisitor( $VisitorIp, $VisitorUserAgent, $isKnownVisitor, $visitor_id, $visitor_exclude );
		if ($bResult == false)
			return false;

		if ( $isKnownVisitor == false ) {
			js_echoJSDebugInfo('New Visitor', '');
			//new unique visitor
			
			// get visitor ------------------------------------------------
			$Visitor = null;
			$updateTldInJSDatabase = true;
			$this->recognizeVisitor( $VisitorIp, $VisitorUserAgent, $updateTldInJSDatabase, $Visitor );
			
			//additional members (I am not sure if we need them)
			$Visitor->RequestedPage = $this->RequestedPage;
			$Visitor->cms_userid = $this->GetJoomlaCmsUserId();
			
			// insert new unique visitor ------------------------------------------------
			$this->insertNewVisitor( $Visitor );
			
			$visitor_id = $Visitor->visitor_id;
			$visitor_exclude = $Visitor->visitor_exclude;
		} else {
			js_echoJSDebugInfo('Visitor already known', '');
		}
		
		if ($visitor_id == 0)
			js_echoJSDebugInfo('Something is wrong with Visitor recognition or storing data about Visitor', '');
		
		if ($visitor_exclude == 1)
			js_echoJSDebugInfo('This Visitor is excluded from counting', '');
		       
		if (($visitor_id != 0) && ($visitor_exclude != 1))
		{
			js_echoJSDebugInfo('Perform Visitor counting process', '');
			// visitor/bot is not excluded

			// get a visit id so we can link the requested pages
			// and then register the pages requested by the visitor
			$this->PageRequest($this->visits());
		}
	}

	/**
	 * Get user agent from Visitor (user that refresh page)
	 *   eg.: "mozilla/5.0 (windows; u; windows nt 5.1; en-gb; rv:1.8.1.15) gecko/20080623 firefox/2.0.0.15"
	 *
	 * @param out string $UserAgent
	 * @return bool - true on success
	 */
	function getVisitorUserAgent( &$UserAgent ) {

		if( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			if( $_SERVER['HTTP_USER_AGENT'] != NULL ) {
				$UserAgent = trim( strtolower( $_SERVER['HTTP_USER_AGENT'] ) );
				js_echoJSDebugInfo('UserAgent string', $UserAgent);
				return true;
			}
		}
		
		js_echoJSDebugInfo('UserAgent string', '');
		return false;
	}

	/** If User is logged into 'Joomla CMS', Joomla CMS UserId is returned. //If user is not logged into 'Joomla CMS', 0 is returned */
	function GetJoomlaCmsUserId()
	{
		if ( defined( '_VALID_MOS' ) || defined( '_JEXEC' ) ) {//outside joomla we can not check if user is logged
			global $mainframe;

			//works on j1.0.15 and j1.5.6 //if user is not logged $user->id return 0
			$user = &$mainframe->getUser();
			return (int)$user->id;
		}

		return 0; //JS stand alone version (defined('_JS_STAND_ALONE'))
	}

	function getVisitorRequestedPage( &$RequestedPage )
	{
		global $mosConfig_sef;
		
		$RequestedPageTmp = '';

		if ((isset($_SERVER['REQUEST_URI'])) && ($_SERVER['REQUEST_URI'] != NULL))
		{
			$RequestedPageTmp = $_SERVER['REQUEST_URI'];
		}
		else if ((isset($_SERVER['PHP_SELF'])) && ($_SERVER['PHP_SELF'] != NULL))
		{
			$RequestedPageTmp = $_SERVER['PHP_SELF'];

			if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != NULL))
			{
				$RequestedPageTmp .= '?'.$_SERVER['QUERY_STRING'];
			}
		}
		else if ((isset($_SERVER['SCRIPT_NAME'])) && ($_SERVER['SCRIPT_NAME'] != NULL))
		{
			$RequestedPageTmp = $_SERVER['SCRIPT_NAME'];

			if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != NULL))
			{
				$RequestedPageTmp .= '?'.$_SERVER['QUERY_STRING'];
			}
		}

		if (($RequestedPageTmp == "/") || ($RequestedPageTmp == "\\"))
			$RequestedPageTmp .= "index.php";

		if ((strtolower(substr($RequestedPageTmp, -3)) == 'ico') ||
		    (strtolower(substr($RequestedPageTmp, -3)) == 'png') ||
		    (strtolower(substr($RequestedPageTmp, -3)) == 'gif') ||
		    (strtolower(substr($RequestedPageTmp, -3)) == 'jpg'))
			$RequestedPageTmp = '';

		if ($RequestedPageTmp != '')
		{
			// Search Engine Friendly url
			/*
			// removed by mic
			if (intval($mosConfig_sef) == 1)
			{
				if (substr($RequestedPageTmp, 0, 1) == '/') $RequestedPageTmp = sefRelToAbs(substr($RequestedPageTmp, 1));
				else					       $RequestedPageTmp = sefRelToAbs($RequestedPageTmp);
			}
			*/

			// added by mic 2006.12.07
			if (intval($mosConfig_sef) == 1)
			{
				$RequestedPageTmp = sefRelToAbs('index.php?' . $_SERVER['QUERY_STRING']);
				// echo '<br />** joomlastats.php - getVisitorRequestedPage [ '.$RequestedPageTmp.' ] **<br />'; // debug
			}

			$RequestedPageTmp = str_replace('http://', ':#:', $RequestedPageTmp);
			$RequestedPageTmp = str_replace('//', '/', $RequestedPageTmp);
			$RequestedPageTmp = str_replace(':#:', 'http://', $RequestedPageTmp);
			
			$RequestedPage = $RequestedPageTmp;
			return true;
		}
		
		return false;
	}

	/**
	 *
	 *  @param $VisitorIp - valid only when true is returned
	 *  @return true on success
	 */
	function getVisitorIp(&$VisitorIp)
	{
		$Ip_tmp = null;
		// get usefull vars:
		$client_ip	 = isset($_SERVER['HTTP_CLIENT_IP'])	   ? $_SERVER['HTTP_CLIENT_IP']	      : NULL;
		$x_forwarded_for = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : NULL;
		$remote_addr	 = isset($_SERVER['REMOTE_ADDR'])	   ? $_SERVER['REMOTE_ADDR']	      : NULL;

		// then the script itself
		if (!empty($x_forwarded_for) && strrpos($x_forwarded_for, '.') > 0)
		{
			$arr = explode(',', $x_forwarded_for);
			$Ip_tmp = trim(end($arr));
		}

		if (!$this->isIpAddressValidRfc3330($Ip_tmp) && !empty($client_ip))
		{
			$ip_expl = explode('.', $client_ip);
			$referer = explode('.', $remote_addr);

			if ($referer[0] != $ip_expl[0])
			{
				$Ip_tmp = trim(implode('.', array_reverse($ip_expl)));
			}
			else
			{
				$arr = explode(',', $client_ip);
				$Ip_tmp = trim(end($arr));
			}
		}

		if (!$this->isIpAddressValidRfc3330($Ip_tmp) && !empty($remote_addr))
		{
			$arr = explode(',', $remote_addr);
			$Ip_tmp = trim(end($arr));
		}

		unset($client_ip, $x_forwarded_for, $remote_addr, $ip_expl, $referer);

		$VisitorIp = $Ip_tmp;
		return true;//@todo false never is returned but should be (I think it is possible to configure PHP that IP is unable to possess)
	}


	/**
	 * This function check if such visitor visit Joomla CMS any time before
	 *
	 * @param        $VisitorIp
	 * @param string $VisitorUserAgent  values: '', null, "mozilla/5.0 (windows; u;..." //if null this UserAgent is not considered during comparation
	 * @param bool   $isKnownVisitor
	 * @return bool - true on success
	 */
	function isKnownVisitor( $VisitorIp, $VisitorUserAgent, &$isKnownVisitor, &$visitor_id, &$visitor_exclude ) {

		$this->_getDB();
		
		$query = 'SELECT exclude, type, tld, id, useragent'
		. ' FROM #__jstats_ipaddresses'
		. ' WHERE ip = \'' . $VisitorIp . '\''
		//. ' AND useragent = \'' . $VisitorUserAgent . '\'' for performance we do this in PHP (MySQL very bad deal with something like this. Additional column user_agent is not indexed (and it should not be indexed)). In main cases there shoud be one entry so PHP better
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;

		if (!$rows) {
			$isKnownVisitor = false;
			return true;
		}
		
		//In main cases there shoud be one entry so PHP better			
		foreach( $rows as $row) {
			if( $row->useragent == $VisitorUserAgent) {
				$isKnownVisitor = true;//yes we found
				$visitor_id = $row->id;
				$visitor_exclude = $row->exclude;
				return true;
			}
		}
		
		$isKnownVisitor = false;
		return true;
	}

	/**
	 * Find and return TLD. This function operate on string.
	 *
	 * @param string $visitor_nslookup   - string returned by PHP method gethostbyaddr( $visitor_ip ); eg.: "crawl-66-249-70-72.googlebot.com", "sewer.com.eu", "66.249.70.72" (for this false will be returned)
	 * @param string $tld                - eg.: "us", "de", "pl"
	 * @return bool - true on success
	 */
	function getTldFromNslookupString( $visitor_nslookup, &$tld ) {

		$pos = strrpos( $visitor_nslookup, '.' ) + 1;
		
		if( $pos > 1 ) {
			$xt = trim( substr( $visitor_nslookup, $pos ) );
		
			if( ereg( '([a-zA-Z])', $xt ) ) {
				$tld = strtolower( $xt );
				return true;
			}
		}

		return false;
	}

	/**
	 * Find and return TLD. This function get Visitor IP and check it in JS database.
	 *
	 * @param string $visitor_ip    - eg.: "66.249.70.72"
	 * @param string $country_code  - eg.: "us", "de", "pl"     NOTICE: this is not TLD!!!
	 * @return bool - return false if there is no entry in JS DB for such IP
	 */
	function getCountryCodeFromJSDatabase( $visitor_ip, &$country_code ) {

		$this->_getDB();

		$query = 'SELECT country_code2'
		. ' FROM #__jstats_iptocountry'
		. ' WHERE inet_aton(\'' . $visitor_ip . '\') >= ip_from'
		. ' AND inet_aton(\'' . $visitor_ip . '\') <= ip_to'
		;
		$this->db->setQuery( $query );
		$tmp_country_code = $this->db->loadResult();
		if ($this->db->getErrorNum() > 0)
			return false;

		if( $tmp_country_code ) {
			$country_code = $tmp_country_code;
			return true;
		}

		return false;
	}

	/**
	 * Query RIPE servers in internet about tld for given IP address
	 *
	 *    EU is used as the IANA generic country code; it is always returned
	 *      for 0.0.0.0 to 255.255.255.255 and some other generic IANA networks
	 *    
	 *    AP is used as the APNIC generic country code; the real
	 *      country code can be obtained from the 'route' entry
	 *
	 * @param string $visitor_ip   - eg.: "66.249.70.72"
	 * @param string $tld          - eg.: "us", "de", "pl"
	 *
	 * NOTICE:
	 *    This function rise PHP warning very often! eg.:
	 *
	 * @return bool - return false if something goes wrong or could not determine tld
	 */
	function getTldFromRipeServers( $visitor_ip, &$ipFrom, &$ipTo, &$tld ) {
		$visitor_tld	= '';
		$countryCode	= '';
		$ipFrom			= '0.0.0.0';
		$ipTo			= '255.255.255.255';
		$whois			= array();
		$whoisResult	= array();

		// do RIPE Whois lookup for the IP address

		// Andreas: removed VERIO added AFRINIC,NTTCOM
		// mic 20081014: IMPORTANT the \n at the end of the query!
		$query		= '-s RIPE,ARIN,APNIC,RADB,JPIRR,AFRINIC,NTTCOM -T inetnum -G ' . $visitor_ip . "\n";
		$countryCode = $this->queryWhois( 'whois.ripe.net', $query, $ipFrom, $ipTo, $whoisResult );

		if( $countryCode === 'LACNIC' || $countryCode === 'EU' || $countryCode === 'AP' || $countryCode ===''){
			$query			= $visitor_ip . "\n";
			$countryCode	= $this->queryWhois( 'whois.lacnic.net', $query, $ipFrom, $ipTo, $whoisResult );
		}else{
            $whois = $whoisResult;
		}

		if( $countryCode === 'AfriNIC' || $countryCode === 'EU' || $countryCode === 'AP' || $countryCode===''){
			$query = '-T inetnum -r ' . $visitor_ip . "\n";
			$countryCode = $this->queryWhois( 'whois.afrinic.net', $query, $ipFrom, $ipTo, $whois );
		}else{
            $whois = $whoisResult;
		}

		js_echoJSDebugInfo('Answer from RIPE server', $whois);

        //if( array_key_exists( 'descr', $whois ) ) {
        //	$visitor_nslookup .= "\n" . $whois['descr'];
        //}
        //if( array_key_exists( 'role', $whois ) ) {
        //	$visitor_nslookup .= "\n" . $whois['role'];
        //}

		$tld = strtolower( $countryCode );
		return true;//@todo false should be returned on fail
	}

	/**
	 * update TLDs in JS database
	 *
	 * @param string $
	 * @param string $
	 * @return bool - return false on fail
	 */
	function updateTldInJSDatabase( $ipFrom, $ipTo, $countryCode ) {

		$this->_getDB();

		// EU is used as the IANA generic country code; it is always returned
		// for 0.0.0.0 to 255.255.255.255 and some other generic IANA networks

		// AP is used as the APNIC generic country code; the real
		// country code can be obtained from the 'route' entry

		if( $countryCode !== '' && $countryCode !== 'eu' && $countryCode !== 'ap' ) {
			// found country code, enter it into iptocountry
			$query = 'INSERT INTO #__jstats_iptocountry (ip_from, ip_to, country_code2)'
			. ' VALUES (' . sprintf( "%u", ip2long( $ipFrom ) ) . ',' . sprintf( "%u", ip2long( $ipTo ) ) . ',\''	. $countryCode . '\')'
			;
			$this->db->setQuery( $query );
			$this->db->query();
			if ($this->db->getErrorNum() > 0)
				return false;
		}

		return true;
	}


	/**
	 * This function make visitor recognition. Basing on $IpAddress and $UserAgent it return information about
	 *   operationg system, browser, user type etc.
	 *
	 * recognize because data are taken directly from function arguments (not from user browser, PHP settings, cookies, javascript etc)
	 *
	 * @param out $Visitor - object of class js_Visitor
	 *
	 * @return bool - true on success
	 */
	function recognizeVisitor( $IpAddress, $UserAgent, $updateTldInJSDatabase, &$Visitor ) {

		js_echoJSDebugInfo('Recognizing visitor', '');
		
		$visitor_tld		= '';//@todo define or whole object of class js_Tld should be here
		$visitor_nslookup	= $IpAddress;

		if( $this->isIpAddressIntranet( $IpAddress ) ) {
			$visitor_tld		= 'intranet';//@todo define or whole object of class js_Tld should be here
			js_echoJSDebugInfo('This IP address is INTRANET. We do not search TLD for this address', '');
		} else if( $this->isIpAddressLocalHost( $IpAddress ) ) {
			$visitor_tld		= 'localhost';//@todo define or whole object of class js_Tld should be here
			js_echoJSDebugInfo('This IP address is LOCALHOST. We do not search TLD for this address', '');
		} else if( !$this->isIpAddressValidRfc3330( $IpAddress ) ) {
			js_echoJSDebugInfo('This IP address is NOT VALID according to RFC3330. We do not search TLD for this address', '');
		} else {
			js_echoJSDebugInfo('This IP address is valid.', '');
			if( $this->JSConf->enable_whois ) {
				$visitor_nslookup = gethostbyaddr( $IpAddress );
				$this->getTldFromNslookupString( $visitor_nslookup, $visitor_tld );

				if( $visitor_tld === '' || $visitor_tld === 'eu' || strlen( $visitor_tld ) > 2 ) {

					//below function return CountryCode not TLD. Is below code correct?
					$tld_res = $this->getCountryCodeFromJSDatabase( $IpAddress, $visitor_tld );

					if( $tld_res == false ) {
						$ipFrom	= '0.0.0.0';
						$ipTo	= '255.255.255.255';
						$this->getTldFromRipeServers( $IpAddress, $ipFrom, $ipTo, $visitor_tld );
						$this->updateTldInJSDatabase( $ipFrom, $ipTo, $visitor_tld );
					}

					// GB is the only country code not matching the country TLD
					if( strcasecmp($visitor_tld, 'gb') == 0 ) {
						$visitor_tld = 'uk';
					}
				}
			} else {
				js_echoJSDebugInfo('WHOIS option is turned OFF', '');
			}
		}


		$Tld = $this->getTldFromTld( $visitor_tld );

		// determine if bot or browser
		$type = _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR;

		// get browser --------------------------------------------------------------------------
		$BrowserVersion = '';
		$Browser = $this->getBrowserFromUserAgent( $UserAgent, $BrowserVersion );
		if ($Browser != null)
			$type = _JS_DB_IPADD__TYPE_REGULAR_VISITOR;
		//$Browser = new js_Browser();//we should not create browser object!!
		

		// look for bot if this is not regular visitor (if still unknown) -----------------------
		$BotId = 0;
		$BotName = '';
		if( $type == _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR ) {
			$this->getBotFromUserAgent($UserAgent, $type, $BotId, $BotName );
		}

		// get OS version -----------------------------------------------------------------------
		$OS = $this->getOsFromUserAgent($UserAgent);


		// mic 20081014: get screen resolution
		//$this->getJSScreenresolution();

		


		// create visitor object ------------------------------------------------

		if ($OS == null) {
			//create unknown system
			$OS = new js_OS();
		}

		if ($Tld == null) {
			//create unknown tld
			$Tld = new js_Tld();
		}

		
		$Visitor = new js_Visitor();
		$Visitor->visitor_id = 0;
		$Visitor->visitor_ip = $IpAddress;
		$Visitor->visitor_useragent = $UserAgent;
		//$Visitor->visitor_exclude = 0;//@todo define should be here //member not set, so default value will be used
		$Visitor->visitor_type = $type; //_JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR, _JS_DB_IPADD__TYPE_REGULAR_VISITOR, _JS_DB_IPADD__TYPE_BOT_VISITOR;
		$Visitor->OS = $OS;
		$Visitor->Browser = $Browser;
		$Visitor->Tld = $Tld;
			$Visitor->browser_version = $BrowserVersion;//additional parameter
		$Visitor->bot_id = $BotId;
		$Visitor->bot_name = $BotName;
		$Visitor->nslookup = $visitor_nslookup;

		js_echoJSDebugInfo('Visitor', $Visitor);

		return true;
	}

	/**
	 * Add new Visitor to #__jstats_ipaddresses table
	 *
	 * @param object $Visitor - object of class $js_Visitor
	 * @return bool - return true on success and object $Visitor has set member $Visitor->visitor_id
	 */
	function insertNewVisitor( &$Visitor ) {

		$this->_getDB();
		
		$browser = '';
		if ($Visitor->visitor_type == _JS_DB_IPADD__TYPE_REGULAR_VISITOR)
			$browser = $Visitor->Browser->browser_name .' '. $Visitor->browser_version;
		if ($Visitor->visitor_type == _JS_DB_IPADD__TYPE_BOT_VISITOR)
			$browser = $Visitor->bot_name;

		$query = 'INSERT INTO #__jstats_ipaddresses'
		. ' (ip, nslookup, useragent, tld, system, browser, type)'
		. ' VALUES (\'' . $Visitor->visitor_ip . '\','
			. ' \'' . $Visitor->nslookup . '\','
			. ' \'' . $Visitor->visitor_useragent . '\','
			. ' \'' . $Visitor->Tld->tld . '\','
			. ' \'' . $Visitor->OS->os_name . '\','
			. ' \'' . $browser . '\','
			. $Visitor->visitor_type
		. ')'
		;

		$this->db->setQuery( $query );
		$this->db->query();

		if ($this->db->getErrorNum() > 0)
			return false;

		$Visitor->visitor_id = $this->db->insertid();

		return true;
	}

	/**
	 * Update Visitor to #__jstats_ipaddresses table
	 *
	 * //@todo: Is it a mistake that we need to update prevoiusly entered entry? (I am unsure but maybe this this is mistake in logic)
	 *
	 * @param object $Visitor - object of class $js_Visitor
	 * @return bool - true on success
	 */
	function updateVisitor( $Visitor ) {

		$this->_getDB();
		
		$browser = '';
		if ($Visitor->visitor_type == _JS_DB_IPADD__TYPE_REGULAR_VISITOR)
			$browser = $Visitor->Browser->browser_name .' '. $Visitor->browser_version;
		if ($Visitor->visitor_type == _JS_DB_IPADD__TYPE_BOT_VISITOR)
			$browser = $Visitor->bot_name;

		$query = 'UPDATE #__jstats_ipaddresses'
		. ' SET nslookup = \'' . $Visitor->nslookup . '\','
		. ' tld = \'' . $Visitor->Tld->tld . '\','
		. ' system = \'' . $Visitor->OS->os_name . '\','
		. ' browser = \'' . $browser . '\','
		. ' type = ' . $Visitor->visitor_type . ','
		. ' WHERE ip = \''. $Visitor->visitor_ip . '\''
		. ' AND useragent = \'' . $Visitor->visitor_useragent . '\''
		;

		$this->db->setQuery( $query );
		$this->db->query();

		if ($this->db->getErrorNum() > 0)
			return false;
			
		return true;
	}

	/**
	 * @param string  $UserAgent  eg.: "mozilla/5.0 (windows; u; windows nt 5.1; en-gb; rv:1.8.1.15) gecko/20080623 firefox/2.0.0.15"
	 *
	 * @return object of class js_OS or null when fail
	 */
	function getOsFromUserAgent( $UserAgent ) {

		if (strlen($UserAgent) == 0)//if ($UserAgent == '') - this not always works!
			return null;

		$this->_getDB();

		$query = ''
		. ' SELECT'
		. '   LENGTH(o.sys_string) AS strlen,'
		. '   o.sys_id        AS os_id,'
		. '   o.sys_type      AS ostype_id,'
		. '   o.sys_string    AS os_key,'
		. '   o.sys_fullname  AS os_name,'
		. '   o.sys_img       AS os_img'
		. ' FROM'
		. '   #__jstats_systems o'
		. ' WHERE'
		. '   o.sys_id > 0'
		. ' ORDER BY'
		. '   strlen DESC'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return null;
		
		foreach( $rows as $row) {
			if( strpos( $UserAgent, $row->os_key, 0 ) !== false) {
				$OS = new js_OS();//we copy each member manualy to be sure about that what is inside. Additional we use getEscaped() method
				$OS->os_id = $row->os_id;
				$OS->ostype_id = $row->ostype_id;
				$OS->os_key = $row->os_key;
				$OS->os_name = $this->db->getEscaped( $row->os_name );
				$OS->os_img = $row->os_img;

				#__jstats_ostype (with entries)
				$__jstats_ostype = unserialize(_JS_DB_TABLE__OSTYPE);//whole table
				//fill missing entries in $OS object
				$OS->ostype_name = $__jstats_ostype[$OS->ostype_id]['ostype_name'];
				$OS->ostype_img = $__jstats_ostype[$OS->ostype_id]['ostype_img'];

				return $OS;
			}
		}
	
		return null;
	}

	/**
	 * @param string  $tld_str  eg.: "localhost"; "us", "de"
	 *
	 * @return object of class js_Tld or null when fail
	 */
	function getTldFromTld( $tld_str ) {

		$this->_getDB();

		$query = ''
		. ' SELECT'
		. '   t.tld_id        AS tld_id,'
		. '   t.tld           AS tld,'
		. '   t.fullname      AS tld_name'
		. ' FROM'
		. '   #__jstats_topleveldomains t'
		. ' WHERE'
		. '   t.tld=\''.$tld_str.'\''
		;
		$this->db->setQuery( $query );
		$obj = null;
		if (js_getJoomlaVesrion_IsJ15x() == true)
			$obj = $this->db->loadObject();
		else
			$this->db->loadObject($obj);
		if ($this->db->getErrorNum() > 0)
			return null;

		if (!$obj)
			return null;

		$Tld = new js_Tld();
		$Tld->tld_id = $obj->tld_id;
		$Tld->tld = $obj->tld;
		$Tld->tld_name = $obj->tld_name;
		$Tld->tld_img = $obj->tld;
			
		return $Tld;		
	}


	/**
	 * @param in  string  $UserAgent      eg.: "mozilla/5.0 (windows; u; windows nt 5.1; en-gb; rv:1.8.1.15) gecko/20080623 firefox/2.0.0.15"
	 * @param out string  $BrowserVersion eg.: "7.0" (Connected with $BrowserName gives "Internet Explorer 7.0") //could be empty
	 *
	 * @return object of class js_Browser if visitor has browser (if visitor is bot/spider null is returned)
	 */
	function getBrowserFromUserAgent( $UserAgent, &$BrowserVersion ) {

		if (strlen($UserAgent) == 0)//if ($UserAgent == '') - this not always works!
			return null;

		$this->_getDB();

		$query = ''
		. ' SELECT'
		. '   b.browser_id        AS browser_id,'
		. '   b.browser_type      AS browsertype_id,'
		. '   b.browser_string    AS browser_key,'
		. '   b.browser_fullname  AS browser_name,'
		. '   b.browser_img       AS browser_img'
		. ' FROM'
		. '   #__jstats_browsers b'
		. ' WHERE'
		. '   b.browser_id > 0'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return null;

		foreach( $rows as $row ) {
			if( strpos( $UserAgent, $row->browser_key, 0 ) !== false ) {
				//this is browser, set and return arguments
				$Browser = new js_Browser();//we copy each member manualy to be sure about that what is inside. Additional we use getEscaped() method
				$Browser->browser_id = $row->browser_id;
				$Browser->browsertype_id = $row->browsertype_id;
				$Browser->browser_key = $row->browser_key;
				$Browser->browser_name = $this->db->getEscaped( $row->browser_name );
				$Browser->browser_img = $row->browser_img;
				
				#__jstats_browserstype (with entries)
				$__jstats_browserstype = unserialize(_JS_DB_TABLE__BROWSERSTYPE);//whole table
				//fill missing entries in $Browser object
				$Browser->browsertype_name = $__jstats_browserstype[$Browser->browsertype_id]['browsertype_name'];
				$Browser->browsertype_img  = $__jstats_browserstype[$Browser->browsertype_id]['browsertype_img'];

				{//try to get browser version
					$version = array();
					if( preg_match( '/' . $Browser->browser_key . '[\/\sa-z]*([\d\.]*)/i', $UserAgent, $version ) ) {
						if (isset($version[1])) {
							$BrowserVersion = $version[1];
						}
					}
				}

				return $Browser;
			}
		}
			
		return null;
	}
	
	/**
	 * @param string  $UserAgent  eg.: "mozilla/5.0 (windows; u; windows nt 5.1; en-gb; rv:1.8.1.15) gecko/20080623 firefox/2.0.0.15"
	 * @param integer $Type       one of: _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR, _JS_DB_IPADD__TYPE_REGULAR_VISITOR, _JS_DB_IPADD__TYPE_BOT_VISITOR; Defines are in db.constants.php file
	 * @param integer $BotId      value from column 'bot_id' from table '#__jstats_bots'
	 * @param string  $BotName    eg.: "Googlebot (Google)"
	 *
	 * NOTICE:
	 *    Only when $Type = _JS_DB_IPADD__TYPE_BOT_VISITOR is returned, parameters $BotId and $BotName are valid!
	 *
	 * @return bool - true on success
	 */
	function getBotFromUserAgent($UserAgent, &$Type, &$BotId, &$BotName ) {

		$this->_getDB();

		$Type = _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR;

		// get bot
		$query = 'SELECT LENGTH(bot_string) AS strlen, bot_string, bot_id, bot_fullname'
		. ' FROM #__jstats_bots'
		. ' ORDER BY strlen DESC'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();
		if ($this->db->getErrorNum() > 0)
			return false;

		foreach( $rows as $row) {
			if( strpos( $UserAgent, $row->bot_string, 0 ) !== false ) {
				$Type    = _JS_DB_IPADD__TYPE_BOT_VISITOR;
				$BotId   = $row->bot_id;
				$BotName = $this->db->getEscaped( $row->bot_fullname );
				break;
			}
		}

		//@todo - below values should be moved to database (?). Before moving we should check if such entries exist (at all)
		if( $Type == _JS_DB_IPADD__TYPE_NOT_IDENTIFIED_VISITOR ) {
			if( strpos( $UserAgent, 'robot',  0 ) !== false ) {
				$BotName = JTEXT::_( 'Unknown - Identified as robot' );
			}elseif( strpos( $UserAgent, 'crawl',  0 ) !== false ) {
				$BotName = JTEXT::_( 'Unknown - Identified as crawler' );
			}elseif( strpos( $UserAgent, 'spider', 0 ) !== false ) {
				$BotName = JTEXT::_( 'Unknown - Identified as spider' );
			}elseif( strpos( $UserAgent, 'bot', 0 ) !== false ) {
				$BotName = JTEXT::_( 'Unknown - Identified as bot' );
			}

			if( $BotName != '' ) {
				$Type = _JS_DB_IPADD__TYPE_BOT_VISITOR;
			}
		}

		return true;
	}

	/**
	 * checks if ip.address is a local address, therefore we do not check the whois or make a tld-lookup!
	 * needed for e.g. intranet cms
	 *
	 * @param string $ip
	 * @return bool
	 */
	function isIpAddressIntranet( $ipAddressStr ) {

		// mic: ONLY FOR DEBUG SET TO FALSE
		//return false;

		$local = '/^10|^169\.254|^172\.16|^172\.17|^172\.18|^172\.19|^172\.20|^172\.21|^172\.22|^172\.23|^172\.24|^172\.25|^172\.26|^172\.27|^172\.28|^172\.29|^172\.30|^172\.31|^192|0:0:0:0:0:0:0:1/';

		if( preg_match( $local, $ipAddressStr ) ) {
			return true;
		}

		return false;
	}

	/**
	 * checks if ip.address is a local address, therefore we do not check the whois or make a tld-lookup!
	 * needed for e.g. intranet cms
	 *
	 * @param string $ip
	 * @return bool
	 */
	function isIpAddressLocalHost( $ipAddressStr ) {

		$substr4 = substr( $ipAddressStr, 0, 4 );

		if ( $substr4 === '127.' )
			return true;
		
		return false;
	}
	
	/**
	 * checks if the given ip-address is valid
	 *
	 * From where we should get list of reserved blocks?
	 *    1) http://www.rfc-editor.org/rfc/rfc3330.txt
	 *    2) http://www.countryipblocks.net/bogons.php ?  (could be depracated, no date, is this official?)
	 * 	 
	 * 	 
	 * Part of: http://www.rfc-editor.org/rfc/rfc3330.txt
	 * 	 
	 *    Address Block             Present Use                       Reference
	 *    ---------------------------------------------------------------------
	 *    0.0.0.0/8            "This" Network                 [RFC1700, page 4]
	 *    10.0.0.0/8           Private-Use Networks                   [RFC1918]
	 *    14.0.0.0/8           Public-Data Networks         [RFC1700, page 181]
	 *    24.0.0.0/8           Cable Television Networks                    --
	 *    39.0.0.0/8           Reserved but subject
	 *                            to allocation                       [RFC1797]
	 *    127.0.0.0/8          Loopback                       [RFC1700, page 5]
	 *    128.0.0.0/16         Reserved but subject
	 *                            to allocation                             --
	 *    169.254.0.0/16       Link Local                                   --
	 *    172.16.0.0/12        Private-Use Networks                   [RFC1918]
	 *    191.255.0.0/16       Reserved but subject
	 *                            to allocation                             --
	 *    192.0.0.0/24         Reserved but subject
	 *                            to allocation                             --
	 *    192.0.2.0/24         Test-Net
	 *    192.88.99.0/24       6to4 Relay Anycast                     [RFC3068]
	 *    192.168.0.0/16       Private-Use Networks                   [RFC1918]
	 *    198.18.0.0/15        Network Interconnect
	 *                            Device Benchmark Testing            [RFC2544]
	 *    223.255.255.0/24     Reserved but subject
	 *                            to allocation                             --
	 *    224.0.0.0/4          Multicast                              [RFC3171]
	 *    240.0.0.0/4          Reserved for Future Use        [RFC1700, page 4]
	 * 	 
	 *
	 * @param string $ipAddress
	 * @return string
	 */
	function isIpAddressValidRfc3330( $ipAddress ) {

		$substr2 = substr( $ipAddress, 0, 2 );
		$substr3 = substr( $ipAddress, 0, 3 );
		$substr4 = substr( $ipAddress, 0, 4 );
		$substr6 = substr( $ipAddress, 0, 6 );
		$substr8 = substr( $ipAddress, 0, 8 );
		$substr10 = substr( $ipAddress, 0, 10 );
		$substr12 = substr( $ipAddress, 0, 12 );
		$IpAsLong = sprintf( "%u", ip2long( $ipAddress ) );
		
		return ( ( $ipAddress != NULL ) &&
			( $substr2 !== '0.' )     // Reserved IP block
			&& ( $substr3 !== '10.' ) // Reserved for private networks
			&& ( $substr3 !== '14.' ) // IANA Public Data Network
			&& ( $substr3 !== '24.' ) // Reserved IP block
			&& ( $substr3 !== '27.' ) // Reserved IP block
			&& ( $substr3 !== '39.' ) // Reserved IP block
			&& ( $substr4 !== '127.' ) // Reserved IP block
			&& ( $substr6 !== '128.0.' ) // Reserved IP block
			&& ( $substr8 !== '169.254.' ) // Reserved IP block
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '172.16.0.0' ) ) ) // Private networks
				|| $IpAsLong > sprintf( "%u", ip2long( '172.31.255.255' ) ) ) 
			&& ( $substr8 !== '191.255.' ) // Reserved IP block
			&& ( $substr8 !== '192.0.0.' ) // Reserved IP block
			&& ( $substr8 !== '192.0.2.' ) // Reserved IP block
			&& ( $substr10 !== '192.88.99.' ) // Reserved IP block
			&& ( $substr8 !== '192.168.' ) // Reserved IP block
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '198.18.0.0' ) ) ) // Multicast addresses
				|| ( $IpAsLong > sprintf( "%u", ip2long( '198.19.255.255' ) ) ) )
			&& ( $substr12 !== '223.255.255.' ) // Reserved IP block
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '224.0.0.0' ) ) ) // Multicast addresses
				|| ( $IpAsLong > sprintf( "%u", ip2long( '239.255.255.255' ) ) ) )
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '240.0.0.0' ) ) ) // Reserved IP blocks
				|| ( $IpAsLong > sprintf( "%u", ip2long( '255.255.255.255' ) ) ) )
		);
		
				
		/* code from v2.2.3
		return ( ( $ipAddress != NULL ) &&
			// Reserved IP blocks
			( ( sprintf( "%u", ip2long( $ipAddress ) ) < sprintf( "%u", ip2long( '0.0.0.0' ) ) )
			|| ( sprintf( "%u", ip2long( $ipAddress ) ) > sprintf( "%u", ip2long( '2.255.255.255' ) ) ) )
			&& ( substr( $ipAddress, 0, 2 ) !== '5.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 2 ) !== '7.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 3 ) !== '10.' ) // Reserved for private networks
			&& ( substr( $ipAddress, 0, 3 ) !== '14.' ) // IANA Public Data Network
			&& ( substr( $ipAddress, 0, 3 ) !== '23.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 3 ) !== '27.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 3 ) !== '31.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 3 ) !== '36.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 3 ) !== '37.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 3 ) !== '42.' ) // Reserved IP block
			&& ( ( sprintf( "%u", ip2long( $ipAddress ) ) < sprintf( "%u", ip2long( '92.0.0.0') ) ) // Reserved IP blocks
				|| ( sprintf( "%u", ip2long( $ipAddress ) ) > sprintf( "%u", ip2long( '95.255.255.255' ) ) ) )
			&& ( ( sprintf( "%u", ip2long( $ipAddress ) ) < sprintf( "%u", ip2long( '100.0.0.0' ) ) ) // Reserved IP blocks
				|| ( sprintf( "%u", ip2long( $ipAddress ) ) > sprintf( "%u", ip2long( '120.255.255.255' ) ) ) )
			&& ( substr( $ipAddress, 0, 4 ) !== '127.' ) // Loop-back interfaces
			&& ( substr( $ipAddress, 0, 8 ) !== '169.254.' ) // Link-local addresses
			&& ( ( sprintf( "%u", ip2long( $ipAddress ) ) < sprintf( "%u", ip2long( '172.16.0.0' ) ) ) // Private networks
				|| ( sprintf( "%u", ip2long( $ipAddress ) ) > sprintf( "%u", ip2long( '172.31.255.255' ) ) ) )
			&& ( ( sprintf( "%u", ip2long( $ipAddress ) ) < sprintf( "%u", ip2long( '173.0.0.0' ) ) ) // Reserved IP blocks
				|| ( sprintf( "%u", ip2long( $ipAddress ) ) > sprintf( "%u", ip2long( '187.255.255.255' ) ) ) )
			&& ( substr( $ipAddress, 0, 8 ) !== '192.168.' ) // Private networks
			&& ( substr( $ipAddress, 0, 4 ) !== '197.' ) // Reserved IP block
			&& ( substr( $ipAddress, 0, 4 ) !== '223.' ) // Reserved IP block
			&& ( ( sprintf( "%u", ip2long( $ipAddress ) ) < sprintf( "%u", ip2long( '224.0.0.0' ) ) ) // Multicast addresses
				|| ( sprintf( "%u", ip2long( $ipAddress ) ) > sprintf( "%u", ip2long( '239.255.255.255' ) ) ) )
			&& ( ( sprintf( "%u", ip2long( $ipAddress ) ) < sprintf( "%u", ip2long( '240.0.0.0' ) ) ) // Reserved IP blocks
				|| ( sprintf( "%u", ip2long( $ipAddress ) ) > sprintf( "%u", ip2long( '255.255.255.255' ) ) ) )
		);
		*/
	}


   /**
    * Executes a WHOIS query
    *
    * Appended By DVW
    *
    * @param string $server
    * @param string $query
    * @return array
    *
    * @since 2.3.x: added maximum time to fsockopen from server config
    * @todo mic 20081013: maybe moving ths function into a own class AND into backend?
    */
    function executeWhois( $server, $query ) {
    	global $mainframe;

        $resultList = array();

        // mic 20081013: get maximum time for fsockopen - AT: NO, NO, NO!!!! We are on front page!
        //$timeout = ini_get( 'max_execution_time' );
		$timeout = 1;//value 0.1 is better but I am not sure if it is allowed

		js_echoJSDebugInfo('server', $server);
		js_echoJSDebugInfo('query', $query);

        if( ( $socket = fsockopen( gethostbyname( $server ), 43, $errno, $errstr, $timeout ) ) != false ) {
                // send the query string to the socket
                fputs( $socket, $query, strlen( $query ) );

                $result		= array();
                $appended	= false;
                while( !feof( $socket ) ) {
                    $contents = fgets( $socket, 4096 );
                    $contents = trim( $contents );
                    if( empty( $contents ) ) {
                        continue;
                    }

                    $first = $contents[0];

                    if( $first == '%' || $first == '<' || $first == '#' ) {
                        continue;
                    }

                    $comment = strstr( $contents, '//' );

                    if( $comment ) {
                        continue;
                    }

                    $seperatorIndex = strpos($contents, ':');

                    if( $seperatorIndex <= 0 ) {
                        continue;
                    }

                    $key	= trim( substr( $contents, 0, $seperatorIndex ) );
                    $value	= trim( substr( $contents, $seperatorIndex + 1 ) );
                    // Make sure we just have single spaces
                    $value	= preg_replace( '/\s+/', ' ', $value );

                    if( $key == 'inetnum') {
                        $appended	= false;
                        $result		= array();
                    }elseif( $key == 'source' ) {
                        if( !$appended ) {
                            $resultList[] = $result;
                        }
                        $appended = true;
                    }
                    if( array_key_exists( $key, $result ) ) {
                        $entry = $result[$key];
                        if( $entry ) {
                            $value = $entry . "\n" . $value;
                        }
                    }
                    $result[$key] = $value;
                }

                fclose( $socket );
        }

        //filter Results - We are only interested in first result using status ASSIGNED
        //Some results do not have "status", but this is could be our "ASSIGNED" result
        $returnList = array();

        foreach ( $resultList as $result ) {
            if( array_key_exists( 'status', $result ) ) {
                $status = $result['status'];
                //@at stripos() function is not supported by PHP 4.0 //@todo could we here use strpos(); function?
                // mic 20081013: re-added it, because stripos is a function in base.classes.php since 2.3.x
                $pos = stripos( $status, 'SSIGNED' );
                //$pos = strpos( strtolower( $status ), strtolower( 'SSIGNED' ) );

                if( $pos == false ) {
                    continue;
                }else{
                   return array( $result );
                }
            }
            $returnList[] = $result;
        }

        return $returnList;
    }


    /**
    *   Overworked by DVW
    */
    //function queryWhois( $server, $query, &$ipFrom = "0.0.0.0", &$ipTo  = "255.255.255.255", &$result ) {//problem in PHP 4.0 (probably with defalut argument value)
    function queryWhois( $server, $query, &$ipFrom, &$ipTo, &$result ) {
        $countryCode	= '';
        $resultList		= $this->executeWhois( $server, $query );

        if( !empty( $resultList ) ) {
            //$line	    = '';
            $prevline   = '';
            $getCountry = false;
            $getRange   = false;
            $result		= null;

            foreach ( $resultList as $whois) {
                // process the result of the Whois lookup
                if( empty( $whois ) ) {
                	continue;
                }

                if( array_key_exists( 'inetnum', $whois ) ) {
                    $inetnum = $whois['inetnum'];
                    // get IP range and see if it's narrower than the current range
                    // note: ip2long gives signed results, so we convert to unsigned using sprintf
                    
					$getCountry = false;

					$values = null;
					
                    if( substr_count( $inetnum, ' - ' ) > 0 ) {
                    	// Netblock notation
                        $values = explode( ' - ', $inetnum );
                    //}elseif( substr_count( $line, '/' ) > 0 ) {//line was always $line = ''!!
                    }elseif( substr_count( $inetnum, '/' ) > 0 ) {
                    	// CIDR block notation
                        /* - Begin CIDR notation parser, heavily based on code from Leo Jokinen <legetz81@yahoo.com> - */

                        $values	= explode( '/', $inetnum );

                        if (is_array($values))
						{
							if (count($values) == 2)
							{
								$values[0] = trim($values[0]);
								$values[1] = trim($values[1]);
								if (strlen($values[0])>0 && strlen($values[1])>0)
								{
									$bin = '';
									for ($i = 1; $i <= 32; $i++)
										$bin .= $values[1] >= $i ? '1' : '0';
									for ($i = substr_count($values[0], "."); $i < 3; $i++)
										$values[0] .= ".0";
		
									$nm = ip2long(bindec($bin));
									$v0 = ip2long($values[0]);
									if (is_int($nm) && is_int($v0))
									{
										$nw = ($v0 & $nm);
										$bc = $nw | (~$nm);
			
										$values[0] = long2ip($nw);
										$values[1] = long2ip($bc);
									}
								}
							}
						}                        
                        
                        /* - End CIDR notation parser ---------------------------------------------------------------- */
                    }
                    
					if (is_array($values))
					{
						if (count($values) == 2)
						{
							$values[0] = trim($values[0]);
							$values[1] = trim($values[1]);
							if (strlen($values[0])>0 && strlen($values[1])>0)
							{
								if (sprintf("%u", ip2long($values[0])) >= sprintf("%u", ip2long($ipFrom)) &&
								    sprintf("%u", ip2long($values[1])) <= sprintf("%u", ip2long($ipTo)))
								{
									$ipFrom = $values[0];
									$ipTo = $values[1];
			
									$getCountry = true;
								}
							}
						}
					}
						
                }

                if( array_key_exists( 'netname', $whois ) && $getCountry ) {
                    $netname = $whois['netname'];
                    // filter some of the generic networks

                    $ipA	= explode( '.', $this->IpAddress );
                    $ipNet	= 'NET' . $ipA[0];

                    if( substr( $netname, 0, 6 )	=== 'LACNIC'
                    || substr( $netname, 0, 7 )		=== 'AFRINIC'
                    || substr( $netname, 0, 9 )		=== 'RIPE-CIDR'
                    || substr( $netname, 0, 9 )		=== 'ARIN-CIDR'
                    || substr( $netname, 0, 10 )	=== 'IANA-BLOCK'
                    || substr( $netname, 0, 13 )	=== 'IANA-NETBLOCK'
                    || substr( $netname, 0, 12 )	=== 'ERX-NETBLOCK'
                    || substr( $netname, 0, strlen( $ipNet ) ) === $ipNet ) {
                        $getCountry = false;
                    }

                    if( $server === 'whois.ripe.net' ) {
                        if( substr( $netname, 0, 6 ) === 'LACNIC' ) {
                        	$countryCode = 'LACNIC';
                        }
                        if( ( substr( $netname, 0, 7 ) === 'AFRINIC') || ( $ipA[0] === '41' ) ) {
                        	$countryCode = 'AfriNIC';
                        }
                    }
                }
                if( array_key_exists( 'OrgName', $whois ) ) {
                    // LACNIC Joint Whois entry, get country and IP range now
                    $result		= $whois;
                    $getCountry	= true;
                    $getRange	= true;
                }
                if( array_key_exists( 'role', $whois )  && $result == null ) {
                    // LACNIC Joint Whois entry, get country and IP range now
                    $result = $whois;
                }
                if( array_key_exists( 'NetRange', $whois ) && $getRange ) {
                    $NetRange = $whois['NetRange'];
                    // get IP range from LACNIC Joint Whois entry

                    $values = explode( ' - ', $NetRange );

                    if( sprintf( "%u", ip2long( $values[0] ) ) >= sprintf( "%u", ip2long( $ipFrom ) )
                    && sprintf( "%u", ip2long( $values[1] ) ) <= sprintf( "%u", ip2long( $ipTo ) ) ) {
                        $ipFrom	= $values[0];
                        $ipTo	= $values[1];
                    }

                    $getRange = false;
                }
                if( array_key_exists( 'country', $whois ) && $getCountry ) {
                    $country = $whois['country'];
                    // the last ip range was narrower than the ones before and we found
                    // a country entry; now extract the country entry

                    $countryCode = $country;

                    if( $countryCode !== 'AP') {
                    	$getCountry = false;
                    }
                }
                if( array_key_exists( 'nserver', $whois ) && $getCountry ) {
                    $nserver = $whois['nserver'];
                    // if there is no country entry, try to get the TLD from the name
                    // server entry (e.g. registro.br does not include a country code)

                    $pos = strrpos( $nserver, '.' ) + 1;

                    if( $pos > 1) {
                        $xt = trim( substr( $nserver, $pos ) );

                        if( ereg( '([a-zA-Z])', $xt ) ) {
                            $countryCode = $xt;
                        }
                    }
                }
                //RB: question for mic: why did you remove the .br part?
                // mic 20081013: because they use now a capture code for accessing
                /*
                else if ( array_key_exists("nserver", $whois) strstr($line, "registro.br") !== false && $getCountry)
                {
                	registro.br does neither include a country code nor a name
                    server entry for some entries, so find these entries here

                  	$countryCode = "br";
                }
                */
            }
            $result = $resultList[0];
        }

        return $countryCode;
    }

	/**
	 *  Create or update visits table and return its id
     *
     * @return integer
     */
	function visits() {

		$this->_getDB();

		// check if visitor is known (in ipaddresses table) and if known make his/her ID available
		// $visitid is empty or contains the id of the ipaddress table
		$query = 'SELECT id'
		. ' FROM #__jstats_ipaddresses'
		. ' WHERE ip = \'' . $this->IpAddress . '\''
		. ' AND useragent = \'' . $this->UserAgent . '\''
		;
		$this->db->setQuery( $query );
		$visitid = $this->db->loadResult();

		$visitnumber = 0;
		if( $visitid ) {
			// only do a SQL lookup if we have a visitid
			// get the visit row (id) if there is a visit in progress
			$query = 'SELECT id'
			. ' FROM #__jstats_visits'
			. ' WHERE month = MONTH(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR))'
			. ' AND year = YEAR(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR))'
			. ' AND ip_id = \'' . $visitid . '\''
			. ' AND time >= DATE_ADD(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR),'
				. ' INTERVAL -' . $this->JSConf->onlinetime . ' MINUTE)'
			;
			$this->db->setQuery( $query );
			$visitnumber = $this->db->loadResult();
		}

		//js_echoJSDebugInfo('', $visitid);
		//js_echoJSDebugInfo('', $visitnumber);
		//js_echoJSDebugInfo('', $this->JSConf);

		if( $visitnumber ) {
			// it's not the 1st page request, so update visits row
			// (maybe if it's the same visit we shouldn't do the extra query to save the time? anyway now is time last visit)

			$query = 'UPDATE #__jstats_visits'
			. ' SET time = DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)';
			// do not clear the UserId if the user logs out.
			if( $this->UserId != 0 ) {
				$query .= ', userid=' . $this->UserId;
			}
			$query .= ' WHERE id=' . ((int)$visitnumber);

			$this->db->setQuery( $query );
			$this->db->query();
		}else{
			// this is 1st page request, lets create a visits entry
			$query = 'INSERT INTO #__jstats_visits (ip_id, hour, day, month, year, time, userid)'
			. ' VALUES ('
			. $visitid . ','
			. ' HOUR(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. ' DAYOFMONTH(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. ' MONTH(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. ' YEAR(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. ' DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR),'
			. '\'' . $this->UserId . '\')'
			;
			$this->db->setQuery( $query );
			$this->db->query();

			$visitnumber = $this->db->insertid();
		}

		return $visitnumber;
	}

	function PageRequest( $visitid ) {
		global $mainframe;

		$this->_getDB();

		// get page title
		$pagetitle = 'Page outside Joomla';
		//outside joomla we can not check page title
		if( !defined( '_JS_STAND_ALONE' ) ) {
			$pagetitle = $this->db->getEscaped( $mainframe->getPageTitle() ); // mic: changed
		}

		// trim page title if longer than 255 characters
		if( strlen( $pagetitle ) > 255 ) {
			$pagetitle = substr( $pagetitle, 0, 254 );
		}

		// if requested page is not empty
		if( $this->RequestedPage != '' ) {
			$page = $this->db->getEscaped( $this->RequestedPage ); // mic changed

			if( $this->JSConf->enable_i18n ) {
				// remove lang setting from page URL to treat multi language versions of one page as the same
				// @todo mic 20081013: check if position 8 is correct ???
				if( strpos( $page, '?lang=' ) !== false ) {
					$page = str_replace( substr( $page, strpos( $page, '?lang=' ), 8 ), '', $page );
				}

				if( strpos( $page, '&lang=' ) !== false ) {
					$page = str_replace( substr( $page, strpos( $page, '&lang=' ), 8 ), '', $page );
				}

				// mic: for sef urls
				if( strpos( $page, 'lang,' ) !== false ) {
					$page = str_replace( substr( $page, strpos( $page, 'lang,' ), 8 ), '', $page );
				}
			}

			$query = 'SELECT page_id, page_title'
			. ' FROM #__jstats_pages'
			. ' WHERE page = \'' . $page . '\''
			;
			$this->db->setQuery( $query );
			$rows = $this->db->loadObjectList();

			if ( !$rows ) {
				$query = 'INSERT INTO #__jstats_pages (page, page_title)'
				. ' VALUES (\'' . $page . '\', \'' . $pagetitle . '\')'
				;
				$this->db->setQuery( $query );
				$this->db->query();
				$pageid = $this->db->insertid(); // mic changed
			}else{
				$row 	= $rows[0];
				$pageid = $row->page_id;

				if( $row->page_title == '' ) {
					$query = 'UPDATE #__jstats_pages'
					. ' SET page_title = \'' . $pagetitle . '\''
					. ' WHERE page_id = \'' . $pageid . '\''
					;
					$this->db->setQuery( $query );
					$this->db->query();
				}
			}

			$query = 'INSERT INTO #__jstats_page_request (page_id, hour, day, month, year, ip_id)'
			. ' VALUES (' . $pageid . ','
			. ' HOUR(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. ' DAYOFMONTH(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. ' MONTH(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. ' YEAR(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
			. $visitid
			. ')'
			;
			$this->db->setQuery( $query );
			$this->db->query();
    		//if( !$this->db->query() )
    			//echo $this->db->getErrorMsg().', '.$query;//@at in j1.5.x $query is relevant. SQL is inside ErrorMsg
    			// mic: NO ERROR MESSAGES HERE, WE ARE AT THE FRONTEND!!
    			// OR WE CREATE A LOGFILE ..... which i suggest
		}

		$this->Regreferrer();
	}

	function Regreferrer() {

		$this->_getDB();

		// if referer is nothing then return
		if( !isset( $_SERVER['HTTP_REFERER'] ) ) {
			return;
		}

		$ref = $_SERVER['HTTP_REFERER'];

		if( trim( $ref ) != '' ) {
			if( isset( $_SERVER['HTTP_HOST'] ) ) {
				$hst = $_SERVER['HTTP_HOST'];
			}

			$ref = trim( $this->db->getEscaped( $ref ) );
			$hst = trim( $this->db->getEscaped( $hst ) );

			if( strpos( $ref, $hst, 0 ) === false && substr( $ref, 0, 7 ) == 'http://' ) {
				if( strpos( $ref, '/', 7 ) !== false ) {
					$pos = strpos( $ref, '/', 7 ) - 7;
					$dom = substr( $ref, 7, $pos );
				}else{
					$dom = substr( $ref, 7 );
				}

				// changed mic 20081013 and added strtolower
				if( ( strtolower( substr( $dom, 0, 4 ) ) == 'www.' ) ) {
					$dom = substr( $dom, 4 );
				}

				if( $this->regKeyWords( $ref ) === false) {
					$query = 'INSERT INTO #__jstats_referrer (referrer, domain, day, month, year)'
					. ' VALUES (\'' . $ref . '\','
					. ' \'' . $dom . '\','
					. ' DAYOFMONTH(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
					. ' MONTH(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)),'
					. ' YEAR(DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR)))'
					;
					$this->db->setQuery( $query );
					$this->db->query();
				}
			}
		}
	}

	// TEST CODE
	//$keys = 'p=|we=|q=|wy=';
	//$str_start = getmicrotime();
	//getKeyWordsStr("http://google.com/search", $keys);
	//getKeyWordsStr("http://google.com/search?sourceid=navclient", $keys);
	//getKeyWordsStr("http://google.com/search?sourceid=navclient&none", $keys);
	//getKeyWordsStr("http://google.com/search?q=firstkeyword+secondkeyword+thirdkeyword", $keys);
	//getKeyWordsStr("http://google.com/search?sourceid=navclient&q=firstkeyword+secondkeyword+thirdkeyword", $keys);
	//getKeyWordsStr("http://google.com/search?sourceid=navclient&aq=0h&oq=firstkeyword+&hl=en&ie=UTF-8&q=firstkeyword+secondkeyword+thirdkeyword", $keys);
	//getKeyWordsStr("http://google.com/search?sourceid=navclient&aq=0h&oq=firstkeyword+&hl=en&ie=UTF-8&q=firstkeyword+secondkeyword+thirdkeyword&additional", $keys);
	//$str_end = getmicrotime();
	//echo sprintf('%.5f', $str_end - $str_start) . ' seconds' . '<br/>';
	//echo sprintf('%0.3f', memory_get_usage() / 1024 ).' kB' . '<br/>';
	function getKeyWordsStr($url, $keys)
	{
		//below code is working correctly, but it is slower than str_replace()
		//$url_arr = parse_url($url);
		//if (!isset($url_arr['query']))
		//	return ''; //there is not query or query is empty
		//$query = '&'.$url_arr['query'];
		$query = str_replace('?', '&', $url); //str_replace is faster than parse_url() function
	
		$ar = explode("|", $keys);
		for ($i = 0; $i < count($ar); $i++) {
			$key = $ar[$i];
	
			$pos = strpos( $query, '&'.$key );
			if( $pos !== false ) {
				$pos_begin = $pos+strlen($key)+1;
				$pos_end = strpos( $query, '&', $pos_begin );
	
				if( $pos_end !== false )
					return substr( $query, $pos_begin, $pos_end-$pos_begin );
				else
					return substr( $query, $pos_begin );
			}
		}
	
		// 1) below method is working correctly but it is above two times slower than operating on strings
		// 2) preg_match CRASHES! on some machines!! For details see [#18344] *** glibc detected *** double free or corruption (fasttop) with AOL serach results links
		//if( preg_match( '/[\?&]('.$keys.')(.+?)(&|$)/i', $url, $matches ) ) {
		//	for ($i=2; count($matches); $i++) { //we must start from 2 (not 0)
		//		if( $matches[$i] != null ) {
		//			return $matches[$i];
		//		}
		//	}
		//}
	
		return '';//no keywords in query
	}
	
	/**
	 * adds serach items from search engines into database
	 *
	 * @param string $url
	 * @return integer/false
	 */
	function regKeyWords( $url ) {

		$this->_getDB();

		$kwrds = '';

		$query = 'SELECT *'
		. ' FROM #__jstats_search_engines'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadAssocList();

		// added mic: rare case if the table serach_engines is empty!
		if( count( $rows ) > 0 ) {
			foreach( $rows as $row ) {
				if( strpos( $url, addslashes( $row['search'] ), 0 ) !== false ) {
					
					$kwrds = $this->getKeyWordsStr( $url, $row['searchvar'] );
					$kwrds = $this->db->Quote( $kwrds );//@todo Quote() should be removed - it should be synchronized with removing two additional chars from each row from DB (on begining and on end of line)
					$kwrds = urldecode( $kwrds );
					//$kwrds = trim( $kwrds ); NO do not make trim!! different results are for 'ove' and ' ove'!!! Could be 'love' / 'oven'
					
					// DVW: It's nice to know which search engine was used!
					break;
				}
			}
		}

		if ( strlen($kwrds) > 0 ) {
			$query = 'INSERT INTO #__jstats_keywords (kwdate, searchid, keywords)'
			. ' VALUES (DATE_ADD(NOW(), INTERVAL ' . $this->hourdiff . ' HOUR),'
			. $row['searchid'] . ','
			. ' \'' . $this->db->getEscaped( $kwrds ) . '\')'
			;
			$this->db->setQuery( $query );
			$this->db->query();

			// DVW: We return the insert id instead of "true", this makes it possible to associate the date to an user
			return $this->db->insertid();
		}else{
			return false;
		}
	}

	/**
	 * define or get users screen resolution
	 * the javascript is written only once: at the first visit, afterwards we check the cookie
	 * the cookie lives 4 days
	 *
	 * at first it will be checked if screen should be checked (see config)
	 * because at the first time the redirect (see location) will cause a short delay in the website creation
	 *
	 * @since 2.3.x mic
	 *
	 * NOTICE:
	 *     getJSScreenresolution() redirect user
	 *         This is serious problem. If user as firt page visit sub page he will be redirected to main page.
	 *     Redesing column screen in table jos_jstats_ipaddresses
	 *         now it is varchar 12. It should be 2 integers - much faster to compare and smaller database
	 *
	 * @param x
	 * @param y
	 * @return true on success
	 */
	function getJSScreenresolution(&$x, &$y) {

		if( isset( $_COOKIE['JS_screen_resolution'] ) ) {
			$res = $_COOKIE['JS_screen_resolution'];
		}else{ ?>
			<script type="text/javascript">
			    /* <![CDATA[ */
			    function writeCookie() {
					var text = 'JS_screen_resolution=' + screen.width + 'x' + screen.height;
					var date = new Date();
					date.setTime( date.getTime() + ( 4*24*60*60*1000 ) );
					var expires = "; expires=" + date.toGMTString();

					document.cookie = text + expires;
				};

				writeCookie();
				location = 'index.php';
				/* ]]> */
			</script>
			<?php
		}

		if( isset( $res ) ) {
			$x = 0;
			$y = 0;
			return false;
		}

		return false;
	}
}
