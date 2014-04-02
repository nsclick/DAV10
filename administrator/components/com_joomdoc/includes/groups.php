<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: groups.php 638 2008-03-01 12:49:09Z mjaz $
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

include_once dirname(__FILE__) . '/groups.html.php';
JArrayHelper::toInteger(( $cid ));

switch ($task) {
    case "new":
        editGroup($option, 0);
        break;
    case "edit":
        editGroup($option, $cid[0]);
        break;
    case "remove":
        removeGroup($cid);
        break;
    case "apply":
    case "saveg":
    case "save":
        saveGroup($option);
        break;
    case "cancel":
        cancelGroup($option);
        break;
    case "emailgroup":
        emailGroup($gid);
        break;
    case "sendemail":
        sendEmail($gid);
        break;
    case "show" :
    default :
        showGroups($option);
}

function editGroup($option, $uid)
{
    $database = &JFactory::getDBO();

    // disable the main menu to force user to use buttons
    $_REQUEST['hidemainmenu']=1;

    $row = new mosDMGroups($database);
    $row->load($uid);

    $musers = array();
    $toAddUsers = array();
    // get selected members
    if ($row->groups_members) {
        $database->setQuery("SELECT id,name,username, block "
                . "\n FROM #__users "
                . "\n WHERE id IN (" . $row->groups_members . ")"
                . "\n ORDER BY block ASC, name ASC"
            );
        $usersInGroup = $database->loadObjectList();

        foreach($usersInGroup as $user) {
            $musers[] = JHTML::_('select.option',$user->id,
                    $user->id . "-" . $user->name . " (" . $user->username . ")"
                    . ($user->block ? ' - ['._DML_USER_BLOCKED.']':'')
                    );
        }

    }
    // get non selected members
    $query = "SELECT id,name,username, block FROM #__users ";
    if ($row->groups_members) {
        $query .= "\n WHERE id NOT IN (" . $row->groups_members . ")" ;
    }
    $query .= "\n ORDER BY block ASC, name ASC";
    $database->setQuery($query);
    $usersToAdd = $database->loadObjectList();
    foreach($usersToAdd as $user) {
        $toAddUsers[] = JHTML::_('select.option',$user->id,
                        $user->id . "-" . $user->name . " (" . $user->username . ")"
                        . ($user->block ? ' - ['._DML_USER_BLOCKED.']':'')
                        );
    }

    $usersList = JHTML::_('select.genericlist',$musers, 'users_selected[]',
        'class="inputbox" size="20" onDblClick="moveOptions(document.adminForm[\'users_selected[]\'], document.adminForm.users_not_selected)" multiple="multiple"', 'value', 'text', null);
    $toAddUsersList = JHTML::_('select.genericlist',$toAddUsers,
        'users_not_selected', 'class="inputbox" size="20" onDblClick="moveOptions(document.adminForm.users_not_selected, document.adminForm[\'users_selected[]\'])" multiple="multiple"',
        'value', 'text', null);

    HTML_DMGroups::editGroup($option, $row, $usersList, $toAddUsersList);
}

function saveGroup($option)
{
    DOCMAN_token::check() or die('Invalid Token');

    global $mainframe; 
    
    $database = &JFactory::getDBO(); $task = JRequest::getCmd('task');

    $row = new mosDMGroups($database);

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
    $members = JRequest::getVar( 'users_selected', array());
    $members_imploded = implode(',', $members);

    $database->setQuery("UPDATE #__joomdoc_groups SET groups_members='" . $members_imploded . "' WHERE groups_id=". (int) $row->groups_id);
    $database->query();

    if( $task == 'save' OR $task == 'saveg' ) {
        $url = 'index2.php?option=com_joomdoc&section=groups&task=show';
    } else { // $task = 'apply'
        $url = 'index2.php?option=com_joomdoc&section=groups&task=edit&cid[0]='.$row->groups_id;
    }

    $mainframe->redirect( $url, _DML_SAVED_CHANGES);
}

function showGroups($option)
{
    $database = &JFactory::getDBO();

    $search = trim(strtolower(JRequest::getVar( 'search', '')));
    $limit = intval(JRequest::getVar( 'limit', 10));
    $limitstart = intval(JRequest::getVar( 'limitstart', 0));
    $where = array();
    if ($search) {
        $where[] = "LOWER(groups_name) LIKE '%$search%'";
    }
    // get the total number of records
    $database->setQuery("SELECT count(*) FROM #__joomdoc_groups" . (count($where) ? "\nWHERE " . implode(' AND ', $where) : ""));
    $total = $database->loadResult();

    echo $database->getErrorMsg();

    if ($limit > $total) {
        $limitstart = 0;
    }

    $query = "SELECT *"
            ."\n FROM #__joomdoc_groups"
            .(count($where) ? "\n WHERE " . implode(' AND ', $where) : "")
            ."\n ORDER BY groups_name";
    $database->setQuery($query, $limitstart,$limit);
    $rows = $database->loadObjectList();

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    jimport('joomla.html.pagination');
    $pageNav = new JPagination($total, $limitstart, $limit);

    HTML_DMGroups::showGroups($option, $rows, $search, $pageNav);
}

