<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: cleardata.php 608 2008-02-18 13:31:26Z mjaz $
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

include_once dirname(__FILE__) . DS.'cleardata.html.php';
require_once($_DOCMAN->getPath('classes', 'cleardata'));

switch ($task) {
    case 'remove':
        clearData( $cid );
        break;

    default:
    case 'show':
        showClearData();
}

function clearData( $cid = array() )
{
    DOCMAN_token::check() or die('Invalid Token');
	$mainframe = &JFactory::getApplication();
    $msgs=array();

    $cleardata = new DOCMAN_Cleardata( $cid );
    $cleardata->clear();
    $rows = & $cleardata->getList();
    foreach( $rows as $row ){
        $msgs[] = $row->msg;
    }
    $mainframe->redirect( 'index2.php?option=com_joomdoc&section=cleardata', implode(' | ', $msgs));
}

function showClearData(){
    $cleardata = new DOCMAN_Cleardata();
    $rows = & $cleardata->getList();
	HTML_DMClear::showClearData( $rows );
}
