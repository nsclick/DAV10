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
	// configuración login
	define( '_DO_LOGIN_BANMEDICA', true );
	define( '_DO_LOGIN_BANMEDICA_REDIRECT', true );
	define( '_DO_LOGIN_JOOMLA', true );
	define( '_DO_LOGIN_FORM', 'login_debug.php' );
	define( '_DO_LOGIN_URL', 'http://banmeta4web.banmedica.cl/cdavila/verificar_pregunta_intranet.asp' );
	define( '_DO_LOGIN_OFFLINE', 'Estimado Usuario, por problemas técnicos, el servicio del Portal Dávila se encuentra suspendido.<br />Esperamos que esto se solucione pronto. Agradecemos su comprensión.' );
	define( '_DO_FOTOS_BASE', JURI::base().'images/fotos/' );
	define( '_DO_ANALYTICS', false );
	
	$session	=& JFactory::getSession();
	
	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_do'.DS.'tablas' );
	//$session->set( 'DO_oci8_link', @oci_connect("INTRANET",  "INTRA", "DAVILA_INTRA", "AL32UTF8") );
	$session->set( 'DO_oci8_link', @oci_connect("INTRANET",  "INTRA", "(DESCRIPTION=
   (FAILOVER=on)
   (LOAD_BALANCE=yes)
   (ADDRESS_LIST=
    (ADDRESS=(PROTOCOL=TCP)(HOST=davila-vip-scan1.tisal.cl)(PORT=1521))
    (ADDRESS=(PROTOCOL=TCP)(HOST=davila-vip-scan2.tisal.cl)(PORT=1521))
   )
   (CONNECT_DATA=
     (FAILOVER_MODE=(TYPE=select)(METHOD=basic))
     (SERVICE_NAME=dav_web)
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
		
		echo "<pre>"; print_r("1.\t\t Inicio Login"); echo "</pre>";
		
		// se revisa si el usuario está logueado
		if( $user->get('id') ) :
			// si está logueado, se redirecciona al index
			echo "<pre>"; print_r("2.\t\t Logueado -> Login"); echo "</pre>";
			return;
			//$mainframe->redirect( _DO_HOME );
		endif;
		
		echo "<pre>"; print_r("2.\t\t -"); echo "</pre>";
		
		// si se esta tratando de identificar, consultamos banmeta4
		$us			= JRequest::getVar('us', '', 'request');
		if( $us ) :
			echo "<pre>"; print_r("3.\t\t Formulario válido, se revisa login"); echo "</pre>";
			$session	=& JFactory::getSession();
			$db			=& JFactory::getDBO();
			$oracle		=& JTable::getInstance('oracle_debug', 'DO');
			
			if( !$us ) :
				echo "<pre>"; print_r("3.1\t\t Error, favor su nombre de usuario"); echo "</pre>";
				define( '_DO_ERROR', 'Error, favor su nombre de usuario' );
				return;
			endif;
			
			if( IPprivada() ) :
				echo "<pre>"; print_r("4.\t\t IP privada"); echo "</pre>";
				$phpsid			= JRequest::getVar('phpsid', '', 'request');
				// si el usuario no está conectado, se revisa si el login es válido
				if( $phpsid == $session->getId() && $us != '' ) :
					$rut					= (int)substr( $us, 0, -2 );
					echo "<pre>"; print_r("4.1\t\t Login OK"); echo "</pre>";
					// 1.- Obtenemos los datos de MEDISYN
					if( !$funcionario		= $oracle->funcionario( $rut ) ) :
						echo "<pre>"; print_r("4.2\t\t Error MEDISYN"); echo "</pre>";
						define( '_DO_ERROR', $oracle->_error );
					endif;
					if( !defined('_DO_ERROR') ) :
						echo "<pre>"; print_r("4.2\t\t -"); echo "</pre>";	
						/*$query		= "SELECT id FROM #__users WHERE username = '$rut'";
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
						endif;*/
						
					// session joomla
						/*jimport('joomla.user.user');

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
				
						$table->update();*/
						
						// se redirecciona al index
						echo "<pre>"; print_r("4.3\t\t Login OK -> Home"); echo "</pre>";
						//$mainframe->redirect( _DO_HOME );
					endif;
				else :
					echo "<pre>"; print_r("4.1\t\t Login Error"); echo "</pre>";
					// si el login es inválido, se redirecciona a este
					//header( "Location: login.php?action=login" );
					define( '_DO_ERROR', 'Este usuario no est&aacute; registrado, intente nuevamente' );
				endif;
			elseif( $doLogin && JRequest::checkToken() ) :		
				echo "<pre>"; print_r("4.\t\t IP pública"); echo "</pre>";				
				// 1.- Obtenemos los datos de MEDISYN
				if( !$oracle->login( $us, JRequest::getVar('valor','') ) ) :
					echo "<pre>"; print_r("4.1\t\t Oracle Login, Error"); echo "</pre>";
					define( '_DO_ERROR', $oracle->_error );
				endif;
				echo "<pre>"; print_r("4.2\t\t - "._DO_ERROR); echo "</pre>";
				if( !defined('_DO_ERROR') ) :	
					// se redirecciona al index
					echo "<pre>"; print_r("4.3\t\t Login OK -> Home"); echo "</pre>";
					//$mainframe->redirect( _DO_HOME );
				endif;
			else :
				echo "<pre>"; print_r("4.\t\t Error, sesión expiró"); echo "</pre>";
				define( '_DO_ERROR', 'Error, su sesi&oacute;n expir&oacute;' );
				return;
			endif;
		endif;
			
		/*elseif( $doLogin ) :
			echo "<pre>"; print_r("0.\t\t Error, sesión expiró"); echo "</pre>";
			define( '_DO_ERROR', 'Error, su sesi&oacute;n expir&oacute;' );
			return;
		endif;*/
	}
	
	function IPprivada()
	{
		$octetos	= explode( ".", $_SERVER['REMOTE_ADDR'] );
		switch( $octetos[0] ) :
		// IP's privadas
			case '10'	:
				return true;
				break;
			case '172'	:
				return (int)$octetos[1] >= 16 && (int)$octetos[1] <= 31;
				break;
			case '192'	:
				return $octetos[1] == '168';
				break;
		// VLan
			case '9'	:
				return $octetos[1] == '5';
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