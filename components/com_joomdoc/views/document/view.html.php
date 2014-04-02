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
require_once (JPATH_COMPONENT_HELPERS . DS . 'upload.php');

define ( 'DTMP_DOCDETAILS', 'page_docdetails.tpl.php' );
define ( 'DTMP_DOCEDIT', 'page_docedit.tpl.php' );
define ( 'DTMP_DOCMOVE', 'page_docmove.tpl.php' );
define ( 'DTMP_DOCUPLOAD', 'page_docupload.tpl.php' );
define ( 'DTMP_SCRIPT_DOCEDIT', 'script_docedit.tpl.php' );

ob_end_clean();

class DocmanViewDocument extends JView {
	function display() {
		ob_start();
		$docman = &DocmanFactory::getDocman ();
		$tpl = $docman->getCfg ( 'icon_theme' );
		$html = new StdClass ( );
		$gid = DocmanHelper::getGid ();
		$html->menu = DocmanHelper::fetchMenu ( $gid, $tpl );
		$html->docdetails = DocumentsHelper::fetchDocument ( $gid, $tpl );
		$path = DocmanHelper::getPath ( DTMP_DOCDETAILS, $tpl, true );
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = $path;
		// Assign values to the Savant instance.
		$tpl->assignRef ( 'html', $html );
		// load a filter to trim whitespace
		$tpl->loadFilter ( 'trimwhitespace' );
		// Display a template using the assigned values.
		ob_end_clean();
		$tpl->display ( DTMP_DOCDETAILS );
	}
	function _displayForm() {
		ob_start();
		$docman = &DocmanFactory::getDocman ();
		$tpl = $docman->getCfg ( 'icon_theme' );
		$gid = DocmanHelper::getGid ();
		$html = new StdClass ( );
		$html->menu = DocmanHelper::fetchMenu ( $gid, $tpl );
		$html->docedit = DocumentsHelper::fetchEditDocumentForm ( $gid );
		$path = DocmanHelper::getPath ( DTMP_DOCEDIT, $tpl, true );
		// Assign values to the Savant instance.
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = $path;
		$tpl->assignRef ( 'html', $html );
		// load a filter to trim whitespace
		$tpl->loadFilter ( 'trimwhitespace' );
		// Display a template using the assigned values.
		DocmanViewDocument::importScript ();
		
		ob_end_clean();
		$tpl->display ( DTMP_DOCEDIT );
	}
	function _displayMove() {
		ob_start();
		$docman = &DocmanFactory::getDocman ();
		$tpl = $docman->getCfg ( 'icon_theme' );
		$html = new StdClass ( );
		$gid = DocmanHelper::getGid ();
		$html->menu = DocmanHelper::fetchMenu ( $gid, $tpl );
		$html->docmove = DocumentsHelper::fetchMoveDocumentForm ( $gid );
		$path = DocmanHelper::getPath ( DTMP_DOCMOVE, $tpl, true );
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = $path;
		// Assign values to the Savant instance.
		$tpl->assignRef ( 'html', $html );
		// load a filter to trim whitespace
		$tpl->loadFilter ( 'trimwhitespace' );
		// Display a template using the assigned values.
		ob_end_clean();
		$tpl->display ( DTMP_DOCMOVE );
	}
	function _displayUpload($update) {
		ob_start();
		$docman = &DocmanFactory::getDocman ();
		$tpl = $docman->getCfg ( 'icon_theme' );
		$step = JRequest::getInt ( 'step', 1 );
		$method = JRequest::getVar ( 'method' );
		$script = JRequest::getInt ( 'script' );
		$gid = DocmanHelper::getGid ();
		
		if ($script) {
			ob_end_clean();
			HTML_docman::scriptDocumentUpload ( $step, $method, $update );
			return;
		}
		
		//fetch the license form
		$html = new StdClass ( );
		$html->menu = DocmanHelper::fetchMenu ( $gid, $tpl );
		$html->docupload = UploadHelper::fetchDocumentUploadForm ( $gid, $step, $method, $update );
		$path = DocmanHelper::getPath ( DTMP_DOCUPLOAD, $tpl, true );
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = $path;
		// Assign values to the Savant instance.
		$tpl->assignRef ( 'html', $html );
		$tpl->assignRef ( 'step', $step );
		$tpl->assignRef ( 'method', $method );
		$tpl->assignRef ( 'update', $update );
		// load a filter to trim whitespace
		$tpl->loadFilter ( 'trimwhitespace' );
		// Display a template using the assigned values.
		if ($step == 3) {
			DocmanViewDocument::importScript ();
		}
		ob_end_clean();
		$tpl->display ( DTMP_DOCUPLOAD );
	}
	function importScript() {
		$f = JPATH_COMPONENT . DS . 'views' . DS . 'themes' . DS . $this->_layout . DS . 'templates' . DS . 'scripts' . DS . 'form_docedit.tpl.php';
		echo '<script type="text/javascript">';
		require_once ($f);
		echo '</script>';
	}
}
?>