<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: DOCMAN_compat10.class.php 561 2008-01-17 11:34:40Z mjaz $
 * @package JoomDOC
 * @copyright (C) 2003-2008 The DOCman Development Team
 *            Improved to JoomDOC by Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class DOCMAN_Compat {
    function mosLoadAdminModules( $position='left', $style=0 ) {
        return mosLoadAdminModules($position, $style);
    }

    function mosReadDirectory($path, $filter='.', $recurse=false, $fullpath=false) {
    	return mosReadDirectory( $path, $filter, $recurse, $fullpath);
    }

    function editorArea($areaname, $content, $name, $width, $height, $rows, $cols) {
        editorArea($areaname, $content, $name, $width, $height, $rows, $cols);
    }

    function calendarJS() {
    	$document = &JFactory::getDocument();
    	$urlRoot = JURI::root();
    	$document->addScriptDeclaration($urlRoot.'/media/system/js/calendar.js');
    	$document->addScriptDeclaration($urlRoot.'/media/system/js/calendar-setup.js');
    	$document->addStyleDeclaration($urlRoot.'/media/system/css/calendar-jos.css');
    }

    function calendar($name, $value) {
        JHTML::_('calendar', $value, $name, $name, '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19'));
    }

    /**
     * Removes illegal tags and attributes from html input
     */
    function inputFilter($html){
        $filter = new InputFilter(array(), array(), 1, 1);
        return $filter->process( $html );
    }
}