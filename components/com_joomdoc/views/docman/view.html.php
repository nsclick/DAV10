<?php

/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: view.html.php 1 2009-09-01 13:31:26Z j.trumpes $
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

ob_start();

jimport ( 'joomla.application.component.view' );

require_once (JPATH_COMPONENT_HELPERS . DS . 'categories.php');
require_once (JPATH_COMPONENT_HELPERS . DS . 'documents.php');

define ( 'DTMP_DOCBROWSE', 'page_docbrowse.tpl.php' );

ob_end_clean();

class DocmanViewDocman extends JView {
	function display() {
		ob_start();
		$docman = &DocmanFactory::getDocman();
		$tpl = $docman->getCfg('icon_theme');
		$params = DocmanHelper::getMenuParams ();
		$gid = JRequest::getInt ( 'gid', $params->get ( 'cat_id', 0 ) );
		$html = new StdClass ( );
		$html->menu = DocmanHelper::fetchMenu ( $gid, $tpl );
		$html->pathway = DocmanHelper::fetchPathway ( $gid, $tpl );
		$html->category = '';
		if ($gid > 0) {
			$html->category = CategoriesHelper::fetchCategory ( $gid, $tpl );
		}
		$html->cat_list = CategoriesHelper::fetchCategoryList ( $gid, $tpl );
		$html->doc_list = DocumentsHelper::fetchDocumentList ( $gid, $tpl );
		$html->pagenav = DocmanHelper::fetchPageNav ( $gid, $tpl );
		$html->pagetitle = DocmanHelper::fetchPageTitle ( $gid, $tpl );
		$path = DocmanHelper::getPath ( DTMP_DOCBROWSE, $tpl, true );
		$theme = &new DOCMAN_Theme ( );
		$theme->path = $path;
		$theme->assignRef ( 'html', $html );
		$theme->loadFilter ( 'trimwhitespace' );
		ob_end_clean();
		$theme->display ( DTMP_DOCBROWSE );
	}
}
?>