<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: search.php 1 2009-09-01 13:31:26Z j.trumpes $
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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$GLOBALS ['search_mode'] = JRequest::getString ( 'search_mode', 'any' );
$GLOBALS ['ordering'] = JRequest::getString ( 'ordering', 'newest' );
$GLOBALS ['invert_search'] = isset($_REQUEST['invert_search']) ? 1 : 0;
$GLOBALS ['reverse_order'] = isset($_REQUEST['reverse_order']) ? 1 : 0;
$GLOBALS ['search_where'] = JRequest::getVar('search_where',array(),'default','array',array());
$GLOBALS ['search_phrase'] = JRequest::getString ( 'search_phrase', '' );
$GLOBALS ['search_catid'] = JRequest::getInt ( 'catid', 0 );


require_once (JPATH_COMPONENT.DS.'views'.DS.'search'.DS.'tmpl'.DS.'search_form.php');

class SearchHelper {
	
	function fetchSearchForm($gid, $itemid) {
		global $search_mode, $ordering, $invert_search, $reverse_order, $search_where, $search_phrase, $search_catid;
		// category select list
		

		$options = array (JHTML::_ ( 'select.option', '0', _DML_ALLCATS, 'value', 'text' ) );
		$lists ['catid'] = dmHTML::categoryList ( $search_catid, "", $options );
		
		$mode = array ();
		$mode [] = JHTML::_ ( 'select.option', 'any', _DML_SEARCH_ANYWORDS, 'value', 'text' );
		$mode [] = JHTML::_ ( 'select.option', 'all', _DML_SEARCH_ALLWORDS, 'value', 'text' );
		$mode [] = JHTML::_ ( 'select.option', 'exact', _DML_SEARCH_PHRASE, 'value', 'text' );
		$mode [] = JHTML::_ ( 'select.option', 'regex', _DML_SEARCH_REGEX, 'value', 'text' );
		
		$lists ['search_mode'] = JHTML::_ ( 'select.genericlist', $mode, 'search_mode', 'id="search_mode" class="inputbox"', 'value', 'text', $search_mode, null, false, false );
		
		$orders = array ();
		$orders [] = JHTML::_ ( 'select.option', 'newest', _DML_SEARCH_NEWEST, 'value', 'text' );
		$orders [] = JHTML::_ ( 'select.option', 'oldest', _DML_SEARCH_OLDEST, 'value', 'text' );
		$orders [] = JHTML::_ ( 'select.option', 'popular', _DML_SEARCH_POPULAR, 'value', 'text' );
		$orders [] = JHTML::_ ( 'select.option', 'alpha', _DML_SEARCH_ALPHABETICAL, 'value', 'text' );
		$orders [] = JHTML::_ ( 'select.option', 'category', _DML_SEARCH_CATEGORY, 'value', 'text' );
		
		$lists ['ordering'] = JHTML::_ ( 'select.genericlist', $orders, 'ordering', 'id="ordering" class="inputbox"', 'value', 'text', $ordering, null, false, false );
		
		$lists ['invert_search'] = '<input type="checkbox" class="inputbox" name="invert_search" ' . ($invert_search ? ' checked ' : '') . '/>';
		$lists ['reverse_order'] = '<input type="checkbox" class="inputbox" name="reverse_order" ' . ($reverse_order ? ' checked ' : '') . '/>';
		
		$matches = array ();
		if ($search_where && count ( $search_where ) > 0) {
			foreach ( $search_where as $val ) {
				$matches [] = JHTML::_('select.option', $val, $val );
			}
		} else {
			$matches [] = JHTML::_ ( 'select.option', 'search_description', 'search_description', 'value', 'text' );
		}
		
		$where = array ();
		$where [] = JHTML::_ ( 'select.option', 'search_name', _DML_NAME, 'value', 'text' );
		$where [] = JHTML::_ ( 'select.option', 'search_description', _DML_DESCRIPTION, 'value', 'text' );
		$lists ['search_where'] = JHTML::_ ( 'select.genericlist', $where, 'search_where[]', 'id="search_where" class="inputbox" multiple="multiple" size="2"', 'value', 'text', empty($search_where) ? $where : $search_where, null, false, false );
		
		return HTML_DMSearch::searchForm ( $lists, $search_phrase );
	}
	
	function getSearchResult($gid, $itemid) {
		global $search_mode, $ordering, $invert_search, $reverse_order, $search_where, $search_phrase, $search_catid;
		
		$search_mode = ($invert_search ? '-' : '') . $search_mode;
		$searchList = array (array ('search_mode' => $search_mode, 'search_phrase' => $search_phrase ) );
		$ordering = ($reverse_order ? '-' : '') . $ordering;
		
		$rows = DOCMAN_Docs::search ( $searchList, $ordering, $search_catid, '', $search_where );
		
		// This acts as the search header - so they can perform search again
		if (count ( $rows ) == 0) {
			$msg = _DML_NOKEYWORD;
		} else {
			$msg = sprintf ( _DML_SEARCH . ' ' . _DML_SEARCH_MATCHES, count ( $rows ) );
		}
		
		$items = array ();
		if (count ( $rows ) > 0) {
			foreach ( $rows as $row ) {
				// onFetchDocument event, type = list
				$bot = new DOCMAN_mambot ( 'onFetchDocument' );
				$bot->setParm ( 'id', $row->id );
				$bot->copyParm ( 'type', 'list' );
				$bot->trigger ();
				if ($bot->getError ()) {
					DocmanHelper::_returnTo ( 'cat_view', $bot->getErrorMsg () );
				}
				
				// load doc
				$doc = & DOCMAN_Document::getInstance ( $row->id );
				
				// process content mambots
				DOCMAN_Utils::processContentBots ( $doc, 'dmdescription' );
				
				$item = new StdClass ( );
				$item->buttons = &$doc->getLinkObject ();
				$item->paths = &$doc->getPathObject ();
				$item->data = &$doc->getDataObject ();
				$item->data->category = $row->section;
				
				$items [] = $item;
			}
		}
		
		return $items;
	}
}
