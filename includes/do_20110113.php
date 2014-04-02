<?php
/**
 * @version		$Id: do.php 2010-12-22 Sebastián García Truan $
 * @package		Joomla
 * @subpackage	DO
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
	
	defined('_JEXEC') or die('Restricted access');
		
	define( '_DO_HOME', 'index.php' );
	define( '_DO_LOGIN_FORM', 'login.php' );
	define( '_DO_LOGIN_URL', 'http://banmeta4web.banmedica.cl/cdavila/verificar_pregunta_intranet.asp' );
	define( '_DO_FOTOS_BASE', JURI::base().'images/fotos/' );
	
	$session	=& JFactory::getSession();
	
	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_do'.DS.'tablas' );
	//$session->set( 'DO_oci8_link', @oci_connect("INTRANET",  "INTRA", "DAVILA_INTRA", "AL32UTF8") );
	$session->set( 'DO_oci8_link', @oci_connect("INTRANET",  "INTRA", "(DESCRIPTION =
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = mercurio-vip.davila.cl)
      (PORT = 1521)
    )
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = afrodita-vip.davila.cl)
      (PORT = 1521)
    )
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = pegaso-vip.davila.cl)
      (PORT = 1521)
    )
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = venus-vip.davila.cl)
      (PORT = 1521)
    )
    (LOAD_BALANCE = yes)
    (CONNECT_DATA =
      (SERVER = DEDICATED)
      (SERVICE_NAME = dav_RCE)
      (FAILOVER_MODE =
        (TYPE = SELECT)
        (METHOD = BASIC)
        (RETRIES = 180)
        (DELAY = 5)
      )
    )
  )", "AL32UTF8") );
	
	setlocale(LC_ALL, 'es_ES','es_ES.utf8','spanish');
	
	function doindex()
	{
		global $mainframe;
		$user		=& JFactory::getUser();
		$session	=& JFactory::getSession();
		
		// se revisa si el usuario está logueado
		if( !$user->get('id') ) :
			// si no está logueado, se redirecciona al login
			$mainframe->redirect( _DO_LOGIN_FORM );
		else :
			// el usuario está conectado, puede acceder al portal
		endif;
	}
	
	function dologin()
	{
		global $mainframe;
		$user			=& JFactory::getUser();
		$doLogin		= JRequest::getInt('_do_login', 0);
		
		// se revisa si el usuario está logueado
		if( $user->get('id') ) :
			// si está logueado, se redirecciona al index
			$mainframe->redirect( _DO_HOME );
		endif;
		
		// si se esta tratando de identificar, consultamos banmeta4
		if( $doLogin && JRequest::checkToken() ) :
			$session	=& JFactory::getSession();
			$db			=& JFactory::getDBO();
			$oracle		=& JTable::getInstance('oracle', 'DO');
			$us			= JRequest::getVar('us', '', 'request');
			
			if( !$us ) :
				define( '_DO_ERROR', 'Error, favor su nombre de usuario' );
				return;
			endif;
			
			if( IPprivada() ) :
				$phpsid			= JRequest::getVar('phpsid', '', 'request');
				// si el usuario no está conectado, se revisa si el login es válido
				if( $phpsid == $session->getId() && $us != '' ) :
					$rut					= (int)substr( $us, 0, -2 );

					// 1.- Obtenemos los datos de MEDISYN
					if( !$funcionario		= $oracle->funcionario( $rut ) ) :
						define( '_DO_ERROR', $oracle->_error );
					endif;
					if( !defined('_DO_ERROR') ) :	
						$query		= "SELECT id FROM #__users WHERE username = '$rut'";
						$db->setQuery( $query );
						$userid		= $db->loadResult();
						$fecha		= date("Y-m-d H:i:s");
						if( !$userid ) :
							$query		= "INSERT INTO #__users (`name`,`username`,`email`,`usertype`,`gid`,`registerDate`,`lastvisitDate`) VALUES"
										." ('$funcionario->nombre','$rut','$funcionario->email','Registered',18,'$fecha','$fecha')"
										;
							$db->setQuery( $query );
							$db->query();
							$userid		= $db->insertid();
							
							$query		= "INSERT INTO #__core_acl_aro (`section_value`,`value`,`name`) VALUES"
										." ('users',$userid,'$funcionario->nombre')"
										;
							$db->setQuery( $query );
							$db->query();
							$aroid		= $db->insertid();
							
							$query		= "INSERT INTO #__core_acl_groups_aro_map (`group_id`,`aro_id`) VALUES"
										." (18,$aroid)"
										;
							$db->setQuery( $query );
							$db->query();
						else :
							$query		= "UPDATE #__users SET `name`='$funcionario->nombre', `email`='$funcionario->email', `lastvisitDate`='$fecha' WHERE `id`=$userid";
							$db->setQuery( $query );
							$db->query();
						endif;
						
					// session joomla
						jimport('joomla.user.user');

						$user		=& JUser::getInstance( $userid );
						
						// Get an ACL object
						$acl =& JFactory::getACL();
						$grp = $acl->getAroGroup($user->get('id'));
						
						$user->set( 'guest', 0);
						$user->set('aid', 1);
						
						// Register the needed session variables
						$session->set('user', $user);
				
						// Get the session object
						$table = & JTable::getInstance('session');
						$table->load( $session->getId() );
				
						$table->guest 		= $user->get('guest');
						$table->username 	= $user->get('username');
						$table->userid 		= intval($user->get('id'));
						$table->usertype 	= $user->get('usertype');
						$table->gid 		= intval($user->get('gid'));
				
						$table->update();
						
						// se redirecciona al index
						$mainframe->redirect( _DO_HOME );
					endif;
				else :
					// si el login es inválido, se redirecciona a este
					//header( "Location: login.php?action=login" );
					define( '_DO_ERROR', 'Este usuario no est&aacute; registrado, intente nuevamente' );
				endif;
			else :						
				// 1.- Obtenemos los datos de MEDISYN
				if( !$oracle->login( $us, JRequest::getVar('valor','') ) ) :
					define( '_DO_ERROR', $oracle->_error );
				endif;
				
				if( !defined('_DO_ERROR') ) :	
					// se redirecciona al index
					$mainframe->redirect( _DO_HOME );
				endif;
			endif;
			
		elseif( $doLogin ) :
			define( '_DO_ERROR', 'Error, su sesi&oacute;n expir&oacute;' );
			return;
		endif;
	}
	
	function IPprivada()
	{
		$octetos	= explode( ".", $_SERVER['REMOTE_ADDR'] );
		switch( $octetos[0] ) :
			case '10'	:
				return true;
				break;
			case '172'	:
				return (int)$octetos[1] >= 16 && (int)$octetos[1] <= 31;
				break;
			case '192'	:
				return $octetos[1] == '168';
				break;
			default		:
				return false;
		endswitch;
	}
	
	function fixMes( $mes = '' )
	{
		$fix	= '';
		$mes	= ucfirst( $mes );
		switch( $mes ):
			case 'January'		:
			case 'Enero'		:
				$fix			= 'Enero';
			break;
			case 'February'		:
			case 'Febrero'		:
				$fix			= 'Febrero';
			break;
			case 'March'		:
			case 'Marzo'		:
				$fix			= 'Marzo';
			break;
			case 'April'		:
			case 'Abril'		:
				$fix			= 'Abril';
			break;
			case 'May'			:
			case 'Mayo'			:
				$fix			= 'Mayo';
			break;
			case 'June'			:
			case 'Junio'		:
				$fix			= 'Junio';
			break;
			case 'July'			:
			case 'Julio'		:
				$fix			= 'Julio';
			break;
			case 'August'		:
			case 'Agosto'		:
				$fix			= 'Agosto';
			break;
			case 'September'	:
			case 'Septiembre'	:
				$fix			= 'Septiembre';
			break;
			case 'October'		:
			case 'Octubre'		:
				$fix			= 'Octubre';
			break;
			case 'November'		:
			case 'Noviembre'	:
				$fix			= 'Noviembre';
			break;
			case 'December'		:
			case 'Diciembre'	:
				$fix			= 'Diciembre';
			break;
		endswitch;
		return $fix;
	}
	
	if( IPprivada() ) :
		define( '_DO_LOGOUT_URL', 'http://banmeta4web.banmedica.cl/cdavila/davila/logout.asp?url=' );
	else :
		define( '_DO_LOGOUT_URL', '' );
	endif;
?>