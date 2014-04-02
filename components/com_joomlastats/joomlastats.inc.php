<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



/**
 *  All visitors countings should be processed through this file
 *      without any exeption!!!
 *
 *  This is one common place in we can set/change settings for counting
 */
 
 
 

// no direct access
if( ( !defined( '_VALID_MOS' ) && !defined( '_JS_STAND_ALONE' ) ) && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'count.classes.php' );


	
// create js_JSCountVisitor object to register visitors
$JSCountVisitor = new js_JSCountVisitor();
$JSCountVisitor->countVisitor();


