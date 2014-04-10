<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: admin.docman.php 608 2008-02-18 13:31:26Z mjaz $
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

/*if (!($acl -> acl_check('administration', 'edit', 'users', $my -> usertype, 'components', 'all') | $acl -> acl_check('administration', 'edit', 'users', $my -> usertype, 'components', 'com_joomdoc'))){
    $mainframe->redirect('index2.php', _DML_NOT_AUTHORIZED);
}*/

require_once $mainframe->getPath ( 'admin_html' );
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'docman.class.php');


/*$classes = array ('button', 'file', 'mambots', 'tree', 'cleardata', 'groups', 'mime', 'user', 'compat', 'html', 'model', 'utils', 'compat10', 'install', 'params', 'indexhtml', 'compat15', 'jbrowser', 'theme', 'config', 'jobject', 'token' );

foreach ( $classes as $class ) {
	require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'DOCMAN_' . $class . '.class.php');
}

require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'mime.magic.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'mime.mapping.php');*/

require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'factory.php');

global $_DOCMAN, $_DMUSER, $cid, $gid, $id, $pend, $updatedoc, $sort, $view_type, $css, $task, $option, $database, $my;

$mosConfig_live_site = JURI::root ();

$task = JRequest::getCmd ( 'task' );

$_DOCMAN = new dmMainFrame ( );
$lang = &JFactory::getLanguage();
$lang->load('com_joomdoc', JPATH_ADMINISTRATOR);
$_DOCMAN->loadLanguage ( 'backend' );

if($task=='migration'){
	require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'docman.migration.php');
	$migration = new DMMigration();
	$migration->migration();
}
$database = &JFactory::getDBO ();

$my = &JFactory::getUser ();

$_DMUSER = $_DOCMAN->getUser ();

require_once $_DOCMAN->getPath ( 'classes', 'html' );
require_once ($_DOCMAN->getPath ( 'classes', 'utils' ));
require_once ($_DOCMAN->getPath ( 'classes', 'token' ));

$cid = JRequest::getVar ( 'cid', array () );
if (! is_array ( $cid )) {
	$cid = array (0 );
}
$gid = ( int ) JRequest::getVar ( 'gid', '0' );

// retrieve some expected url (or form) arguments
$pend = JRequest::getVar ( 'pend', 'no' );
$updatedoc = JRequest::getVar ( 'updatedoc', '0' );
$sort = JRequest::getVar ( 'sort', '0' );
$view_type = JRequest::getVar ( 'view', 1 );
if (! isset ( $section )) {
	global $section;
	$section = JRequest::getVar ( 'section', '' );
}

// add stylesheet
$css = $mosConfig_live_site . '/administrator/components/com_joomdoc/includes/docman.css';
$mainframe->addCustomHeadTag ( '<link rel="stylesheet" type="text/css" media="all" href="' . $css . '" id="docman_stylesheet" />' );

// Little hack to make sure mosmsg is always displayed:
if (! isset ( $_SERVER ['HTTP_REFERER'] )) {
	$_SERVER ['HTTP_REFERER'] = $mosConfig_live_site . '/administrator/index2.php?option=com_joomdoc';
}

// execute task


if (($task == 'cpanel') || ($section == null)) {
	include_once ($_DOCMAN->getPath ( 'includes', 'docman' ));
} else {
	include_once ($_DOCMAN->getPath ( 'includes', $section ));
}


?>