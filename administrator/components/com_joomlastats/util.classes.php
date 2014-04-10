<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'base.classes.php' );// mic: not again !!! //Yes, again!!. It not working without it!



/**
 * This class contain utility methods that are used by many JS parts of code.
 * Utility methods are more complex than base methods.
 *
 * Maybe this class should be divided to 2 classes. 1-class with access to database, 2-that operate on texts, colors etc.
 */
class js_JSUtil extends JSBasic
{

	/**
	 * Formats a given integer - here used to format the dabase size
	 * mic: reworked since 2.3.x
	 *
	 * @return string
	 */
	function getJSDatabaseSizeHtmlCode() {
		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );
		$JSDbSOV = new js_JSDbSOV();//we create object to not rise PHP notice
		$JSDatabaseSize = 0;
		$JSDbSOV->getJSDatabaseSize($JSDatabaseSize);
		
		$color = 'green';
		if( ( $JSDatabaseSize > '10485760' ) && ( $JSDatabaseSize <= '31457280' ) ) {
			$color = 'blue';
		}
		if( $JSDatabaseSize > '31457280' ) {
			$color = 'red';
		}

		return '<span style="color:' . $color . '">' . round( ( ( $JSDatabaseSize / 1024 ) / 1024 ), 2 ) . '</span>';
	}

	/**
	 * Return array of translated month names (full names, long names)
	 * 0 - nothing
	 * 1 - 'January'
	 * 2 - 'February'
	 *
	 * @return array
	 */
	function getMonthsNamesLong() {
		$MonthsNamesLong  = array(
			'',
			JTEXT::_( 'January' ),
			JTEXT::_( 'February' ),
			JTEXT::_( 'March' ),
			JTEXT::_( 'April' ),
			JTEXT::_( 'May' ),
			JTEXT::_( 'June' ),
			JTEXT::_( 'July' ),
			JTEXT::_( 'August' ),
			JTEXT::_( 'September' ),
			JTEXT::_( 'October' ),
			JTEXT::_( 'November' ),
			JTEXT::_( 'December' )
		);
		
		return $MonthsNamesLong;
	}

	/**
	 * Return array of translated and tructucated month names (short names)
	 * 0 - nothing
	 * 1 - 'Jan'
	 * 2 - 'Feb'
	 *
	 * @return array
	 */
	function getMonthsNamesTruntucatd($nbr_of_chars = 3) {
		$MonthsNamesLong = $this->getMonthsNamesLong();
		$MonthsNamesTruntucated = array();

		foreach( $MonthsNamesLong as $ml ) {
			$MonthsNamesTruntucated[] = substr( $ml, 0, $nbr_of_chars );
		}
		
		return $MonthsNamesTruntucated;
	}

	
	/**
	 *  performing many DB queries
	 *
	 * @since 2.3.x (mic)
	 * @param array $queries_arr    holds all queries
	 * @param bool	$printErrorMsg  supress error message
	 * @return bool                 true if all queries were successful
	 */
	function populateSQL( $queries_arr, $printErrorMsg = true ) {
		$bResult = true;
		
		$this->_getDB();

		foreach( $queries_arr as $query) {
			$this->db->setQuery( $query );
			if( !$this->db->query() ) {
				$bResult &= false;
				if( $printErrorMsg ) {
					echo '<br/>' . $this->db->getErrorMsg() . '<br/>' . $query;
				}
			}
		}
		
		return $bResult;
	}
	
	
	
	/**
	 * Optimize all JS tables
	 *
	 * @return bool - true on success
	 */
	function optimizeAllJSTables() {
		$bResult = true;
		
		$JSSystemConst = new js_JSSystemConst();
		
		require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'database' .DIRECTORY_SEPARATOR. 'select.one.value.php' );
		$JSDbSOV = new js_JSDbSOV();//we create object to not rise PHP notice
		
		foreach( $JSSystemConst->allJSDatabaseTables as $db_table_name) {
			$bResult &= $JSDbSOV->optimizeTable($db_table_name);
		}
		
		return $bResult;
	}
	
	function getUrlToImages() {
		if (js_getJoomlaVesrion_IsJ15x() == true) {
	        //$url_to_generate_img = JURI::base(true).//line to joomla 1.5.9 with and without legacy
			return str_replace( 'administrator/', '', JURI::base() ) . 'components/com_joomlastats/images/';
    	} else {
        	global $mosConfig_live_site;//line joomla 1.0.15
        	return $mosConfig_live_site . '/components/com_joomlastats/images/';
    	}
	}

	/** 
	 *  $image_name      eg. 'explorer' without extension, mainly value from column 'sys_img' or 'browser_img'
	 *  $directory_name  eg. 'browser-png-16x16-1'
	 *  return path that could be used in <img src="... tag
	 *
	 *  Example:
	 *    getImageWithUrl('explorer', 'browser-png-16x16-1') -> '/components/com_joomlastats/images/browser-png-16x16-1/explorer.png'
	 */
	function getImageWithUrl($image_name, $directory_name) {
        $parts = explode('-', $directory_name);
        
        return $this->getUrlToImages().$directory_name.'/'.$image_name.'.'.$parts[1];//$parts[1] is images_extension
	}
	
	
	/**
	 *  Compare JS versions.
	 *  It is higly recomended to use this function as is it shown in examples.
	 *
	 *  NOTICE: This function return bool! 
	 *
	 *  results (examples):
	 *  JSVersionCompare('4.0.4.10',  '4.0.4.11', '<')  -> true
	 *  JSVersionCompare('4.0.5.10',  '4.0.4.11', '<')  -> false
	 *  
	 *  JSVersionCompare('2.2.3',     '2.2.3.113', '<') -> true - that is reason that we always should use 4 sections JS version numeration
	 *  JSVersionCompare('2.2.0.83',  '2.2.3.113', '<') -> true
	 *  JSVersionCompare('2.3.2.176', '2.2.3.113', '<') -> false
	 *  JSVersionCompare('2.2.2.168', '2.2.3.113', '<') -> true
	 *  JSVersionCompare('',          '2.2.3.113', '<') -> true
	 *
	 *  JSVersionCompare('2.2.3.113',     '2.2.3.113',     '<') -> false
	 *  JSVersionCompare('2.2.3.113 dev', '2.2.3.113',     '<') -> true
	 *  JSVersionCompare('2.2.3.113',     '2.2.3.113 dev', '<') -> false
	 *  JSVersionCompare('2.3.0.216 dev', '2.3.0.217',     '<') -> true
	 *  JSVersionCompare('2.3.0.216 dev', '2.3.0.215',     '<') -> false
	 */	
	function JSVersionCompare( $JSversion1, $JSversion2, $operator ) {
		if (version_compare( $JSversion1, $JSversion2, $operator ))
			return true;
		else
			return false;
	}
	
}
