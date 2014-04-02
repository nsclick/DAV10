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

require_once (JPATH_COMPONENT_HELPERS . DS . 'downloads.php');

define ( 'DTMP_DOCLICENSE', 'page_doclicense.tpl.php' );

ob_end_clean();

class DocmanViewDownload extends JView {
	function display() {
		ob_start();
		$docman = &DocmanFactory::getDocman();
		$tpl = $docman->getCfg('icon_theme');
		$docman = &DocmanFactory::getDocman ();
		$db = &JFactory::getDBO ();
		$params = DocmanHelper::getMenuParams ();
		$gid = JRequest::getInt ( 'gid', $params->get ( 'cat_id', 0 ) );
		$doc = new DOCMAN_Document ( $gid );
		$data = &$doc->getDataObject ();
		
		//check if we need to display a license
		if ($docman->getCfg ( 'display_license' ) && ($data->dmlicense_display && $data->dmlicense_id)) {
			//fetch the license form
			$html = new StdClass ( );
			$html->doclicense = DownloadsHelper::fetchDocumentLicenseForm ( $gid );
			
			//get the license text
			$license = new mosDMLicenses ( $db );
			$license->load ( $data->dmlicense_id );
			$path = DocmanHelper::getPath(DTMP_DOCLICENSE,$tpl,true);
			$tpl = &new DOCMAN_Theme ( );
			$tpl->path = $path;
			// Assign values to the Savant instance.
			$tpl->assignRef ( 'html', $html );
			$tpl->assignRef ( 'license', $license->license );
			// load a filter to trim whitespace
			$tpl->loadFilter ( 'trimwhitespace' );
			// Display a template using the assigned values.
			ob_end_clean();
			$tpl->display ( DTMP_DOCLICENSE );
		
		} else {
			ob_end_clean();
			DownloadsHelper::download ( $doc, false );
		}
	}
}