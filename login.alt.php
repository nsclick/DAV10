<?php
/**
 * @version		$Id: login.php 2010-11-26 Sebastián García Truan $
 * @package		DO
 * @subpackage	Login
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/


/**
 * NSCLICK: 02-21-2014 http://www.nsclick.cl
 * These lines are insecure such that they don't guarantee being on a secure of Joomla framework instance.
 */
define( '_JEXEC', 1 );
defined( '_JEXEC') or die('Restricted access');

define( 'JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );

/**
 * Include Joomla files
 */
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

/**
 * Joomla Debug
 */
JDEBUG ? $_PROFILER->mark ( 'afterLoad' ) : null;

/**
 * CREATE THE APPLICATION
 */
$mainframe =& JFactory::getApplication ( 'site' );

/**
 * INITIALISE THE APPLICATION
 */
$mainframe->initialise();

/**
 * Defines
 */
  require_once ( JPATH_BASE . DS . 'includes' . DS . 'do' . DS .'defines.php' );

/**
 * Variables
 */
$config   = JFactory::getConfig();
$session  =& JFactory::getSession();
$user     =& JFactory::getUser();

/**
 * Login Helper
 */
  require_once ( JPATH_BASE . DS . 'includes' . DS . 'do' . DS . 'login_helper.php' );

  LoginHelper :: doLogin ( $user, $session );
  LoginHelper :: doForm ();
?>
