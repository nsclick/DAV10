<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: categories.php 638 2008-03-01 12:49:09Z mjaz $
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

include_once dirname(__FILE__) . '/categories.html.php';
JArrayHelper::toInteger( $cid );

switch ($task) {
    case "edit" :
        editCategory($option, $cid[0]);
        break;
    case "new":
        editCategory($option, 0);
        break;
    case "cancel":
        cancelCategory();
        break;
    case "save":
    case "apply":
        saveCategory();
        break;
    case "remove":
        removeCategories($option, $cid);
        break;
    case "publish":
        publishCategories("com_joomdoc", $id, $cid, 1);
        break;
    case "unpublish":
        publishCategories("com_joomdoc", $id, $cid, 0);
        break;
    case "orderup":
        orderCategory($cid[0], -1);
        break;
    case "orderdown":
        orderCategory($cid[0], 1);
        break;
    case "accesspublic":
        accessCategory($cid[0], 0);
        break;
    case "accessregistered":
        accessCategory($cid[0], 1);
        break;
    case "accessspecial":
        accessCategory($cid[0], 2);
        break;
    case "show":
    default :
        showCategories();
}

function showCategories()
{
    global $option, $menutype, $mainframe, $mosConfig_list_limit;
    $database = &JFactory::getDBO();
    $my = &JFactory::getUser();

    $section = "com_joomdoc";

    $sectionid = $mainframe->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $mainframe->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $query = "SELECT  c.*, c.checked_out as checked_out_contact_category, c.parent_id as parent, g.name AS groupname, u.name AS editor"
     . "\n FROM #__categories AS c"
     . "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
     . "\n LEFT JOIN #__groups AS g ON g.id = c.access"
     . "\n WHERE c.section='$section'"
     . "\n AND c.published != -2"
     . "\n ORDER BY parent_id,ordering" ;

    $database->setQuery($query);

    $rows = $database->loadObjectList();

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }
    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
        $pt = $v->parent;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }
    // second pass - get an indent list of the items
    $list = DOCMAN_Utils::mosTreeRecurse(0, '', array(), $children, max(0, $levellimit-1));
    $list = is_array($list) ? $list : array();

    $total = count($list);

    jimport('joomla.html.pagination');
    $pageNav = new JPagination($total, $limitstart, $limit);

    //$levellist = JHTML::_('select.integerlist',1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit);
    // slice out elements based on limits
    $list = array_slice($list, $pageNav->limitstart, $pageNav->limit);

    $count = count($list);
    // number of Active Items
    for ($i = 0; $i < $count; $i++) {
        $query = "SELECT COUNT( d.id )"
         . "\n FROM #__joomdoc AS d"
         . "\n WHERE d.catid = " . $list[$i]->id;
        // . "\n AND d.state <> '-2'";
        $database->setQuery($query);
        $active = $database->loadResult();
        $list[$i]->documents = $active;
    }
    // get list of sections for dropdown filter
    $javascript = 'onchange="document.adminForm.submit();"';
    $lists['sectionid'] = JHTML::_('list.section','sectionid', $sectionid, $javascript);

    if(JRequest::getString('task') == 'element'){
        HTML_DMCategories::showToSelect($list, $pageNav, $lists);
    } else {
        HTML_DMCategories::show($list, $my->id, $pageNav, $lists, 'other');
    }
}

