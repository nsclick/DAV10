<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: modules.php 561 2008-01-17 11:34:40Z mjaz $
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

//include_once dirname(__FILE__) . '/modules.html.php';

$moduleid = (int) JRequest::getVar( 'moduleid', null );
$client = strval( JRequest::getVar( 'client', 'admin' ) );
JArrayHelper::toInteger(( $cid ));

switch ($task) {
    case 'publish':
    case 'unpublish':
        publishModule( array($moduleid), ($task == 'publish'), $option, $client );
        break;
    case 'orderup':
    case 'orderdown':
        orderModule( $moduleid, ($task == 'orderup' ? -1 : 1), $option, $client );
        break;
    default:
        $mainframe->redirect( 'index2.php?option=com_joomdoc' );
        break;
}


function publishModule( $cid=null, $publish=1, $option, $client='admin' ) {
    $database = &JFactory::getDBO(); $my = &JFactory::getUser();

    if (count( $cid ) < 1) {
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script> alert('Select a module to $action'); window.history.go(-1);</script>\n";
        exit;
    }

    JArrayHelper::toInteger(( $cid ));
    $cids = 'id=' . implode( ' OR id=', $cid );

    $query = "UPDATE #__modules"
    . "\n SET published = " . (int) $publish
    . "\n WHERE ( $cids )"
    . "\n AND ( checked_out = 0 OR ( checked_out = " . (int) $my->id . " ) )"
    ;
    $database->setQuery( $query );
    if (!$database->query()) {
        echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count( $cid ) == 1) {
        $row = new mosModule( $database );
        $row->checkin( $cid[0] );
    }

    mosCache::cleanCache( 'com_content' );

    $redirect = JRequest::getVar( 'redirect', 'index2.php?option='. $option .'&client='. $client );
    $mainframe->redirect( $redirect );
}

/*
 * using custom function because the core function in com_modules doesn't
 * read id from $_GET
 */
function orderModule( $uid, $inc, $option, $client='admin' ){
    $database = &JFactory::getDBO();

    $row = new mosModule( $database );
    $row->load( (int)$uid );

    if ($client == 'admin') {
        $where = "client_id = 1";
    } else {
        $where = "client_id = 0";
    }

    $row->move( $inc, "position = " . $database->Quote( $row->position ) . " AND ( $where )"  );

    mosCache::cleanCache( 'com_content' );

    $redirect = JRequest::getVar( 'redirect', 'index2.php?option='. $option .'&client='. $client );
    $mainframe->redirect( $redirect );

}