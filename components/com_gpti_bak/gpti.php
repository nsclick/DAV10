<?php
/**
 * @version		$Id: gpti.php 2011-05-20 Sebastián García Truan $
 * @package		Joomla
 * @subpackage	GPTI
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2011 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*     			www.do.cl    	  	  */
	/*   		 contacto@do.cl  		  */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'El acceso directo a este archivo no está permitido.' );
define( '_DO_GPTI', 1 );

// Iniciamos el buffer de salida
ob_start();

	global $mainframe;
	$app 			=& JFactory::getApplication();
	$doc			=& JFactory::getDocument();
	
	define('GPTI_ASSETS_URL', JURI::base().'components/com_gpti/assets/');
	define('GPTI_TEMPLATE_URL', JURI::base().'templates/gpti/');
	
	if( !is_numeric(OCI_NO_AUTO_COMMIT) ) :
		define( 'OCI_NO_AUTO_COMMIT', OCI_DEFAULT );
	endif;
	
	require_once( dirname(__FILE__).DS.'config.php' );
	//require_once( dirname(__FILE__).DS.'router.php' );
	require_once( dirname(__FILE__).DS.'helpers'.DS.'helper.error.php' );
	require_once( dirname(__FILE__).DS.'helpers'.DS.'helper.correo.php' );
	require_once( dirname(__FILE__).DS.'helpers'.DS.'helper.log.php' );
	require_once( dirname(__FILE__).DS.'helpers'.DS.'helper.acl.php' );
	require_once( dirname(__FILE__).DS.'helpers'.DS.'helper.html.php' );
	require_once( dirname(__FILE__).DS.'helpers'.DS.'helper.validacion.php' );
	require_once( dirname(__FILE__).DS.'helpers'.DS.'helper.usuarios.php' );
	require_once( dirname(__FILE__).DS.'vistas'.DS.'general.php' );
	
	// cheaqueamos el usuario
	if( !GPTIHelperACL::checkUser() ) :
		GPTIHelperError::Raise( 'Usuario, no pertenece a la aplicación' );
	else :
		//echo "ok";
		//echo "<pre>"; print_r($session->get('GPTI_user')); echo "</pre>";
	endif; 
	
	JTable::addIncludePath( JPATH_COMPONENT.DS.'tablas' );

	
	$doc->addStyleSheet( 'components/com_gpti/assets/css/jquery.treeTable.css' );
	$doc->addStyleSheet( 'components/com_gpti/assets/css/jquery.ganttView.css' );
	$doc->addScript( 'components/com_gpti/assets/js/jquery.tablednd_0_5.js' );
	$doc->addScript( 'components/com_gpti/assets/js/jquery.cookie.js' );
	//$doc->addScript( 'components/com_gpti/assets/js/jquery.bestupper.min.js' );
	$doc->addScript( 'components/com_gpti/assets/js/gpti.js' );
	
	//$doc		= JFactory::getDocument();
	//GPTIHelperLog::Log(1,'gpti.php','La aplicación comenzó pro primera vez.');
	//exit;

	// css
	$css		= "\n"
				. "\t<link rel=\"stylesheet\" href=\"".JURI::base()."components/com_gpti/assets/css/estilos.css\" type=\"text/css\" />"
				//. "\t<!--[if IE 6]><link rel=\"stylesheet\" href=\"".JURI::base()."components/com_gpti/assets/css/template_ie6.css\" type=\"text/css\" /><![endif]-->"
				//. "\t<!--[if IE 7]><link rel=\"stylesheet\" href=\"".JURI::base()."components/com_gpti/assets/css/template_ie7.css\" type=\"text/css\" /><![endif]-->"
				;
	//$doc->addCustomTag( $css );

	// controlador y tarea
	$Itemid 	= intval(JRequest::getVar('Itemid', '' ,"REQUEST"));
	$menu 		= JTable::getInstance('Menu');
	$menu->load( $Itemid );
	$params		= new JParameter($menu->params);
	
	// controlador y tarea
	$c 			= strval($params->get( 'controlador','inicio' ));
	
	$tarea	= JRequest::getCmd( 'task', $params->get( 'tarea','' ) );
	JRequest::setVar('task', $tarea);
	
	$controllerName = JRequest::getCmd( 'c', $c );

		require_once( JPATH_COMPONENT.DS.'controladores'.DS.$controllerName.'.php' );
		$controllerName = 'GPTIController'.$controllerName;
	
		// Create the controller
		$controller = new $controllerName();
	
		// Lipiamos el buffer de salida
		ob_end_clean();
	
		// Perform the Request task
		$controller->execute( JRequest::getCmd( 'task' ) );
		
		// Redirect if set by the controller
		$controller->redirect();
?>