function editCategory($section = '', $uid = 0)
{
    $database = &JFactory::getDBO(); $my = &JFactory::getUser();
    $mosConfig_absolute_path = JPATH_ROOT; $mosConfig_live_site = JURI::root();

    // disable the main menu to force user to use buttons
    $_REQUEST['hidemainmenu']=1;

    $type = JRequest::getVar( 'type', '');
    $redirect = JRequest::getVar( 'section', '');;

    $row = new mosDMCategory($database);
    // load the row from the db table
    $row->load($uid);
    // fail if checked out not by 'me'
    if ($row->checked_out && $row->checked_out <> $my->id) {
        $mainframe->redirect('index2.php?option=com_joomdoc&task=categories', 'The category ' . $row->title . ' is currently being edited by another administrator');
    }

    if ($uid) {
        // existing record
        $row->checkout($my->id);
        // code for Link Menu
    } else {
        // new record
        $row->section = $section;
        $row->published = 1;
    }
    // make order list
    $order = array();
    $database->setQuery("SELECT COUNT(*) FROM #__categories WHERE section='$row->section'");
    $max = intval($database->loadResult()) + 1;

    for ($i = 1; $i < $max; $i++) {
        $order[] = JHTML::_('select.option',$i);
    }
    // build the html select list for ordering
    $query = "SELECT ordering AS value, title AS text"
     . "\n FROM #__categories"
     . "\n WHERE section = '$row->section'"
     . "\n ORDER BY ordering" ;
    $lists['ordering'] = JHTML::_('list.specificordering',$row, $uid, $query);
    // build the select list for the image positions
    $active = ($row->image_position ? $row->image_position : 'left');
    $lists['image_position'] = JHTML::_('list.positions','image_position', $active, null, 0, 0);
    // Imagelist
    $lists['image'] = dmHTML::imageList('image', $row->image);
    // build the html select list for the group access
    $lists['access'] = JHTML::_('list.accesslevel',$row);
    // build the html radio buttons for published
    $lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $row->published);
    // build the html select list for paraent item
    $options = array();
    $options[] = JHTML::_('select.option','0', _DML_TOP);
    $lists['parent'] = dmHTML::categoryParentList($row->id, "", $options);

    HTML_DMCategories::edit($row, $section, $lists, $redirect);
}

function saveCategory()
{
    DOCMAN_token::check() or die('Invalid Token');

    $database = &JFactory::getDBO(); $task = JRequest::getCmd('task');
    global $mainframe;

    $row = new mosDMCategory($database);

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
    $row->reorder("section='com_joomdoc' AND parent_id=". (int) $row->parent_id);

    /* http://forum.joomlatools.org/viewtopic.php?f=14&t=316
    $oldtitle =  strip_tags( JRequest::getVar( 'oldtitle', null) );
    if ($oldtitle) {
        if ($oldtitle != $row->title) {
            $database->setQuery("UPDATE #__categories " . "\n SET name='$row->title' " . "\n WHERE name='$oldtitle' " . "\n    AND section='com_joomdoc'");
            $database->query();
        }
    }
    */

    if( $task == 'save' ) {
        $url = 'index2.php?option=com_joomdoc&section=categories';
    } else { // $task = 'apply'
        $url = 'index2.php?option=com_joomdoc&section=categories&task=edit&cid[0]='.$row->id;
    }

    $mainframe->redirect( $url, _DML_SAVED_CHANGES);

}

