<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: joomdoc.php 1 2009-09-01 13:31:26Z j.trumpes $
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
 
ob_start();
defined ( '_JEXEC' ) or die ( 'Restricted access' );

define ( 'JPATH_COMPONENT_HELPERS', JPATH_COMPONENT_SITE . DS . 'helpers' );
define ( 'JPATH_COMPONENT_AHELPERS', JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' );
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'docman.class.php');
require_once (JPATH_COMPONENT_HELPERS . DS . 'helper.php');
require_once (JPATH_COMPONENT_AHELPERS . DS . 'factory.php');
require_once (JPATH_COMPONENT_SITE . DS . 'controller.php');

define ( 'C_DOCMAN_DEFAULT_THEME', JPATH_COMPONENT_SITE . DS . 'views' . DS . 'themes' . DS);

$docman = &DocmanFactory::getDocman ();
DocmanFactory::setTheme ( $docman, C_DOCMAN_DEFAULT_THEME );

define ( 'C_DOCMAN_HTML', $docman->getPath ( 'classes', 'html' ) );
define ( 'C_DOCMAN_UTILS', $docman->getPath ( 'classes', 'utils' ) );
define ( 'C_DOCMAN_THEME', $docman->getPath ( 'classes', 'theme' ) );
define ( 'C_DOCMAN_COMPAT', $docman->getPath ( 'classes', 'compat' ) );
define ( 'C_DOCMAN_TOKEN', $docman->getPath ( 'classes', 'token' ) );
define ( 'C_DOCMAN_MODEL', $docman->getPath ( 'classes', 'model' ) );
define ( 'C_DOCMAN_PARAMS', $docman->getPath ( 'classes', 'params' ) );
define ( 'C_DOCMAN_MAMBOTS', $docman->getPath ( 'classes', 'mambots' ) );
define ( 'C_DOCMAN_FILE', $docman->getPath ( 'classes', 'file' ) );

require_once (C_DOCMAN_HTML);
require_once (C_DOCMAN_UTILS);
require_once (C_DOCMAN_THEME);
require_once (C_DOCMAN_COMPAT);
require_once (C_DOCMAN_TOKEN);
require_once (C_DOCMAN_MODEL);
require_once (C_DOCMAN_PARAMS);
require_once (C_DOCMAN_MAMBOTS);
require_once (C_DOCMAN_FILE);

$controller = new DocmanController ( );
ob_end_clean();
$controller->execute ( JRequest::getString ( 'task' ) );
$controller->redirect ();

?>