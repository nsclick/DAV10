<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}





/**
 *  This class contain JS API methods that body is to big to be in js_JSApiGeneral class
 *
 *  All methods are static
 */
class js_JSApiBase
{
	/**
	 * Get list of Operating Systems that are recognized by JoomlaStats
	 * see also getAvailableSystemListForHuman
	 *
	 * @param out integer $AvailableSystemList_result
	 * @return true on success
	 */
	function getAvailableOperatingSystemList( &$AvailableOperatingSystemList_result ) {
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR.'select.many.rows.php' );
		$JSDbSMR = new js_JSDbSMR();
		$systems_arr = array();
		if ($JSDbSMR->getAvailableOperatingSystemArrForHuman( $systems_arr ) == false)
			return false;
			
		$AvailableOperatingSystemList_result = $this->addUrlsToOperatingSystemList($systems_arr, '');
		return true;
	}
		
	/**
	 * Get list of Operating Systems that are recognized by JoomlaStats
	 * see also getAvailableSystemList
	 *
	 * @param out integer $AvailableSystemList_result
	 * @return true on success
	 */
	function getAvailableOperatingSystemListForHuman( &$AvailableOperatingSystemListForHuman_result ) {
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR.'select.many.rows.php' );
		$JSDbSMR = new js_JSDbSMR();
		$systems_arr = array();
		if ($JSDbSMR->getAvailableOperatingSystemArrForHuman( $systems_arr ) == false)
			return false;
			
		$AvailableOperatingSystemListForHuman_result = $this->addUrlsToOperatingSystemList($systems_arr, '');
		return true;
	}
	
	
	/** @private 
	 *  $OSDirectoryName eg. 'os-png-16x16-1'; ''; // if '' default directory is used
	 *  $directory_name  
	 */
	function addUrlsToOperatingSystemList( $OperatingSystemListArr, $OSDirectoryName ) {
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'util.classes.php' );
		if ($OSDirectoryName == '') {
        	$JSSystemConst = new js_JSSystemConst();
        	$OSDirectoryName = $JSSystemConst->defaultPathToImagesOs;
		}
		$JSUtil = new js_JSUtil();
		
		$SystemList_result = array();
		foreach( $OperatingSystemListArr as $row) {
			$this->addUrlsToOSObject($JSUtil, $OSDirectoryName, $row);
			$SystemList_result[] = $row;
			/*
			//$obj = new stdClass();
			$obj = $row;
			$obj->os_img_url      = $JSUtil->getImageWithUrl($obj->os_img, $OSDirectoryName);
			$obj->os_img_html     = '<img src="'.$obj->os_img_url.'" alt="'.$obj->os_name.'" />';
			if (isset($obj->ostype_img))
				$obj->ostype_img_url  = $JSUtil->getImageWithUrl($obj->ostype_img, $OSDirectoryName);
			if (isset($obj->ostype_name))
				$obj->ostype_img_html = '<img src="'.$obj->ostype_img_url.'" alt="'.$obj->ostype_name.'" />';
			
			$SystemList_result[] = $obj;
			*/
		}
		
		return $SystemList_result;
	}
	
	/** @private 
	 *  $OSDirectoryName eg. 'os-png-16x16-1'; // empty string ('') is not allowed!
	 *  $OS_inout object of class js_OS  
	 */
	function addUrlsToOSObject( $JSUtil, $OSDirectoryName, &$OS_inout ) {
		$OS = $OS_inout;
		$OS->os_img_url      = $JSUtil->getImageWithUrl($OS->os_img, $OSDirectoryName);
		$OS->os_img_html     = '<img src="'.$OS->os_img_url.'" alt="'.$OS->os_name.'" />';
		if (isset($OS->ostype_img))
			$OS->ostype_img_url  = $JSUtil->getImageWithUrl($OS->ostype_img, $OSDirectoryName);
		if (isset($OS->ostype_name))
			$OS->ostype_img_html = '<img src="'.$OS->ostype_img_url.'" alt="'.$OS->ostype_name.'" />';

		$OS_inout = $OS;
		
		return true;
	}

	/** @private 
	 *  $BrowserDirectoryName eg. 'browser-png-16x16-1'; // empty string ('') is not allowed!
	 *  $Browser_inout object of class js_Browser  
	 */
	function addUrlsToBrowserObject( $JSUtil, $BrowserDirectoryName, &$Browser_inout ) {
		$Browser = $Browser_inout;
		$Browser->browser_img_url      = $JSUtil->getImageWithUrl($Browser->browser_img, $BrowserDirectoryName);
		$Browser->browser_img_html     = '<img src="'.$Browser->browser_img_url.'" alt="'.$Browser->browser_name.'" />';
		if (isset($Browser->browsertype_img))
			$Browser->browsertype_img_url  = $JSUtil->getImageWithUrl($Browser->browsertype_img, $BrowserDirectoryName);
		if (isset($Browser->browsertype_name))
			$Browser->browsertype_img_html = '<img src="'.$Browser->browsertype_img_url.'" alt="'.$Browser->browsertype_name.'" />';

		$Browser_inout = $Browser;
		
		return true;
	}

	/** @private 
	 *  $TldDirectoryName eg. 'tld-png-16x16-1'; // empty string ('') is not allowed!
	 *  $Tld_inout object of class js_Tld  
	 *  
	 * @todo for unknown this function will not work. Probably we should to fix all code (without this function)
	 */
	function addUrlsToTldObject( $JSUtil, $TldDirectoryName, &$Tld_inout ) {
		$Tld = $Tld_inout;
		$Tld->tld_img_url      = $JSUtil->getImageWithUrl($Tld->tld_img, $TldDirectoryName);
		$Tld->tld_img_html     = '<img src="'.$Tld->tld_img_url.'" alt="'.$Tld->tld_name.'" />';

		$Tld_inout = $Tld;
		
		return true;
	}

	function getOperatingSystemVisistsArr( $date_from, $date_to, $include_summarized, $OSDirectoryName, &$arr_obj_result ) {
		if (($include_summarized !== true) && ($include_summarized !== false)) {
			require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'base.classes.php' );
			$JSConf = new js_JSConf();
			$include_summarized = $JSConf->include_summarized;
		}
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR.'select.many.rows.php' );
		$JSDbSMR = new js_JSDbSMR();
		
		$OperatingSystemVisistsArr = array();
		$res = $JSDbSMR->getOperatingSystemVisistsArr( $date_from, $date_to, $include_summarized, $OperatingSystemVisistsArr );
		if ($res == false)
			return false;
		
		$arr_obj_result = $this->addUrlsToOperatingSystemList($OperatingSystemVisistsArr, $OSDirectoryName);
		
		return true;
	}
	
	/**
	 *  This function return details about user (visitor) that visit page
	 *
	 *  @param in  $OSDirectoryName;      eg.: 'os-png-16x16-1'; '';      //if '' default directory is used
	 *  @param in  $BrowserDirectoryName; eg.: 'browser-png-16x16-1'; ''; //if '' default directory is used
	 *  @param in  $TldDirectoryName;     eg.: 'tld-png-16x11-1'; '';     //if '' default directory is used
	 *
	 *  @return true on success //@todo
	 */
	function getVisitorDetails( $OSDirectoryName, $BrowserDirectoryName, $TldDirectoryName, &$Visitor_result ) {
		//include not required!!! not once!!
		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. '..' .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_joomlastats' .DIRECTORY_SEPARATOR. 'count.classes.php' );

		require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'util.classes.php' );
		$Visitor = null;
		$JSCountVisitor = new js_JSCountVisitor();
		
		$UserAgent = '';
		$JSCountVisitor->getVisitorUserAgent( $UserAgent );
		$VisitorIp = null;
		$JSCountVisitor->getVisitorIp( $VisitorIp );
		
		$updateTldInJSDatabase = true;
		$JSCountVisitor->recognizeVisitor( $VisitorIp, $UserAgent, $updateTldInJSDatabase, $Visitor );
		
		//additional members
		$RequestedPage = '';
		$JSCountVisitor->getVisitorRequestedPage( $RequestedPage );
		$Visitor->RequestedPage = $RequestedPage;
		$Visitor->cms_userid = $JSCountVisitor->GetJoomlaCmsUserId();
		
		
		if ($OSDirectoryName == '') {
        	$JSSystemConst = new js_JSSystemConst();
        	$OSDirectoryName = $JSSystemConst->defaultPathToImagesOs;
		}
		if ($BrowserDirectoryName == '') {
        	$JSSystemConst = new js_JSSystemConst();
        	$BrowserDirectoryName = $JSSystemConst->defaultPathToImagesBrowser;
		}
		if ($TldDirectoryName == '') {
        	$JSSystemConst = new js_JSSystemConst();
        	$TldDirectoryName = $JSSystemConst->defaultPathToImagesTld;
		}

		$JSUtil = new js_JSUtil();
		
		$this->addUrlsToOSObject( $JSUtil, $OSDirectoryName, $Visitor->OS );
		if ($Visitor->Browser != null)
			$this->addUrlsToBrowserObject( $JSUtil, $BrowserDirectoryName, $Visitor->Browser );
		$this->addUrlsToTldObject( $JSUtil, $TldDirectoryName, $Visitor->Tld );

		$Visitor_result = $Visitor;

		return true;
	}
}