/**
* Deletes one or more categories from the categories table
*
* @param string $ The name of the category section
* @param array $ An array of unique category id numbers
*/
function removeCategories($section, $cid)
{
    DOCMAN_token::check() or die('Invalid Token');

    $database = &JFactory::getDBO();
    
    global $mainframe;

    if (count($cid) < 1) {
        echo "<script> alert('"._DML_SELECTCATTODELETE."'); window.history.go(-1);</script>\n";
        exit;
    }

    $cids = implode(',', $cid);
    // Check to see if the category holds child documents and/or subcategories
    $query = "SELECT c.id, c.name, c.parent_id, COUNT(s.catid) AS numcat, COUNT(u.id) as numkids"
     . "\n FROM #__categories AS c"
     . "\n LEFT JOIN #__joomdoc     AS s ON s.catid=c.id"
     . "\n LEFT JOIN #__categories AS u ON u.parent_id =c.id"
     . "\n WHERE c.id IN ($cids)"
     . "\n GROUP BY c.id" ;
    $database->setQuery($query);

    if (!($rows = $database->loadObjectList())) {
        echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    }

    $err = array();
    $cid = array();
    
    foreach ($rows as $row) {
        if ($row->numcat == 0 && $row->numkids == 0) {
            $cid[] = $row->id;
        } else {
            $err[] = $row->name;
        }
    }
    
    if (count($cid)) {
        $cids = implode(',', $cid);
        $database->setQuery("DELETE FROM #__categories WHERE id IN ($cids)");
        if (!$database->query()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
    }

    if (count($err)) {    	
        if (count($err) > 1) {
            $cids = implode(', ', $err);
            $msg = _DML_CATS.": $cids -";
        } else {
            $msg = _DML_CAT." " . $err[0] ;
        }
        $msg .= ' '._DML_CATS_CANT_BE_REMOVED;
        
        $mainframe->redirect('index2.php?option=com_joomdoc&section=categories' , $msg);
    }

    $msg = (count($err) > 1 ? _DML_CATS : _DML_CAT . " ") . _DML_DELETED;
    $mainframe->redirect('index2.php?option=com_joomdoc&section=categories', $msg);
}

/**
* Publishes or Unpublishes one or more categories
*
* @param string $ The name of the category section
* @param integer $ A unique category id (passed from an edit form)
* @param array $ An array of unique category id numbers
* @param integer $ 0 if unpublishing, 1 if publishing
* @param string $ The name of the current user
*/

function publishCategories($section, $categoryid = null, $cid = null, $publish = 1)
{
    if(!DOCMAN_token::check()){
    	die('Invalid Token');
    }

    $database = &JFactory::getDBO(); $my = &JFactory::getUser();
    $mainframe = &JFactory::getApplication();

    if (!is_array($cid)) {
        $cid = array();
    }
    if ($categoryid) {
        $cid[] = $categoryid;
    }

    if (count($cid) < 1) {
        $action = $publish ? _PUBLISH : _DML_UNPUBLISH;
        echo "<script> alert('" . _DML_SELECTCATTO . " $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $cids = implode(',', $cid);

    $query = "UPDATE #__categories SET published=$publish"
     . "\n WHERE id IN ($cids) AND (checked_out=0 OR (checked_out=$my->id))" ;
    $database->setQuery($query);
    if (!$database->query()) {
        echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($cid) == 1) {
    	require_once (JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table'.DS.'category.php');
        $row = new JTableCategory($database);
        $row->checkin($cid[0]);
    }

    $mainframe->redirect('index2.php?option=com_joomdoc&section=categories');
}

/**
* Cancels an edit operation
*
* @param string $ The name of the category section
* @param integer $ A unique category id
*/
function cancelCategory()
{
    $database = &JFactory::getDBO();
	$mainframe = &JFactory::getApplication();
    $row = new mosDMCategory($database);
    $row->bind(DOCMAN_Utils::stripslashes($_POST));
    $row->checkin();
    $mainframe->redirect('index2.php?option=com_joomdoc&section=categories');
}

/**
* Moves the order of a record
*
* @param integer $ The increment to reorder by
*/
function orderCategory($uid, $inc)
{
    $database = &JFactory::getDBO();
	$mainframe = &JFactory::getApplication();
    $row = new mosDMCategory($database);
    $row->load($uid);
    $row->move($inc, "section='$row->section'");
    $mainframe->redirect('index2.php?option=com_joomdoc&section=categories');
}

/**
* changes the access level of a record
*
* @param integer $ The increment to reorder by
*/
function accessCategory($uid, $access)
{
    if(!DOCMAN_token::check()){
    	die('Invalid Token');
    }
	$mainframe = &JFactory::getApplication();
    $database = &JFactory::getDBO();

    $row = new mosDMCategory($database);
    $row->load($uid);
    $row->access = $access;

    if (!$row->check()) {
        return $row->getError();
    }
    if (!$row->store()) {
        return $row->getError();
    }

    $mainframe->redirect('index2.php?option=com_joomdoc&section=categories');
}
