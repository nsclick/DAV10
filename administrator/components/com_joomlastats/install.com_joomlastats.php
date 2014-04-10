<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'base.classes.php' );
require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'status.php' );
require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'util.classes.php' );

if( !defined( '_JEXEC' ) ) {
	// check and get language
	$path_to_admin = $GLOBALS['mosConfig_absolute_path'] .DIRECTORY_SEPARATOR. 'administrator' .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_joomlastats' .DIRECTORY_SEPARATOR. 'language';
	jxTools::_checkLanguage( false, $path_to_admin );
}

class JSInstall extends JSBasic
{
	var $quer 					= array();
	var $errors					= array();
	var $dataSum					= 0;
	var $installPath;

	var $errorMsg				= array();
	var $warningMsg				= array();
	var $infoMsg					= array();

	function __construct() {
		parent::__construct();
	}

	/**
	 * basic function for installing JoomlaStats
	 *
	 */
	function com_install() {
		global $mainframe;

		$this->_getDB();
		$JSUtil = new js_JSUtil();

		$installationErrorMsg = '';

		$isThisUpgrade		= false;
		$oldJSVersionNumber = '';

		{// detect if this is upgrade or install and get old version number
			$query = 'SHOW TABLE STATUS FROM `' . $mainframe->getCfg( 'db' ) . '`'
			. ' LIKE \'' . $mainframe->getCfg( 'dbprefix' ) .'jstats_configuration\''
			;
			$this->db->setQuery( $query );
			$rows = $this->db->LoadObjectList();
			if ($this->db->getErrorNum() > 0)
				$installationErrorMsg .= JTEXT::_( 'Some errors occured during the JoomlaStats installation process' ) . 'Error number: #1';

			if ($rows) {
				if (count($rows) == 1) {
					$query = 'SELECT * FROM'
					. ' #__jstats_configuration'
					;
					$this->db->setQuery( $query );
					$rows = $this->db->loadAssocList();
					if ($this->db->getErrorNum() > 0) {
						$installationErrorMsg .= JTEXT::_( 'Some errors occured during the JoomlaStats installation process' ) . 'Error number: #4';
					} else {
						if ( (!$rows) || (count($rows) == 0) ) {
							$installationErrorMsg .= JTEXT::_( 'Some errors occured during the JoomlaStats installation process' ) . 'Error number: #5';
				        } else {
							$isThisUpgrade = true;
				
							foreach( $rows as $row ) {
								if( $row['description'] == 'version' )
									$oldJSVersionNumber = $row['value'];
							}
						}
					}
				} else {
					$installationErrorMsg .= JTEXT::_( 'Some errors occured during the JoomlaStats installation process' ) . 'Error number: #2';
				}
			}
		}


		$JSConfDef = new js_JSConfDef();

		
		if( $isThisUpgrade == true) {
			//update existing database

			// first check if we are able to upgrade from this particular old version
			// Yes, we should do this. We should not allow to update from too old versions - too many queries and datbase operations could exide PHP time limit (30s) and we get broken database!!!
			if ($JSUtil->JSVersionCompare( $oldJSVersionNumber, '2.2.0', '<') == true) {
				echo "<br/><br/>";
				echo "You try to update JoomlaStats from version '$oldJSVersionNumber' to version '$JSConfDef->JSVersion'!!!<br/><br/>";
				echo "It is imposible!!<br/><br/>";
				echo "Please first update JoomlaStats to version '2.2.3'!!<br/><br/>";
				echo "<br/><br/>";
				return false;
			} else {
				include_once( dirname( __FILE__ ) .DS. 'install' .DS. 'update.db.joomlastats.inc.php' );
				js_UpdateJSDatabaseOnInstall( $JSUtil, $oldJSVersionNumber, $JSConfDef );
			}
		} else {
			//install new database
			
			// mic 20081009: new - queries are now splitted into 1. tables and 2. data (no language depending vars into database)
			// this is new install so We create tables
			include_once( dirname( __FILE__ ) .DS. 'install' .DS. 'all.tables.joomlastats.inc.php' );
	
			// populate queries
			$JSUtil->populateSQL( $quer, true );
			$quer = null;
		}
		
		{//here we refresh data. We delete existing data and load new one (that contains more rows) - this way exist from version 2.2.0
			//this is not the best way but... eg. data not exist in 2.2.0 nor 2.3.0.84, next we make fix (add new data) in in 2.2.1, and in the same moment in 2.3.0.104 - every thing will work all right

			include_once( dirname( __FILE__ ) .DS. 'install' .DS. 'all.data.joomlastats.inc.php' );
	
			// populate queries
			$JSUtil->populateSQL( $quer, true );
			$quer = null;
		}


		{//here update/modify things outside JS
		
		    // Modify the admin icon because j1.0.x cannot do that through the xml
			$query = 'UPDATE #__components'
			. ' SET admin_menu_img = \'../administrator/components/com_joomlastats/images/icon-16-js_js-logo.png\''
			. ' WHERE link = \'option=com_joomlastats\''
			;
			$this->db->setQuery( $query );
			$this->db->query();
		}
		
		//it is good to make optimalization (database structure can change a little), but it is not neccessary
		$JSUtil->optimizeAllJSTables();

		
		if ($installationErrorMsg != '')
			$this->errorMsg[] = array( 'name' => JTEXT::_( 'Installation' ), 'description' => $installationErrorMsg );

		
	    // collect warning/recommendation/info messages
		if( count( $this->errors ) == 0 ) {
			//everything is OK
			if( $isThisUpgrade == true ) {
				$this->infoMsg[] = array( 'name' => JTEXT::_( 'Upgrade' ), 'description' => JTEXT::sprintf( 'JoomlaStats has been successfully upgraded from version [%s] to version [%s]<br />Previously collected statistics have been retained!', $oldJSVersionNumber, $JSConfDef->JSVersion ) );
			} else {
				$this->infoMsg[] = array( 'name' => JTEXT::_( 'Installation' ), 'description' => JTEXT::_( 'New installation of JoomlaStats' ) );
				$this->infoMsg[] = array( 'name' => JTEXT::_( 'Installation' ), 'description' => JTEXT::_( 'JoomlaStats has been succesfully installed!' ) );
			}
		}else{
			//something is wrong
			if ( $isThisUpgrade == true ) {
				$installationErrorMsg .= JTEXT::SPRINTF( 'Some errors occured during JoomlaStats upgrade process when trying to upgrade from version [%s] to version [%s]', $oldJSVersionNumber, $JSConfDef->JSVersion );
			}else{
				$installationErrorMsg .= JTEXT::_( 'Some errors occured during the JoomlaStats installation process' ) . 'Error number: #3';
			}

			if( $this->errors ) {
				$installationErrorMsg .= '<br/><ul>';
				foreach ( $this->errors as $err ) {
					$installationErrorMsg .= '<li>' . print_r( $err, true ) . '</li>';
				}
				$installationErrorMsg .= '</ul><br/>';
			}

			$installationErrorMsg .= '<br/>' . JTEXT::_( 'Please refer to the JoomlaStats project website' )
			. ' <a href="http://www.joomlastats.org" target="_blank">http://www.joomlastats.org</a>.';

			$this->errorMsg[] = array( 'name' => JTEXT::_( 'Installation' ), 'description' => $installationErrorMsg );
		}

		$JSStatus = new js_JSStatus();
		$JSStatus->viewJSStatusForInstallProcess( $this->errorMsg, $this->warningMsg, $this->infoMsg );
	}
}

$jsInstall = new JSInstall();
$jsInstall->com_install();

// mic: dummy function do NOT delete it!
function com_install() { }