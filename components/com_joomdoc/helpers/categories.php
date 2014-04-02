<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: categpries.php 1 2009-09-01 13:31:26Z j.trumpes $
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

define ( 'DTMP_CATEGORY_DEFAULT', 'categories/category.tpl.php' );
define ( 'DTMP_CATEGORY_LIST', 'categories/list.tpl.php' );

class CategoriesHelper {
	
	function fetchCategory($id, $vtpl) {
		$dmuser = DocmanFactory::getDmuser ();
		
		$cat = new DOCMAN_Category ( $id );
		
		// if the user is not authorized to access this category, redirect
		if (! $dmuser->canAccessCategory ( $cat->getDBObject () )) {
			DocmanHelper::_returnTo ( '', _DML_NOT_AUTHORIZED );
		}
		
		DOCMAN_Utils::processContentBots ( $cat, 'description' );
		
		$tpl = &new DOCMAN_Theme ( );
		
		$tpl->path = DocmanHelper::getPath ( DTMP_CATEGORY_DEFAULT, $vtpl,true );
		
		$tpl->assignRef ( 'links', $cat->getLinkObject () );
		$tpl->assignRef ( 'paths', $cat->getPathObject () );
		$tpl->assignRef ( 'data', $cat->getDataObject () );
		
		return $tpl->fetch ( DTMP_CATEGORY_DEFAULT );
	}
	
	function fetchCategoryList($id,$vtpl) {
		$theme = DocmanHelper::getPath ( DTMP_CATEGORY_LIST, $vtpl,true );
		$docman = &DocmanFactory::getDocman();
		DocmanFactory::setTheme($docman,$theme);
		
		$children = DOCMAN_Cats::getChildsByUserAccess ( $id );
		$items = array ();
		foreach ( $children as $child ) {
			$cat = new DOCMAN_Category ( $child->id );
			
			// process content mambots
			DOCMAN_Utils::processContentBots ( $cat, 'description' );
			
			$item = new StdClass ( );
			$item->links = &$cat->getLinkObject ();
			$item->paths = &$cat->getPathObject ();
			$item->data = &$cat->getDataObject ();
			$items [] = $item;
		}
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = $theme;
		$tpl->assignRef ( 'items', $items );
		return $tpl->fetch ( DTMP_CATEGORY_LIST );
	}
}
?>