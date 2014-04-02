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
	
	$config		= JFactory::getConfig();
	
	define( '_DO_HOME', 'index.php' );
		// configuración login
	define( '_DO_LOGIN_BANMEDICA', true );
	define( '_DO_LOGIN_BANMEDICA_REDIRECT', true );
	define( '_DO_LOGIN_JOOMLA', true );
	define( '_DO_LOGIN_FORM', 'login_new.php'  );
	define( '_DO_LOGIN_URL', 'http://banmeta4web.banmedica.cl/cdavila/verificar_pregunta_intranet.asp' );
	define( '_DO_LOGIN_OFFLINE', 'Estimado Usuario, por problemas técnicos, el servicio del Portal Dávila se encuentra suspendido.<br />Esperamos que esto se solucione pronto. Agradecemos su comprensión.' );
	define( '_DO_FOTOS_BASE', JURI::base().'images/fotos/' );
	define( '_DO_ANALYTICS', true );
	
	$session	=& JFactory::getSession();
	
	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_do'.DS.'tablas' );
	//$session->set( 'DO_oci8_link', @oci_connect("INTRANET",  "INTRA", "DAVILA_INTRA", "AL32UTF8") );
	
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
			if( $user->get('gid') == 31 ) :
				$option		= JRequest::getCmd('option');
				if( !$session->get('GPTI_redirect') || ( $option != 'com_gpti' && $option != 'com_user' ) ) :
					$session->set('GPTI_redirect', true);
					$mainframe->redirect( JRoute::_("index.php?option=com_gpti&Itemid=27") );
					//$mainframe->redirect( "index.php?option=com_gpti&Itemid=21" );
				/*elseif( $option != 'com_gpti' ) :
					define( '_DO_GPTI', 1 );
					require_once( JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'helpers'.DS.'helper.error.php' );
					GPTIHelperError::Raise( 'NO TIENE ACCESO AL PORTAL.DAVILA.CL' );*/
				endif;
			endif;
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
		
		jimport('joomla.user.user');
		jimport('joomla.user.helper');
		
		// si se esta tratando de identificar, consultamos banmeta4
		$us			= JRequest::getVar('us', '', 'request');
		
		if( $us ) :
//		if( $doLogin && JRequest::checkToken() ) :
			$session	=& JFactory::getSession();
			$db			=& JFactory::getDBO();
			$oracle		=& JTable::getInstance('oracle', 'DO');
//			$us			= JRequest::getVar('us', '', 'request');
			
			if( !$us ) :
				define( '_DO_ERROR', 'Error, favor su nombre de usuario' );
				return;
			endif;
			
			if( _DO_LOGIN_BANMEDICA_REDIRECT && IPprivada() ) :
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
			elseif( _DO_LOGIN_BANMEDICA && $doLogin && JRequest::checkToken() ) :

				//echo '<pre>'; print_r($oracle); exit;
				$rf = new ReflectionClass('DOOracle');
				//echo '<pre>'; print_r($rf->getFileName()); exit;

				// 1.- Obtenemos los datos de MEDISYN
				if( !$oracle->login( $us, JRequest::getVar('valor','') ) ) :
					define( '_DO_ERROR', $oracle->_error );
				endif;
				
				if( !defined('_DO_ERROR') ) :	
					// se redirecciona al index
					$mainframe->redirect( _DO_HOME );
				endif;
			endif;


			if( _DO_LOGIN_JOOMLA && defined('_DO_ERROR') ) :
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
			elseif( !defined('_DO_ERROR') ) :
				define( '_DO_ERROR', 'Error, su sesi&oacute;n expir&oacute;' );
				return;
			endif;
		endif;
		
//		elseif( $doLogin ) :
//			define( '_DO_ERROR', 'Error, su sesi&oacute;n expir&oacute;' );
//			return;
//		endif;
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
	
class Rut {
 
