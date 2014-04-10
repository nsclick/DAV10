<?php
/**
 * JoomDOC Buttons Plugin - Joomla! Document Manager
 * @version $Id: standardbuttons.php 625 2008-02-22 21:12:47Z mjaz $
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

$mainframe->registerEvent( 'onFetchButtons', 'bot_standardbuttons' );

require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'factory.php');


function bot_standardbuttons($params) {
    $lang = JFactory::getLanguage();
    $lang->load('plg_joomdoc_standardbuttons');
    
    $_DOCMAN = &DocmanFactory::getDocman();
	$_DMUSER = &DocmanFactory::getDmuser();
    require_once($_DOCMAN->getPath('classes', 'button'));
    require_once($_DOCMAN->getPath('classes', 'token'));

    $doc        = & $params['doc'];
    $file       = & $params['file'];
    $objDBDoc   = $doc->objDBTable;

    $botParams  = bot_standardbuttonsParams();
    $js = "javascript:if(confirm('".JText::_('Are you sure?')."')) {window.location='%s'}";

    // format document links, ONLY those the user can perform.
    $buttons = array();

    if ($_DMUSER->canDownload($objDBDoc) AND $botParams->get('download', 1)) {
        $buttons['download'] = new DOCMAN_Button('download', _DML_TPL_DOC_DOWNLOAD, $doc->_formatLink('doc_download'));
    }

    if ($_DMUSER->canDownload($objDBDoc) AND $botParams->get('view', 1)) {
        $viewtypes = trim($_DOCMAN->getCfg('viewtypes'));
        if ($viewtypes != '' && ($viewtypes == '*' || stristr($viewtypes, $file->ext))) {
            $link = $doc->_formatLink('doc_view', null, true, 'index2.php');
            $params = new DMmosParameters('popup=1');
            $buttons['view'] = new DOCMAN_Button('view', JText::_('_DML_TPL_DOC_VIEW'), $link, $params);
        }
    }

    if($botParams->get('details', 1)) {
        $buttons['details'] = new DOCMAN_Button('details', JText::_('_DML_TPL_DOC_DETAILS'), $doc->_formatLink('doc_details'));
    }


    if ($_DMUSER->canEdit($objDBDoc) AND $botParams->get('edit', 1)) {
        $buttons['edit'] = new DOCMAN_Button('edit', JText::_('_DML_TPL_DOC_EDIT'), $doc->_formatLink('doc_edit'));
    }

    if ($_DMUSER->canMove($objDBDoc) AND $botParams->get('move', 1)) {
        $buttons['move'] = new DOCMAN_Button('move', JText::_('_DML_TPL_DOC_MOVE'), $doc->_formatLink('doc_move'));
    }

    if ($_DMUSER->canDelete($objDBDoc) AND $botParams->get('delete', 1)) {
        $link = $doc->_formatLink('doc_delete', null, null, null, true);
        $buttons['delete'] = new DOCMAN_Button('delete', JText::_('_DML_TPL_DOC_DELETE'), sprintf($js, $link));
    }

    if ($_DMUSER->canUpdate($objDBDoc) AND $botParams->get('update', 1)) {
        $buttons['update'] = new DOCMAN_Button('update', JText::_('_DML_TPL_DOC_UPDATE'), $doc->_formatLink('doc_update'));
    }

    if ($_DMUSER->canReset($objDBDoc) AND $botParams->get('reset', 1)) {
        $buttons['reset'] = new DOCMAN_Button('reset', JText::_('_DML_TPL_DOC_RESET'), sprintf($js, $doc->_formatLink('doc_reset')));
    }

    if ($_DMUSER->canCheckin($objDBDoc) AND $objDBDoc->checked_out AND $botParams->get('checkout', 1)) {
        $params = new DMmosParameters('class=checkin');
        $buttons['checkin'] = new DOCMAN_Button('checkin', JText::_('_DML_TPL_DOC_CHECKIN'), $doc->_formatLink('doc_checkin'), $params);
    }

    if ($_DMUSER->canCheckout($objDBDoc) AND !$objDBDoc->checked_out AND $botParams->get('checkout', 1)) {
        $buttons['checkout'] = new DOCMAN_Button('checkout', JText::_('_DML_TPL_DOC_CHECKOUT'), $doc->_formatLink('doc_checkout'));
    }

    if ($_DMUSER->canApprove($objDBDoc) AND !$objDBDoc->approved AND $botParams->get('approve', 1)) {
        $params = new DMmosParameters('class=approve');
        $link   = $doc->_formatLink('doc_approve', null, null, null, true);
        $buttons['approve'] = new DOCMAN_Button('approve', JText::_('_DML_TPL_DOC_APPROVE'), $link, $params);
    }

    if ($_DMUSER->canPublish($objDBDoc) AND $botParams->get('publish', 1)) {
        $params = new DMmosParameters('class=publish');
        $link   = $doc->_formatLink('doc_publish', null, null, null, true);
        $buttons['publish'] = new DOCMAN_Button('publish', JText::_('_DML_TPL_DOC_PUBLISH'), $link, $params);
    }

    if ($_DMUSER->canUnPublish($objDBDoc) AND $botParams->get('publish', 1)) {
        $link   = $doc->_formatLink('doc_unpublish', null, null, null, true);
        $buttons['unpublish'] = new DOCMAN_Button('unpublish', JText::_('_DML_TPL_DOC_UNPUBLISH'), $link);
    }

    return $buttons;

}

function bot_standardbuttonsParams() {
    global $_MAMBOTS;
    $database = &JFactory::getDBO();

    $dbtable = '#__mambots';
    if(defined('_DM_J15')) {
    	$dbtable = '#__plugins';
    }

	// check if param query has previously been processed
    if ( !isset($_MAMBOTS->_docman_mambot_params['standardbuttons']) ) {
        // load mambot params info
        $query = "SELECT params"
        . "\n FROM $dbtable"
        . "\n WHERE element = 'standardbuttons'"
        . "\n AND folder = 'joomdoc'"
        ;
        $database->setQuery( $query );
        $params = $database->loadResult();

        // save query to class variable
        $_MAMBOTS->_docman_mambot_params['standardbuttons'] = $params;
    }

    // pull query data from class variable
    $botParams = new JParameter(  $_MAMBOTS->_docman_mambot_params['standardbuttons'] );
    return $botParams;
}