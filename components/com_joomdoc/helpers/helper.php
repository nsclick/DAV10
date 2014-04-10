<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: helper.php 1 2009-09-01 13:31:26Z j.trumpes $
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
define ( 'DTMP_PAGENAV', 'general/pagenav.tpl.php' );
define ( 'DTMP_PAGETITLE', 'general/pagetitle.tpl.php' );
define ( 'DTMP_MENU', 'general/menu.tpl.php' );
define ( 'DTMP_PATHWAY', 'general/pathway.tpl.php' );

class DocmanHelper {
	function getTemplate(&$view, $tpl) {
		jimport ( 'joomla.filesystem.path' );
		$filetofind = $view->_createFileName ( 'template', array ('name' => $tpl ) );
		$template = JPath::find ( $this->_path ['template'], $filetofind );		
		$template = str_replace ( DocmanHelper::_getTmpPath (), '', $template );
		$template = str_replace ( DocmanHelper::_getDefaultPath (), '', $template );
		
		return '..' . DS . '..' .DS . '..' .DS . $template;
	}
	function getPath($file, $tpl, $themes = false) {
		$r = $themes ? 'themes' . DS . $tpl . DS : $tpl . DS;
		$tmp_path = DocmanHelper::_getTmpPath () . $r;
		$default_path = DocmanHelper::_getDefaultPath () . $r;
		return file_exists ( $tmp_path . $file ) ? $tmp_path : $default_path;
	}
	private function _getTmpPath() {
		$mainframe = &JFactory::getApplication ();
		return JPATH_THEMES . DS . $mainframe->setTemplate . DS . 'html' . DS . 'com_joomdoc' . DS;
	}
	private function _getDefaultPath() {
		return JPATH_COMPONENT_SITE . DS . 'views' . DS;
	}
	
	function getMenuParams() {
		jimport ( 'joomla.application.menu' );
		$menu = & JMenu::getInstance ( 'site' );
		$Itemid = JRequest::getInt ( 'Itemid' );
		return $menu->getParams ( $Itemid );
	}
	function fetchMenu($gid = 0, $vtpl) {
		$docman = &DocmanFactory::getDocman ();
		$dmuser = &DocmanFactory::getDmuser ();
		// create links
		$links = new StdClass ( );
		$links->home = DocmanHelper::_taskLink ( null );
		$links->search = DocmanHelper::_taskLink ( 'search_form' );
		$links->upload = DocmanHelper::_taskLink ( 'upload', $gid );
		
		// create perms
		$perms = new StdClass ( );
		$perms->view = DM_TPL_AUTHORIZED;
		$perms->search = DM_TPL_AUTHORIZED;
		$perms->upload = DM_TPL_NOT_AUTHORIZED;
		
		if ($dmuser->canUpload ()) {
			$perms->upload = DM_TPL_AUTHORIZED;
		} else {
			if ($dmuser->userid == 0 && $docman->getCfg ( 'user_upload' ) != - 1) {
				$perms->upload = DM_TPL_NOT_LOGGED_IN;
			}
		}
		
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = DocmanHelper::getPath ( DTMP_MENU, $vtpl,true );
		// Assign values to the Savant instance.
		$tpl->assignRef ( 'links', $links );
		$tpl->assignRef ( 'perms', $perms );
		// Display a template using the assigned values.
		return $tpl->fetch ( DTMP_MENU );
		
	//return HTML_docman::fetchMenu ( $links, $perms );
	}
	function fetchPathWay($id, $vtpl) {
		if (! $id > 0) {
			return;
		}
		
		// get the category ancestors
		$ancestors = & DOCMAN_Cats::getAncestors ( $id );
		
		// add home link
		$home = new StdClass ( );
		$home->name = _DML_TPL_CAT_VIEW;
		$home->title = _DML_TPL_CAT_VIEW;
		$home->link = DOCMAN_Utils::taskLink ( '' );
		
		$ancestors [] = &$home;
		// reverse the array
		$ancestors = array_reverse ( $ancestors );
		// display the pathway
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = DocmanHelper::getPath ( DTMP_PATHWAY, $vtpl,true );
		// Assign values to the Savant instance.
		$tpl->assignRef ( 'links', $ancestors );
		// Display a template using the assigned values.
		return $tpl->fetch ( DTMP_PATHWAY );
	}
	function _taskLink($task, $gid = '', $params = null, $sef = true) {
		return DOCMAN_Utils::taskLink ( $task, $gid, $params, $sef );
	}
	function fetchPageNav($gid, $vtpl) {
		$dmuser = &DocmanFactory::getDmuser ();
		$docman = &DocmanFactory::getDocman ();
		
		$limit = JRequest::getInt ( 'limit', $docman->getCfg ( 'perpage' ) );
		$total = DOCMAN_Cats::countDocsInCatByUser ( $gid, $dmuser );
		
		if ($total <= $limit) {
			return;
		}
		
		$Itemid = JRequest::getInt ( 'Itemid' );
		$ordering = JRequest::getVar ( 'ordering', $docman->getCfg ( 'default_order' ) );
		$direction = strtoupper ( JRequest::getVar ( 'dir', $docman->getCfg ( 'default_order2' ) ) );
		$limitstart = JRequest::getInt ( 'limitstart' );
		
		jimport ( 'joomla.html.pagination' );
		$pageNav = new JPagination ( $total, $limitstart, $limit );
		
		$link = 'index.php?option=com_joomdoc&amp;task=cat_view' . '&amp;gid=' . $gid . '&amp;dir=' . $direction . '&amp;order=' . $ordering . '&amp;Itemid=' . $Itemid;
		
		$tpl = &new DOCMAN_Theme ( );
		
		$tpl->path = DocmanHelper::getPath ( DTMP_PAGENAV, $vtpl,true );
		
		$tpl->assignRef ( 'pagenav', $pageNav );
		$tpl->assignRef ( 'link', $link );
		return $tpl->fetch ( DTMP_PAGENAV );
	}
	function fetchPageTitle($id, $vtpl) {
		if (! $id > 0) {
			return;
		}
		// get the category ancestors
		$ancestors = & DOCMAN_Cats::getAncestors ( $id );
		// reverse the array
		$ancestors = array_reverse ( $ancestors );
		// display the pathway
		$tpl = &new DOCMAN_Theme ( );
		
		$tpl->path = DocmanHelper::getPath ( DTMP_PAGETITLE, $vtpl,true );
		// Assign values to the Savant instance.
		$tpl->assignRef ( 'pagetitle', $ancestors );
		// Display a template using the assigned values.
		return $tpl->fetch ( DTMP_PAGETITLE );
	}
	function getGid() {
		$params = DocmanHelper::getMenuParams ();
		return JRequest::getInt ( 'gid', $params->get ( 'cat_id', 0 ) );
	}
	function _returnTo($task, $msg = '', $gid = '', $params = null) {
		return DOCMAN_Utils::returnTo ( $task, $msg, $gid, $params );
	}
}
?>