  const FORMATO_CARNET = 1; //Ejemplo 12.345.678-9
  const FORMATO_CARNET_SIN_PUNTOS = 2; //Ejemplo 123456789
 
   /**
019	   * remueve caracteres que actuan como separadores dentro del
020	   * RUT (espacios, puntos, guiones,etc)
021	   *
022	   * @param string $r RUT
023	   * @return string RUT sin caracteres separadores.
024	   */
  public static function formatoUser($r){
      return $r = preg_replace('/(\.)|(\-)|[ ]|[\,]|[\']/','',$r);
  }
 
  /**
030	   * Crea un arreglo asociativo separando el RUT y el dígito verificador.
031	   * @param string $r
032	   * @return string[]
033	   */
  public static function splitRut($r)
  {
    //sacar puntos, guiones, espacios, etc:
    $r = self::formatoUser($r);
 
    $dv = substr($r,strlen($r)-1);
    $r = substr($r,0,strlen($r)-1);
 
    return ($rut = array("rut"=>$r, "dv"=>$dv));
  }
  
  /**
046	   * Retorna el RUT sin dígito verificador
047	   * @param <type> $rut
048	   * @return <type>
049	   */
  public function getRut($rut)
  {
      $r = self::splitRut($rut);
      return $r["rut"];
  }
  
   /**
057	   *
058	   * @param <type> $rut
059	   * @return <type>
060	   */
  public function getDV($rut)
  {
      $r = self::splitRut($rut);
      return $r["dv"];
  }
  /**
068	   * Formatea el RUT en el formato específicado, se asume que incluye DV
069	   * @param string $rutCompleto
070	   * @param integer $formato
071	   * @return string
072	   * @example getRutCompleto(163580357,FORMATO_CARNET)
073	   */
  public static function getRutCompleto($r, $formato = 0){

      if(strlen($r)==0)
          return '';
 
      $rut = self::splitRut($r);
 
      if($formato == self::FORMATO_CARNET){
        return number_format($rut["rut"],0,'','.')."-".$rut["dv"];
      }
      else if($formato == self::FORMATO_CARNET_SIN_PUNTOS){
        return $rut["rut"]."-".$rut["dv"];
      }
      else{
          return $rut["rut"].$rut["dv"];
      }
 
  }
 /**
094	   * Verifica que el formato del rut sea tipo usuario (123456789)
095	   * y tenga un largo entre 6 y 9
096	   * @param <type> $r
097	   * @return <type>
098	   */
  public static function verificaFormatoRut($r)
  {
      //sacar puntos, guiones, espacios, etc:
      $r = self::formatoUser($r);
 
      //extraer el rut sin dv:
      $rut = substr($r,0,strlen($r)-1);
 
      return (preg_match('/^[0-9]+$/', $rut) && preg_match('/([kK0-9])$/',$r) && strlen($rut)>5 && strlen($rut)<9);
  }
  /**
111	   * Verifica que un RUT tenga un dígito verificador válido.
112	   * @param string $r
113	   * @return boolean
114	   */
  public function isRut($r)
  {
    $rut = self::splitRut($r);
    return (strcasecmp($rut["dv"], self::calculaDV($rut["rut"])) == 0 ? true : false);
  }
  /**
122	  * Calcula el digito verificador de un RUT.
123	  * Fuente: http://www.dcc.uchile.cl/~mortega/microcodigos/validarrut/php.php
124	  * @author Luis Dujovne
125	  * @param int $r  Un RUT sin DV
126	  * @return char(1) el digito verificador del RUT
127	  */
  public function calculaDV($r)
  {
    $s=1;
    for($m=0;$r!=0;$r/=10)
      $s=($s+$r%10*(9-$m++%6))%11;
    return chr($s?$s+47:75);
  }
  
  public function getRutCompleto_v2($r)
  {
		return self::getRutCompleto($r.'-'.self::calculaDV($r),1);
  }
}

?>
