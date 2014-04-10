<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: logs.php 608 2008-02-18 13:31:26Z mjaz $
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

include_once dirname(__FILE__) . '/logs.html.php';
require_once($_DOCMAN->getPath('classes', 'mambots'));
JArrayHelper::toInteger(( $cid ));

switch ($task) {
    case "remove":
        removeLog($cid);
        break;
    case "show" :
    default :
        showLogs($option);
}

function showLogs($option) {
	$database = &JFactory::getDBO();
	$mainframe = &JFactory::getApplication();
	global $sectionid;

    // request
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', 10);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}{$sectionid}limitstart", 'limitstart', 0);
    $search = $mainframe->getUserStateFromRequest("search{$option}{$sectionid}", 'search', '');
    $search = $database->getEscaped(trim(strtolower($search)));
    $wheres = array();
    $wheres2 = array();

    // get the total number of records
    $query = "SELECT count(*)"
            ."\n FROM #__joomdoc_log";
    $database->setQuery($query);
    $total = $database->loadResult();

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    //  WHERE clause
    //$wheres[] = "(l.log_user = u.id OR l.log_user = 0)";
    $wheres[] = "l.log_docid = d.id";
    if ($search) {
        $wheres[] = "( LOWER(l.log_ip) LIKE '%$search%'"
                    ."\n OR LOWER(l.log_datetime) LIKE '%$search%'"
                    ."\n OR LOWER(IF(l.log_user, u.name, '"._DML_ANONYMOUS."')) LIKE '%$search%'"
                    ."\n OR LOWER(d.dmname) LIKE '%$search%'"
                    ."\n OR LOWER(l.log_browser) LIKE '%$search%'"
                    ."\n OR LOWER(l.log_os) LIKE '%$search%' )";
    }
    $where = "\n WHERE " . implode(' AND ', $wheres) ;

    $wheres2[] = "l.log_docid = d.id";
    if ($search) {
        $wheres2[] = "( LOWER(l.log_ip) LIKE '%$search%'"
                    ."\n OR LOWER(l.log_datetime) LIKE '%$search%'"
                    ."\n OR LOWER('"._DML_ANONYMOUS."') LIKE '%$search%'"
                    ."\n OR LOWER(d.dmname) LIKE '%$search%'"
                    ."\n OR LOWER(l.log_browser) LIKE '%$search%'"
                    ."\n OR LOWER(l.log_os) LIKE '%$search%' )";
    }
    $where2 = "\n WHERE " . implode(' AND ', $wheres2) ;




    // NAvigation
    
    require_once (JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'pagination.php');
    $pageNav = new JPagination($total, $limitstart, $limit);

    // Query
    $query = "( SELECT l.*, u.name AS user, d.dmname"
            ."\n FROM #__joomdoc_log AS l, #__users AS u, #__joomdoc AS d "
            .$where
            ."\n AND l.log_user = u.id )"
            ."\n UNION "
            ."( SELECT l.*, '"._DML_ANONYMOUS."' AS user, d.dmname"
            ."\n FROM #__joomdoc_log AS l, #__joomdoc AS d "
            .$where2
            ."\n AND l.log_user = 0"
            .")"
            ."\n ORDER BY log_datetime DESC";
    $database->setQuery($query, $limitstart, $limit);
    $rows = $database->loadObjectList();

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    HTML_DMLogs::showLogs($option, $rows, $search, $pageNav);
}

function removeLog($cid)
{
    DOCMAN_token::check() or die('Invalid Token');
    $mainframe = &JFactory::getApplication();

    $database = &JFactory::getDBO(); 
    $_DMUSER = &DocmanFactory::getDocman();
    
    $log = new mosDMLog($database);
    $rows = $log->loadRows($cid); // For log mambots

    if ($log->remove($cid)) {
        if ($rows) {
            $logbot = new DOCMAN_mambot('onLogDelete');
            $logbot->setParm('user' , $_DMUSER);
            $logbot->copyParm('process' , 'delete log');
            $logbot->setParm('rows' , $rows);
            $logbot->trigger(); // Delete the logs
        }
        $mainframe->redirect("index2.php?option=com_joomdoc&section=logs");
    }
}
