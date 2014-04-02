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

$mainframe->registerEvent('onSearch', 'plgSearchJFJoomdoc');
$mainframe->registerEvent('onSearchAreas', 'plgSearchJFJoomdocAreas');

function &plgSearchJFJoomdocAreas ()
{
    static $areas = array('joomdoc' => 'Documents');
    return $areas;
}

function plgSearchJFJoomdoc ($text, $phrase = '', $ordering = '', $areas = null)
{
    $db = & JFactory::getDBO();
    $user = & JFactory::getUser();
    
    $searchText = $text;
    
    if (is_array($areas)) {
        if (! array_intersect($areas, array_keys(plgSearchJFJoomdocAreas()))) {
            return array();
        }
    }
    
    $plugin = & JPluginHelper::getPlugin('search', 'jfjoomdoc');
    $pluginParams = new JParameter($plugin->params);
    
    $limit = $pluginParams->def('search_limit', 50);
    $activeLang = $pluginParams->def('active_language_only', 0);
    
    $text = trim($text);
    if ($text == '') {
        return array();
    }
    
    $wheres = array();
    switch ($phrase) {
        case 'exact':
            $text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
            $wheres2 = array();
            $wheres2[] = 'title LIKE ' . $text;
            $wheres2[] = 'text LIKE ' . $text;
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
                $wheres2[] = 'title LIKE ' . $word;
                $wheres2[] = 'text LIKE ' . $word;
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
    
    $registry = & JFactory::getConfig();
    $lang = $registry->getValue("config.jflang");
    
    $query = "SELECT id FROM #__languages WHERE code = '$lang'";
    $db->setQuery($query);
    $lid = (int) $db->loadResult();
    
    $join = $activeLang ? 'RIGHT' : 'LEFT';
    
    $query = 'SELECT * FROM ( SELECT jmd.id, catid, COALESCE(jf1.value,dmname) AS title, COALESCE(jf3.value,title) AS section,';
    $query .= ' COALESCE(jf2.value,dmdescription) AS text, dmdate_published AS created, dmcounter AS hits';
    $query .= ' FROM #__joomdoc AS jmd LEFT JOIN #__categories AS cat ON jmd.catid = cat.id';
    $query .= ' ' . $join . " JOIN #__jf_content AS jf1 ON jmd.id = jf1.reference_id AND jf1.reference_field = 'dmname'";
    $query .= " AND jf1.reference_table = 'joomdoc' AND jf1.language_id = $lid AND jf1.published = 1";
    $query .= ' ' . $join . " JOIN #__jf_content AS jf2 ON jmd.id = jf2.reference_id AND jf2.reference_field = 'dmdescription'";
    $query .= " AND jf2.reference_table = 'joomdoc' AND jf2.language_id = $lid AND jf2.published = 1";
    $query .= ' ' . $join . " JOIN #__jf_content AS jf3 ON cat.id = jf3.reference_id AND jf3.reference_field = 'title'";
    $query .= " AND jf3.reference_table = 'categories' AND jf3.language_id = $lid AND jf3.published = 1";
    $query .= ' WHERE jmd.published = 1 AND approved = 1';
    $query .= ' ) AS s WHERE ' . $where . ' ORDER BY ' . $order;
    $db->setQuery($query, 0, $limit);
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