<?php
/**
 * @version $Id: compat.joomla_1.0.php,v 1.2 2008/10/27 10:27:27 mic $
 * @version 1.0.2
 * @package Various
 * @author michael (mic) pagler
 * @copyright (C) 2008 mic [ http://www.joomx.com ]
 * @desc Purpose to emulate various Joomla! 1.5.x setting (see: j1.5.x: defines.php)
 * @license Other [No commercial use of this script in own applications without the explicit permission of the author]
 * - You may:
 * Use the work
 * Copy and re-distribute the work as is
 * Modify the work for your own use
 * Modify the work for your clients use
 * Remove any visible powered by and copyright statements from the frontend (although we'd rather see them present)
 * - You may not:
 * fork the work into a new project and use the existing code
 * remove the copyright statements from the source code
 * rebrand and release our work as your own
 * take credit for our work
 */

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'No Direct Access (J.1.5)' );
}

if( !defined( 'DS' ) ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

if( !defined( 'JPATH_ROOT' ) ) {
	// get root path
	if( !isset( $tmpPath ) ) {
		$tmpPath = preg_replace( '/(\/admin*.*|index*.*)\s*(.*?)/i', '', $_SERVER['SCRIPT_FILENAME'] );
		$tmpPath = str_replace( '/', DS, $tmpPath );
		$tmpPath = trim( $tmpPath, DS );
	}

	define( 'JPATH_ROOT', 			$tmpPath );
	define( 'JPATH_SITE',			JPATH_ROOT );
	define( 'JPATH_CONFIGURATION', 	JPATH_ROOT );
	define( 'JPATH_ADMINISTRATOR', 	JPATH_ROOT.DS.'administrator' );
	/*
	define( 'JPATH_XMLRPC', 		JPATH_ROOT.DS.'xmlrpc' );
	define( 'JPATH_LIBRARIES',	 	JPATH_ROOT.DS.'libraries' );
	define( 'JPATH_PLUGINS',		JPATH_ROOT.DS.'plugins'   );
	define( 'JPATH_INSTALLATION',	JPATH_ROOT.DS.'installation' );
	// JPATH_BASE depends if administrator or site!
	define( 'JPATH_THEMES',			JPATH_BASE.DS.'templates' );
	define( 'JPATH_CACHE',			JPATH_BASE.DS.'cache' );
	*/

	unset( $tmpPath );
}

if( !class_exists( 'JURI' ) ) {
	/**
	 * simple emulation of J.1.5.x JURI class
	 *
	 */
	class JURI
	{
		/**
		 * returns the live_site url
		 *
		 * @param bool $pathonly	only here to avoid troubles if set in script, has no further function
		 * @return string live_site url
		 */
		function root( $pathonly = false ) {
			// JURI::root()     for j1.5.x  [http://localhost/j15/1.5.7_tmp/] (are You shure about / at the end?)
			// juri::root()     for j1.5.x  [http://localhost/j15/1.5.7_tmp/administrator/] (are You shure about / at the end?)
			// juri::root(true) for j1.5.x  [/j15/1.5.7_tmp/administrator]
			// juri::root(any)  for j1.0.15 [http://127.0.0.1/j1015_2009-02-24/administrator] in j1.0.15 always full path!!!

			if( defined( '_JEXEC' ) ){
				//joomla 1.5

				global $mainframe;
	
				if( $pathonly === false ) {
					$retVal = $mainframe->getCfg( 'live_site' ) . '/';
				}else{
					$retVal = trim( dirname( $_SERVER['PHP_SELF'] ), '/\\' );
				}
	
				if( defined( 'JPATH_BASE' ) ) {
					if( JPATH_BASE == JPATH_ADMINISTRATOR ) {
						$retVal .= ( $pathonly === false ? '' : '/' ) . 'administrator' . ( $pathonly === false ? '/' : '' );
					}
				}
	
				return $retVal;

			}else{
				//joomla 1.0.x

				global $mosConfig_live_site;

				return $mosConfig_live_site.'/administrator';
			}
		}

		/**
		 * returns the live_site url w - w/o administrator
		 *
		 * @param bool $pathonly
		 * @return string live_site url
		 */
		function base( $pathonly = false ) {
			// juri::base()     for j1.5.x [http://localhost/j15/1.5.7_tmp/administrator/]
			// juri::base(true) for j1.5.x [/j15/1.5.7_tmp/administrator]

			return JURI::root( $pathonly );
		}
	}
}

if( !class_exists( 'JRequest' ) ) {
	/**
	 * simple emulation of the original J.1.5.x JREQUEST class
	 *
	 */
	class JRequest
	{
		function getVar( $name, $default = null, $hash = 'default', $type = 'none', $mask = 0 ){

			if( defined( '_JEXEC' ) ){
				//joomla 1.5
				$var = JRequest::getVar( $name, $default, $hash, $type, $mask );
			}else{
				//joomla 1.0.x
				// Ensure hash and type are uppercase
				$hash = strtoupper( $hash );
				if ($hash === 'METHOD') {
					$hash = strtoupper( $_SERVER['REQUEST_METHOD'] );
				}
				$type	= strtoupper( $type );

				switch( $hash ) {
					case 'GET':
						$hash = $_GET;
						break;
					case 'POST':
						$hash = $_POST;
						break;
					case 'DEFAULT':
					default:
						$hash = $_REQUEST;
						break;
				}

				$var = mosGetParam( $hash, $name, $default, $mask );
			}

			return $var;
		}
	}
}