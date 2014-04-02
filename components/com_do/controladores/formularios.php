<?php
/**
 * @version		$Id: formularios.php 2010-07-05 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
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
defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DOControllerFormularios extends JController  
{
	/**
	 * Constructor*
	 */	 
 
	function __construct()
	{
		parent::__construct( array() );
	}

	function display() 
	{ 
		//ob_start();
		global $mainframe, $Itemid;
		
		//$return			= JRequest::getVar('return','','request','string');
		$return			= $_SERVER["HTTP_REFERER"];
		
		// revisamos el catchap
		/*
		$cc_codigo = JRequest::getVar('cc_codigo','','request','string');
		$cc_codigo_md5 = md5($cc_codigo);
		$cc_codigo_secret = JRequest::getVar('cc_codigo_secret','','request','string');
		if( $cc_codigo_md5 != $cc_codigo_secret ){
			$this->setRedirect( $return."?msg=Error en código de la imágen, favor inténtelo nuevamente" );
			return;
		}
		*/

		$user	 		= & JFactory::getUser();
		$db				=& JFactory::getDBO();

		$name 			= $user->get('name');
		$email 			= $user->get('email');
		$username 		= $user->get('username');

		$sitename 		= $mainframe->getCfg( 'sitename' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= JURI::base();
		
		$plantilla		= JRequest::getVar('plantilla','','request','string');
		//$recipiente		= base64_decode(JRequest::getVar('recipientes',base64_encode($mailfrom),'request','string'));
		$recipiente		= NULL;
		$cc 			= NULL;
		$bcc 			= NULL;
		$texto 			= "";
		$html			= 0;
		$adjuntos		= NULL;
		$sujeto 		= JRequest::getVar('sujeto','(sin asunto)','request','string');
		$msg			= "Gracias, pronto estaremos en contacto";
		
		//_plantilla( &$recipiente, &$cc, &$bcc, &$sujeto, &$acuserecivo, &$html, &$adjuntos, &$msg )
		/*
		$plantillaArchivo = $mosConfig_absolute_path . "/components/com_content/plantillas/" . $plantilla . ".php";
		if( file_exists( $plantillaArchivo ) ){
		include_once( $plantillaArchivo);
		$texto = _plantilla( $recipiente, $cc, $bcc, $sujeto, $acuserecivo, $html, $adjuntos, $msg );
		}
		*/
		
		$tmpl			= JPATH_BASE.DS.'components'.DS.'com_do'.DS.'includes'.DS.'correos'.DS.$plantilla.'.php';
		if( file_exists( $tmpl ) ) :
			include_once( $tmpl );
			$texto		= _plantilla( $recipiente, $cc, $bcc, $sujeto, $acuserecivo, $html, $adjuntos, $msg );
		endif;
		
		//sendMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null )
		if( JUtility::sendMail($mailfrom, $fromname, $recipiente, $sujeto, $texto, $html, $cc, $bcc, $adjuntos) ) :
			$this->setRedirect( $return."?msg=$msg" );
		else :
			$this->setRedirect( $return."?msg=No se logró enviar el correo" );
		endif;
		
		//ob_end_clean();
	}

}