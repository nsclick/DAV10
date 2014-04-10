<?php

function dologin () {
	$user_id  = $user->get( 'id' );
	$doLogin  = JRequest::getInt( '_do_login', 0 );

	// Is user logged in?
	if( $user->get( 'id' ) ) {
		$mainframe->redirect ( _DO_HOME );
	}

	jimport('joomla.user.user');
	jimport('joomla.user.helper');

	// si se esta tratando de identificar, consultamos banmeta4
	$us			= JRequest::getVar('us', '', 'request');

	if( $us ) {
		$db			=& JFactory::getDBO(true);
		$oracle		=& JTable::getInstance('oracle', 'DO');

		if( !$us ) {
			define( '_DO_ERROR', 'Error, favor su nombre de usuario' );
			return;
		}

		if( _DO_LOGIN_BANMEDICA_REDIRECT && IPprivada() ) {
			$phpsid			= JRequest::getVar('phpsid', '', 'request');

			// si el usuario no está conectado, se revisa si el login es válido
			if( $phpsid == $session->getId() && $us != '' ) {
				$rut = (int)substr( $us, 0, -2 );

				// 1.- Obtenemos los datos de MEDISYN
				if( !$funcionario		= $oracle->funcionario( $rut ) ) {
					define( '_DO_ERROR', $oracle->_error );
				}

				if( !defined('_DO_ERROR') ) {
					$query		= "SELECT id FROM #__users WHERE username = '$rut'";
					$db->setQuery( $query );
					$userid		= $db->loadResult();
					$fecha		= date("Y-m-d H:i:s");

					if( !$userid ) {
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
					} else {
						$query		= "UPDATE #__users SET `name`='$funcionario->nombre', `email`='$funcionario->email', `lastvisitDate`='$fecha' WHERE `id`=$userid";
						$db->setQuery( $query );
						$db->query();
					}

					// session joomla
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

				} else {
					define( '_DO_ERROR', 'Este usuario no est&aacute; registrado, intente nuevamente' );
				} /* EO if !DO_ERROR */

			} elseif ( _DO_LOGIN_BANMEDICA && $doLogin && JRequest::checkToken() ) {
				$rf = new ReflectionClass('DOOracle');
				//echo '<pre>'; print_r($rf->getFileName()); exit;

				// 1.- Obtenemos los datos de MEDISYN
				if( !$oracle->login( $us, JRequest::getVar('valor','') ) ) {	
					define( '_DO_ERROR', $oracle->_error );
				}
				
				if( !defined('_DO_ERROR') ) {
					// se redirecciona al index
					$mainframe->redirect( _DO_HOME );
				}

			} /* EO if phpsid */

		} /* EO _DO_LOGIN_BANMEDICA_REDIRECT */

		if( _DO_LOGIN_JOOMLA && defined('_DO_ERROR') ) {
			// clave
			$clave		= JRequest::getVar('valor','');
	
			// Joomla does not like blank passwords
			if (empty($clave))
			{
				define( '_DO_ERROR', 'Error, debe ingresar una Contrase&ntilde;a' );
				return;
			}
	
			// Initialize variables
			$conditions = '';
	
			$query = 'SELECT `id`, `password`, `gid`'
				. ' FROM `#__users`'
				. ' WHERE username=' . $db->Quote( $us )
				;
			$db->setQuery( $query );
			$result = $db->loadObject();

			if($result)
			{
				$parts	= explode( ':', $result->password );
				$crypt	= $parts[0];
				$salt	= @$parts[1];
				$testcrypt = JUserHelper::getCryptedPassword($clave, $salt);
	
				if ($crypt == $testcrypt) {
					$user = JUser::getInstance($result->id); // Bring this in line with the rest of the system
					$user->setLastVisit();
						
					// session joomla
					jimport('joomla.user.user');

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
				} else {
					define( '_DO_ERROR', 'Error, usuario no existe o su contrase&ntilde;a es incorrecta.' );
					return;
				}
			}
			else
			{
				define( '_DO_ERROR', 'Error, usuario no existe o su contrase&ntilde;a es incorrecta.' );
				return;
			}

		} elseif ( !defined('_DO_ERROR') ) {
			define( '_DO_ERROR', 'Error, su sesi&oacute;n expir&oacute;' );
				return;
		} /* EO _DO_LOGIN_JOOMLA */

	} /* EO if us */

}

/**
 * Include LoginForm
 */
require_once ( JPATH_BASE . DS . 'includes' . DS . 'do' . DS . 'tpl' . DS . 'login_form.tpl.php' );

?>