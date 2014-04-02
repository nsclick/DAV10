<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



 
/**
 * This file provide access to JoomlaStats database in:
 *     - 'joomla v1.0.15 Native' environment
 *     - 'joomla v1.5.7 Native' environment
 *     - without joomla
 *
 *  To get access to database JoomlaStats use a little modified classes from Joomla CMS
 */



if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}






class js_JSDatabaseAccess
{
	var $db;
	
	function __construct() {
		$this->_getDB();
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
	function js_JSDatabaseAccess()
	{
		$args = func_get_args();
		call_user_func_array(array(&$this, '__construct'), $args);
	}
	
	
	function _getDB() {

		if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
			//we are in non-joomla envinroment
			if ( !defined( '_JS_STAND_ALONE' ) ) {
				//someone try to hack page? or only author forgot apply defined( '_JS_STAND_ALONE' )
			} else {
				if (!defined('DS'))
					define('DS', DIRECTORY_SEPARATOR);
	
				//order is important!!!
				//get resources needed by JoomlaStats to access to database
					require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'stand.alone.configuration.php' );
					require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'res_joomla' .DIRECTORY_SEPARATOR. 'object.php' );
					require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'res_joomla' .DIRECTORY_SEPARATOR. 'database.php' );
					
				
				//create resources needed by JoomlaStats to work correctly
					//global $database;
					$JSStandAloneConfiguration = new js_JSStandAloneConfiguration();
					$this->db = JDatabase::getInstance($JSStandAloneConfiguration->JConfigArr);
	
					//show error if occure 
					//it is	VERY important - without this is very hard to determine what is not working (we are ouside joomla!)
					if ( is_object($this->db) == false ) {
						echo $this->db;
						echo '<br/><br/><br/><br/>';
					}
			}			
		} else {
			//we are in joomla, so use it
			if( defined('_JEXEC') ) {
				//joomla 1.5
				$this->db =& JFactory::getDBO();
			} else {
				//joomla 1.0.x
				global $database;
				$this->db = $database;
			}
		}
	}
	
	/** This function should not be here, but now there is no better place for it 
	 * @todo - make this function works for 'stand alone' version */
	function isMySql40orGreater() {
		$verParts = explode( '.', $this->db->getVersion() );
		//return ($verParts[0] == 5 || ($verParts[0] == 4 && $verParts[1] == 1 && (int)$verParts[2] >= 2));// oryginal code from joomla - works in j1.0.15 and j1.5.8
		return (bool) ($verParts[0] >= 4);
		//return false;//to tests
	}
	
	
	/** 
	 *  This function convert dates to SQL WHERE condition
	 *     Both dates are inclusive
	 *  
	 *  Now this function works on colum of type 'DATETIME' and name 'time'
	 *
	 *  date formats: 
	 *      ''             - use '' to omit date and time limitation
	 *      '2009-03-25'
	 *      '2009-3-9'
	 *      '2009-03-25 16:42:56' (NOT RECOMENDED)
	 */
	function getConditionStringFromDates($table_prefix, $date_from, $date_to) {
		if ( ($date_from === '') && ($date_to === '') )
			return '1=1';
			
		if ($date_from == $date_to) {
			if( strpos( $date_from, ' ', 0 ) !== false)
				return $table_prefix.'.time=\''.$date_from.'\'';
			else
				return 'CAST('.$table_prefix.'.time AS DATE)=\''.$date_from.'\'';
		}
			
		$res_from = '';
		$res_to = '';
		if ($date_from !== '') {
			if( strpos( $date_from, ' ', 0 ) !== false)
				$res_from .= $table_prefix.'.time>=\''.$date_from.'\'';
			else
				$res_from .= 'CAST('.$table_prefix.'.time AS DATE)>=\''.$date_from.'\'';
		}
		
		if ($date_to !== '') {
			if( strpos( $date_from, ' ', 0 ) !== false)
				$res_to .= $table_prefix.'.time<=\''.$date_to.'\'';
			else
				$res_to .= 'CAST('.$table_prefix.'.time AS DATE)<=\''.$date_to.'\'';
		}
			
		if ( ($res_from !== '') && ($res_to !== '') )
			return '('.$res_from.' AND '.$res_to.')';
		
		return $res_from.$res_to;//one of it always will be empty
	}
}
