<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: DOCMAN_compat15.class.php 561 2008-01-17 11:34:40Z mjaz $
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

class DOCMAN_Compat {
    function mosLoadAdminModules( $position='left', $style=0 ) {
        global $mainframe;

        $modules    =& JModuleHelper::getModules($position);
        $pane       =& JPane::getInstance('sliders');
        echo $pane->startPane("content-pane");

        foreach ($modules as $module) {
            $title = $module->title ;
            echo $pane->startPanel( $title, "$position-panel" );
            echo JModuleHelper::renderModule($module);
            echo $pane->endPanel();
        }

        echo $pane->endPane();
    }

    function mosReadDirectory($path, $filter='.', $recurse=false, $fullpath=false) {
        $arr = array();
        if (!@is_dir( $path )) {
            return $arr;
        }
        $handle = opendir( $path );

        while ($file = readdir($handle)) {
            $dir = &DocmanFactory::getPathName( $path.'/'.$file, false );
            $isDir = is_dir( $dir );
            if (($file != ".") && ($file != "..")) {
                if (preg_match( "/$filter/", $file )) {
                    if ($fullpath) {
                        $arr[] = trim( DocmanFactory::getPathName( $path.'/'.$file, false ) );
                    } else {
                        $arr[] = trim( $file );
                    }
                }
                if ($recurse && $isDir) {
                    $arr2 = mosReadDirectory( $dir, $filter, $recurse, $fullpath );
                    $arr = array_merge( $arr, $arr2 );
                }
            }
        }
        closedir($handle);
        asort($arr);
        return $arr;
    }


    function editorArea($areaname, $content, $name, $width, $height, $rows, $cols) {
        $editor =& JFactory::getEditor();

        // JEditor::display( $name,  $html,  $width,  $height,  $col,  $row, [ $buttons = true])
        echo $editor->display($name, $content, $width, $height, $rows, $cols) ;
    }

    // Add the Calendar includes to the document <head> section
    function calendarJS() {
        JHTML::_('behavior.calendar');
    }

    function calendar($name, $value) {
        return JHTML::_('calendar', $value, $name, $name, '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19'));
    }

    /**
     * Removes illegal tags and attributes from html input
     */
    function inputFilter($html){
        /*
        jimport('joomla.filter.input');
        $filter = & JFilterInput::getInstance(array(), array(), 1, 1);
        return $filter->clean( $html );
        */

        // Replaced code to fix issue with img tags
        jimport('phpinputfilter.inputfilter');
        $filter = new InputFilter(array(), array(), 1, 1);
        return $filter->process( $html );
    }
}
