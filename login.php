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
 * Include DO table folder path
 */
JTable::addIncludePath( JPATH_ADMINISTRATOR . DS . 'components'. DS . 'com_do' . DS . 'tablas' );

/**
 * Set DO_oci8_link variable in Session
 */
  $session->set( 'DO_oci8_link', @oci_connect("INTRANET",  "INTRA", "(DESCRIPTION=
    (FAILOVER=on)
    (LOAD_BALANCE=yes)
    (ADDRESS_LIST=
    (ADDRESS=(PROTOCOL=TCP)(HOST=172.31.2.237)(PORT=1521))
    (ADDRESS=(PROTOCOL=TCP)(HOST=172.31.2.237)(PORT=1521))
    )
    (CONNECT_DATA=
    (FAILOVER_MODE=(TYPE=select)(METHOD=basic))
    (SERVICE_NAME=dav_web)
    )
  )", "AL32UTF8") );
  
/**
 * Set Language
 */
  setlocale(LC_ALL, 'es_ES','es_ES.utf8','spanish');
/**
 * Login Helper
 */
  require_once ( JPATH_BASE . DS . 'includes' . DS . 'do' . DS . 'login_helper.php' );

  LoginHelper :: doLogin ( $user, $session );
  LoginHelper :: doForm ();
?>