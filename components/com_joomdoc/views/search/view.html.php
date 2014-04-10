<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

ob_start();
jimport ( 'joomla.application.component.view' );

require_once (JPATH_COMPONENT_HELPERS . DS . 'search.php');

define ( 'DTMP_DOCSEARCH', 'page_docsearch.tpl.php' );

ob_end_clean();

class DocmanViewSearch extends JView {
	function display($tpl = null) {
	ob_start();
		$docman = &DocmanFactory::getDocman();
		$tpl = $docman->getCfg('icon_theme');
		$params = DocmanHelper::getMenuParams ();
		$gid = JRequest::getInt ( 'gid', $params->get ( 'cat_id', 0 ) );
		$Itemid = JRequest::getInt ( 'Itemid' );
		$html = new StdClass ( );
		$html->menu = DocmanHelper::fetchMenu ( 0, $tpl );
		$html->searchform = SearchHelper::fetchSearchForm ( $gid, $Itemid );
		$task = JRequest::getCmd ( 'task' );
		switch ($task) {
			case 'search_form' :
				$items = array ();
				break;
			case 'search_result' :
				$items = SearchHelper::getSearchResult ( $gid, $Itemid );
				break;
		}
		$path = DocmanHelper::getPath ( DTMP_DOCSEARCH, $tpl, true );
		$tpl = &new DOCMAN_Theme ( );
		$tpl->path = $path;
		// Assign values to the Savant instance.
		$tpl->assignRef ( 'html', $html );
		$tpl->assignRef ( 'items', $items );
		// load a filter to trim whitespace
		$tpl->loadFilter ( 'trimwhitespace' );
		// Display a template using the assigned values.
		ob_end_clean();
		$tpl->display ( DTMP_DOCSEARCH );
	}
}

?>