function removeGroup($cid)
{
    DOCMAN_token::check() or die('Invalid Token');

    $database = &JFactory::getDBO();
    global $mainframe;
    if (!is_array($cid) || count($cid) < 1) {
        echo "<script> alert('" . _DML_SELECT_ITEM_DEL . "'); window.history.go(-1);</script>\n";
        exit;
    }
    if (count($cid)) {
        $cids = implode(',', $cid);
        // lets see if some document is owned by this group and not allow to delete it
        for ($g = 0;$g < count($cid);$g++) {
            $ttt = $cid[$g];
            $ttt = ($ttt-2 * $ttt) -10;
            $query = "SELECT id FROM #__joomdoc WHERE dmowner=" . $ttt;
            $database->setQuery($query);
            if (!($result = $database->query())) {
                echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
            }
            if ($database->getNumRows($result) != 0) {
                $mainframe->redirect("index2.php?option=com_joomdoc&section=groups", _DML_CANNOT_DEL_GROUP);
            }
        }
        $database->setQuery("DELETE FROM #__joomdoc_groups WHERE groups_id IN ($cids)");
        if (!$database->query()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
    }
    $mainframe->redirect("index2.php?option=com_joomdoc&section=groups");
}

function emailGroup($gid)
{
    $database = &JFactory::getDBO();
    $config = &JFactory::getConfig();
    $mosConfig_sitename = $config->getValue('config.sitename');
    $lists = array();

    $database->setQuery("SELECT * FROM #__joomdoc_groups WHERE groups_id=$gid");
    $email_group = $database->loadObjectList();

    $lists['leadin'] = _DML_THIS_IS . " [" . $mosConfig_sitename . "] "
     . _DML_SENT_BY . " '" . $email_group[0]->groups_name . "'";

    HTML_DMGroups::messageForm($email_group, $lists);
}

function cancelGroup($option)
{
    $database = &JFactory::getDBO();
    global $mainframe; 
    $row = new mosDMGroups($database);
    $row->bind(DOCMAN_Utils::stripslashes($_POST));
    $row->checkin();
    $mainframe->redirect("index2.php?option=$option&section=groups");
}

function sendEmail($gid)
{
    DOCMAN_token::check() or die('Invalid Token');

    // this is a generic mass mail sender to groups members.
    // From frontend you will find a email to group function specific for a document.
    $database = &JFactory::getDBO();
    $my = &JFactory::getUser();
    $mainframe = &JFactory::getApplication();
    
    $config = &JFactory::getConfig();

    $mailfrom = $config->getValue('config.mailfrom');
    $fromname = $config->getValue('config.fromname');
    //$sitename = $config->getValue('config.sitename');
    
    $this_index = 'index2.php?option=com_joomdoc&section=groups';

    $message = JRequest::getVar( "mm_message", '');
    $subject = JRequest::getVar( "mm_subject", '');
    $leadin = JRequest::getVar( "mm_leadin", '');

    if (!$message || !$subject) {
        $mainframe->redirect($this_index . '&task=emailgroup&gid=' . $gid , _DML_FILL_FORM);
    }

    $usertmp = trim(strtolower($my->usertype));
    if ($usertmp != "super administrator" && $usertmp != "superadministrator" && $usertmp != "manager") {
        $mainframe->redirect("index2.php", _DML_ONLY_ADMIN_EMAIL);
    }
    // Get the 'TO' list of addresses
    $database->setQuery("SELECT * "
         . "\n FROM #__joomdoc_groups "
         . "\n WHERE groups_id=" . (int) $gid);

    $email_group = $database->loadObjectList();
    $database->setQuery("SELECT id,name,username,email "
         . "\n FROM #__users"
         . "\n WHERE id in ( " . $email_group[0]->groups_members . ")"
         . "\n   AND email !=''");
    $listofusers = $database->loadObjectList();
    if (! count($listofusers)) {
        $mainframe->redirect($this_index , _DML_NO_TARGET_EMAIL . " " . $email_group[0]->name);
    }
    // Get 'FROM' sending email address (Use default)
    if (! $mailfrom) {
        $database->setQuery("SELECT email "
             . "\n FROM #__users "
             . "\n WHERE id=". $my->id);
        $my->email = $database->loadResult();
        echo $database->getErrorMsg();
        $mailfrom = $my->email;
    }
    // Build e-mail message format
    $message =
    ($leadin ?
        (stripslashes($leadin) . "\r\n\r\n") :'')
     . stripslashes($message);
    $subject = stripslashes($subject);
    // ------- Obsolete: ...kept for historical purposes....
    // $headers = "MIME-Version: 1.0\r\n"
    // . "From: "    .$mosConfig_sitename." <".$my->email.">\r\n"
    // . "Reply-To: ".$mosConfig_sitename." <".$my->email.">\r\n"
    // . "X-Priority: 3\r\n"
    // . "X-MSMail-Priority: Low\r\n"
    // . "X-Mailer: DOCman\r\n"
    // ;
    // mail($emailtosend->email, $subject, $message, $headers);
    // TO:              SUBJECT:  (message) Headers
    // ------------   Send email using standard mosMail function
    foreach($listofusers as $emailtosend) {
        JUTility::sendMail($mailfrom, $fromname, $emailtosend->email, $subject, $message );
    }
    $mainframe->redirect($this_index, _DML_EMAIL_SENT_TO . " " . count($listofusers) . " " . _DML_USERS);
}
