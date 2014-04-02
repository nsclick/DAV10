<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: router.php 561 2008-01-17 11:34:40Z mjaz $
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

require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomdoc'.DS.'docman.class.php');
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomdoc'.DS.'classes'.DS.'DOCMAN_utils.class.php');
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomdoc'.DS.'helpers'.DS.'factory.php');

$_DOCMAN = &DocmanFactory::getDocman(); 
$_DMUSER = &DocmanFactory::getDmuser();
if(!is_object($_DOCMAN)) {
	$_DOCMAN = new dmMainFrame();
    $_DMUSER = $_DOCMAN->getUser();
}

class JoomdocRouterHelper {
    function getDoc($id) {

        static $docs;

        if(!isset($docs)) {
        	$docs = array();
        }

    	if(!isset($docs[$id])) {
            $docs[$id] = false;
            $db = & JFactory::getDBO();
    		$docs[$id] = new mosDMDocument($db);
            $docs[$id]->load($id);
        }

        return $docs[$id];
    }
}


function JoomdocBuildRoute(&$query) {
    jimport('joomla.filter.output');


    $segments = array();

    // check for task=...
    if(!isset($query['task'])) {
        return $segments;
    }
    $segments[] = $query['task'];

    // check for gid=...
    $gid = isset($query['gid']) ? $query['gid'] : 0;


    if(in_array($query['task'], array('cat_view', 'upload')) ) {
        // create the category slugs
        $cats = & DOCMAN_Cats::getCategoryList();
        $cat_slugs = array();
        while($gid AND isset($cats[$gid])) {
        	$cat_slugs[] = $gid.':'.JFilterOutput::stringURLSafe($cats[$gid]->name);
            $gid = $cats[$gid]->parent_id;
        }
        $segments = array_merge($segments, array_reverse($cat_slugs));
    } else {
        // create the document slug
        $doc = JoomdocRouterHelper::getDoc($gid);
        if($doc->id) {
            $segments[] = $gid.':'.JFilterOutput::stringURLSafe($doc->dmname);
        }
    }

    unset($query['gid']);
    unset($query['task']);

    return $segments;
}

function JoomdocParseRoute($segments){
    $vars = array();

    //Get the active menu item
    $menu =& JSite::getMenu();
    $item =& $menu->getActive();

    // Count route segments
    if(!($count = count($segments))) {
        return $vars;
    }

    if( isset($segments[0]) ) {
        $vars['task'] = $segments[0];

        if(in_array($segments[0], array('cat_view', 'upload'))) {
            $vars['gid'] = (int) $segments[$count-1];
    	} else {
            $vars['gid'] = isset($segments[1]) ? (int) $segments[1] : 0;
        }
    }

    return $vars;
}