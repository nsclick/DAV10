<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: doclink.php 561 2008-01-17 11:34:40Z mjaz $
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
defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__).DS.'doclink.html.php';

global $_DOCMAN, $mainframe;

// Load classes and language
require_once($_DOCMAN->getPath('classes', 'utils'));
require_once($_DOCMAN->getPath('classes', 'file'));
require_once($_DOCMAN->getPath('classes', 'model'));

JRequest::setVar('tmpl', 'component');

$lang = JFactory::getLanguage();
$lang->load('plg_editors-xtd_joomdoclink');

function showDoclink() {
    global $mainframe;

    $assets = JURI::root()."components/com_joomdoc/assets";


    // add styles and scripts
    $doc =& JFactory::getDocument();
    JHTML::_('behavior.mootools');
    $doc->addStyleSheet($assets.'/css/doclink.css');
    $doc->addScript($assets.'/js/dlutils.js');
    $doc->addScript($assets.'/js/popup.js');
    $doc->addScript($assets.'/js/dialog.js');


    $rows = DOCMAN_utils::categoryArray();

    HTML_DMDoclink::showDoclink($rows);
}

function showListview(){
    global $_DOCMAN, $mainframe;
    $assets = JURI::root()."components/com_joomdoc/assets";
    // add styles and scripts
    $doc =& JFactory::getDocument();
    JHTML::_('behavior.mootools');
    $doc->addStyleSheet($assets.'/css/doclink.css');
    $doc->addScript($assets.'/js/sortabletable.js');
    $doc->addScript($assets.'/js/listview.js');
    $doc->addScript($assets.'/js/dldialog.js');


    if (isset($_REQUEST['catid'])) {
        $cid =  intval($_REQUEST['catid']);
    } else {
        $cid = 0;
    }
        //get folders
        $cats = DOCMAN_Cats::getChildsByUserAccess($cid);

        //get items
        if ($cid) {
            $docs = DOCMAN_Docs::getDocsByUserAccess($cid, 'name', 'ASC', 999, 0);
        } else {
            $docs = array();
        }


        //if ($entries_cnt)
        HTML_DMDoclink::createHeader();
        HTML_DMDoclink::createFolders($cats,$cid);
        HTML_DMDoclink::createItems($docs, $cid);
        HTML_DMDoclink::createFooter();

}