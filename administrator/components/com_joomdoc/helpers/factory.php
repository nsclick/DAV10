<?php

/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: factory.php 561 2009-09-01 11:34:40Z j.trumpes $
 * @package JoomDOC
 * @copyright (C) 2009 Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
class DocmanFactory
{

    function getDocman ()
    {
		ob_start();
        global $mainframe;
        static $instance;
        if (! is_object($instance)) {
            $instance = new dmMainFrame();
        }
        if ($mainframe->isSite()) {
            $lang = &JFactory::getLanguage();
            $lang->load('com_joomdoc');
            $instance->loadLanguage('frontend');
        }
		ob_end_clean();
        return $instance;
    }

    function getDmuser ()
    {
        static $instance;
        if (! is_object($instance)) {
            $docman = DocmanFactory::getDocman();
            $instance = $docman->getUser();
        }
        return $instance;
    }

    function setTheme (&$docman, $theme, $icon_theme = '')
    {
        $theme = str_replace(JPATH_ROOT, '', $theme);
        $docman->setCfg('themes', $theme);
        //$docman->_path->themes = $theme;
        $docman->setCfg($icon_theme, '');
    }

    function getPathName ($p_path, $p_addtrailingslash = true)
    {
        jimport('joomla.filesystem.path');
        $path = JPath::clean($p_path);
        if ($p_addtrailingslash) {
            $path = rtrim($path, DS) . DS;
        }
        return $path;
    }

    function getToolTip ($tooltip, $title = '', $width = '', $image = 'tooltip.png', $text = '', $href = '', $link = 1)
    {
        // Initialize the toolips if required
        static $init;
        if (! $init) {
            JHTML::_('behavior.tooltip');
            $init = true;
        }
        
        return JHTML::_('tooltip', $tooltip, $title, $image, $text, $href, $link);
    }

    function getFormatDate ($date = 'now', $format = null, $offset = null)
    {
        
        if (! $format) {
            $format = JText::_('DATE_FORMAT_LC1');
        }
        
        return JHTML::_('date', $date, $format, $offset);
    }

    function getImageCheckAdmin ($file, $directory = '/images/', $param = NULL, $param_directory = '/images/', $alt = NULL, $name = NULL, $type = 1, $align = 'middle')
    {
        $attribs = array('align' => $align);
        return JHTML::_('image.administrator', $file, $directory, $param, $param_directory, $alt, $attribs, $type);
    }

    function getStripslashes (&$value)
    {
        $ret = '';
        if (is_string($value)) {
            $ret = stripslashes($value);
        } else {
            if (is_array($value)) {
                $ret = array();
                foreach ($value as $key => $val) {
                    $ret[$key] = DocmanFactory::getStripslashes($val);
                }
            } else {
                $ret = $value;
            }
        }
        return $ret;
    }
}
?>