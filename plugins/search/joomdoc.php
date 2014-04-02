<?php
/**
 * JoomDOC Search Plugin - Joomla! Document Manager
 * @version $Id: standardbuttons.php 625 2008-02-22 21:12:47Z mjaz $
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
defined('_JEXEC') or die('Restricted access');

$joomdocBase = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc' . DS;

require_once ($joomdocBase . 'classes' . DS . 'DOCMAN_user.class.php');
require_once ($joomdocBase . 'classes' . DS . 'DOCMAN_utils.class.php');
require_once ($joomdocBase . 'helpers' . DS . 'factory.php');
require_once ($joomdocBase . 'docman.class.php');

$mainframe->registerEvent('onSearch', 'plgSearchJoomdoc');
$mainframe->registerEvent('onSearchAreas', 'plgSearchJoomdocAreas');

JPlugin::loadLanguage('plg_search_joomdoc');

function &plgSearchJoomdocAreas ()
{
    static $areas = array('joomdoc' => 'Documents');
    return $areas;
}

function plgSearchJoomdoc ($text, $phrase = '', $ordering = '', $areas = null)
{
    $db = & JFactory::getDBO();
    $user = & JFactory::getUser();
    
    $searchText = $text;
    
    if (is_array($areas)) {
        if (! array_intersect($areas, array_keys(plgSearchJoomdocAreas()))) {
            return array();
        }
    }
    
    $plugin = & JPluginHelper::getPlugin('search', 'joomdoc');
    $pluginParams = new JParameter($plugin->params);
    
    $limit = $pluginParams->def('search_limit', 50);
    
    $text = trim($text);
    if ($text == '') {
        return array();
    }
    
    $wheres = array();
    switch ($phrase) {
        case 'exact':
            $text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
            $wheres2 = array();
            $wheres2[] = 'dmname LIKE ' . $text;
            $wheres2[] = 'dmdescription LIKE ' . $text;
            $where = '(' . implode(') OR (', $wheres2) . ')';
            break;
        
        case 'all':
        case 'any':
        default:
            $words = explode(' ', $text);
            $wheres = array();
            foreach ($words as $word) {
                $word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);
                $wheres2 = array();
                $wheres2[] = 'dmname LIKE ' . $word;
                $wheres2[] = 'dmdescription LIKE ' . $word;
                $wheres[] = implode(' OR ', $wheres2);
            }
            $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
            break;
    }
    
    switch ($ordering) {
        case 'oldest':
            $order = 'created ASC';
            break;
        
        case 'popular':
            $order = 'hits DESC';
            break;
        
        case 'alpha':
            $order = 'title ASC';
            break;
        
        case 'category':
            $order = 'section ASC, title ASC';
            break;
        
        case 'newest':
        default:
            $order = 'created DESC';
    }
    
    $query = 'SELECT * FROM ( SELECT jmd.id, catid, dmname AS title, title AS section,';
    $query .= ' dmdescription AS text, dmdate_published AS created, dmcounter AS hits';
    $query .= ' FROM #__joomdoc AS jmd LEFT JOIN #__categories AS cat ON jmd.catid = cat.id';
    $query .= ' WHERE ' . $where;
    $query .= ' AND jmd.published = 1 AND approved = 1) AS s ORDER BY ' . $order;
    
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    
    $dmuser = DocmanFactory::getDmuser();
    
    foreach ($rows as $i => &$row) {
        $row->browsernav = 2;
        if ($dmuser->canDownload($row->id) === false) {
            unset($rows[$i]);
        }
        $row->href = DOCMAN_Utils::taskLink('doc_details', $row->id, null, true);
    }
    
    return $rows;
}
?>