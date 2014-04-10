<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


/** 
 *    HOW TO RUN JOOMLASTATS FOR NON-JOOMLA PAGES
 *
 * a) Configure JoomlaStats stand alone version by editing 'stand.alone.configuration.php' file
 *     You find it in: '(...)\joomla\administrator\components\com_joomlastats\database\stand.alone.configuration.php'
 *
 * b) Activate JoomlaStats - use one of below methods:
 *     b1) Include JoomlaStats count file directly to other PHP pages.
 *           (paste below line to pages that You want to count visitors)
 *           Fix path in below line!!!
 *         include(dirname(__FILE__) .DIRECTORY_SEPARATOR. 'joomla' .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_joomlastats' .DIRECTORY_SEPARATOR. 'stand.alone.joomlastats.inc.php');
 *
 *     b2) Add to below javascript to pages that you want to count visitors.
 *
 *
 * Testing:
 *	  a)  Open activation file in web browser, eg:
 *           http://my.domain.com/joomla/components/com_joomlastats/stand.alone.joomlastats.inc.php
 *    b1) If something goes wrong You should see error (if Your PHP settings are set in that way)
 *    b2) If You see blank page probalby every thing is OK. Go to Joomla administration panel
 *           to JoomlaStats statistics page and chek if You were counted.
 *
 *
 * NOTICE:
 *   If You activate JoomlaStats by using 'Stand Alone' method, some JoomlaStats features will be 
 *   unavailable (like determine if user was logged to joomla or not)
 */
 

 
//this file must have direct access!! - It is stand alone version!
//defined('_VALID_MOS') or die ('Direct Access to this location is not allowed.');
/** _JS_STAND_ALONE define tell Us that is stand alone version */
define('_JS_STAND_ALONE', true);

// there is no sense making below thing (and moving above line to user script)
//if( !defined( '_JS_STAND_ALONE' ) ) {
//	die( 'JS: No Direct Access' );
//}


//include JoomlaStats count classes
	require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'count.classes.php' );



//perform count action
	$JSCountVisitor = new js_JSCountVisitor();
	$JSCountVisitor->hourdiff = $JConfigArr['offset'];
	$JSCountVisitor->countVisitor();

	
