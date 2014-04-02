<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: licenses.php 638 2008-03-01 12:49:09Z mjaz $
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

include_once dirname(__FILE__) . '/licenses.html.php';
JArrayHelper::toInteger(( $cid ));

switch ($task) {
    case "edit":
        $cid = (isset( $cid[0] )) ? $cid[0] : 0;
        editLicense($option, $cid);
        break;
    case "remove":
        removeLicense($cid, $option);
        break;
    case "apply":
    case "save":
        saveLicense($option);
        break;
    case "cancel":
        cancelLicense($option);
        break;
    case "show":
    default :
        showLicenses($option);
}

function editLicense($option, $uid)
{
    $database = &JFactory::getDBO();

    // disable the main menu to force user to use buttons
    $_REQUEST['hidemainmenu']=1;

    $row = new mosDMLicenses($database);
    $row->load($uid);
    HTML_DMLicenses::editLicense($option, $row);
}

function saveLicense($option)
{
    DOCMAN_token::check() or die('Invalid Token');

    $database = &JFactory::getDBO(); 
    $task = JRequest::getCmd('task');
    $mainframe = &JFactory::getApplication();

    $row = new mosDMLicenses($database);
    //$isNew = ($row->id == 0);

    if (!$row->bind(DOCMAN_Utils::stripslashes($_POST))) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }
    if (!$row->check()) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }
    if (!$row->store()) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }
    $row->checkin();

    if( $task == 'save' ) {
        $url = 'index2.php?option=com_joomdoc&section=licenses';
    } else { // $task = 'apply'
        $url = 'index2.php?option=com_joomdoc&section=licenses&task=edit&cid[0]='.$row->id;
    }

    $mainframe->redirect( $url, _DML_SAVED_CHANGES);
}

function cancelLicense($option)
{
    $database = &JFactory::getDBO();
    $mainframe = &JFactory::getApplication();
    $row = new mosDMLicenses($database);
    $row->bind(DOCMAN_Utils::stripslashes($_POST));
    $row->checkin();
    $mainframe->redirect("index2.php?option=$option&section=licenses");
}

function showLicenses($option)
{
    $database = &JFactory::getDBO();
    $mainframe = &JFactory::getApplication();
    global $sectionid;

    $catid = (int) $mainframe->getUserStateFromRequest("catid{$option}{$sectionid}", 'catid', 0);
    $limit = (int) $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', 10);
    $limitstart = (int) $mainframe->getUserStateFromRequest("view{$option}{$sectionid}limitstart", 'limitstart', 0);
    $search = $mainframe->getUserStateFromRequest("search{$option}{$sectionid}", 'search', '');
    $search = $database->getEscaped(trim(strtolower($search)));
    $where = array();
    if ($search) {
        $where[] = "LOWER(name) LIKE '%$search%'";
    }
    // get the total number of records
    $database->setQuery("SELECT count(*) FROM #__joomdoc_licenses" . (count($where) ? "\nWHERE " . implode(' AND ', $where) : ""));
    $total = $database->loadResult();
    echo $database->getErrorMsg();

    $id = JRequest::getVar( 'id', 0);

    require_once (JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'pagination.php');
    $pageNav = new JPagination($total, $limitstart, $limit);

    $query = "SELECT id, name, license"
            ."\n FROM #__joomdoc_licenses"
            .(count($where) ? "\n WHERE " . implode(' AND ', $where) : "")
            ."\n ORDER BY name";
    $database->setQuery( $query, $limitstart,$limit);
    $rows = $database->loadObjectList();

    // show the beginning of each license text
    foreach ( $rows as $key=>$row ) {
        $rows[$key]->license = substr( strip_tags($row->license), 0, 100 ) . ' (...)';
    }

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    HTML_DMLicenses::showLicenses($option, $rows, $search, $pageNav);
}

function removeLicense($cid, $option)
{
    DOCMAN_token::check() or die('Invalid Token');
	$mainframe = &JFactory::getApplication();
    $database = &JFactory::getDBO();

    if (!is_array($cid) || count($cid) < 1) {
        echo "<script> alert(" . _DML_SELECT_ITEM_DEL . "); window.history.go(-1);</script>\n";
        exit;
    }

    if (count($cid)) {
        $cids = implode(',', $cid);
        // lets see if some document is using this license
        for ($g = 0;$g < count($cid);$g++) {
            $ttt = $cid[$g];
            $ttt = ($ttt-2 * $ttt) -10;
            $query = "SELECT id FROM #__joomdoc WHERE dmlicense_id=" . (int) $ttt;
            $database->setQuery($query);
            if (!($result = $database->query())) {
                echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
            }
            if ($database->getNumRows($result) != 0) {
                $mainframe->redirect("index2.php?option=com_joomdoc&task=viewgroups", _DML_CANNOT_DEL_LICENSE);
            }
        }

        $database->setQuery("DELETE FROM #__joomdoc_licenses WHERE id IN ($cids)");

        if (!$database->query()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
    }
    $mainframe->redirect("index2.php?option=com_joomdoc&section=licenses");
}

