<?php
/**
 * @version		$Id: inicio.php 2011-05-26 Max Roa Barba
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo www.do.cl
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
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class GPTIControllerTareas extends JController  
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
		ob_start();
		global $mainframe, $Itemid;
	


		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'tareas.php');
		ob_end_clean();
		GPTIVistaTareas::display( $lists, $row );
	}
	
	function informar() 
	{
		ob_start();
		
		$TAR		=& JTable::getInstance('tareas', 'GPTI');
		$TAR->get( JRequest::getInt('TAR_ID', 0) );
		
		$lists										= array();

		$lists['text-informada-hh']					= GPTIHelperHtml::Text('TAR_HH_INFORMADA','HH Informadas',null,'class="ancho_x" onkeydown="javascript:return numbersOnly(event);"'). ' <strong><small>(*)</small></strong>';
		$lists['textarea-observaciones_ejecutor']	= GPTIHelperHtml::TextArea( 'TAR_OBS_EJECUTOR', 'Observaciones dej ejecutor', NULL,NULL,"3","25" );
		$lists['calendario-fecha-inicio-real']			= GPTIHelperHtml::Calendario('TAR_FECHA_INICIO_REAL', 'Fecha Inicio Real',NULL,NULL,'%Y-%m-%d', 'class="inputclass"' ). ' <strong><small>(*)</small></strong>';
		$lists['calendario-fecha-termino-real']			= GPTIHelperHtml::Calendario('TAR_FECHA_TERMINO_REAL', 'Fecha Termino Real',NULL,NULL,'%Y-%m-%d', 'class="inputclass"' ). ' <strong><small>(*)</small></strong>';
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'tareas.php');
		ob_end_clean();
		
		GPTIVistaTareas::informar( $lists, $TAR);
	}
	
	function informar_check()
	{
		if( $errores = GPTIHelperValidacion::formTareaEjecutor() ) :
			$texto	= 'Los siguientes campos son obligatorios : <br />';
			header('Content-type: application/json');
			?>
			{
					"error": "1",
					"errormsj": "<?php echo $texto . implode( '<br />' , $errores ); ?>"
			}
			<?php
			exit(0);
		endif;		
		
		header('Content-type: application/json');
		?>
		{
				"error": "0",
                "errormsj": "nose"
		}
		<?php
		exit(0); 		
	}	
	
	function informar_submit() 
	{
		global $mainframe, $Itemid;
		
		$session			=& JFactory::getSession();
		$GPTIuser			=& $session->get( 'GPTI_user', null );

		if( !GPTIHelperACL::check('req_ingresar') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		
		if( GPTIHelperValidacion::formTareaEjecutor() ) :
			$this->informar();
			return;
		endif;	
		
		$TAR			=& JTable::getInstance( 'tareas' , 'GPTI');
		$TAR->get( JRequest::getInt('TAR_ID', 0) );
		
		$TAR->bind( $_POST );		

		$TAR->save();
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( $TAR->TAR_REQ );
		
			$REQ->REQ_FASE 		= 5; //fase 5
			$REQ->REQ_ESTADO 	= 13; //informado
			
		$REQ->save();
		
		$TAR->get( $TAR->TAR_ID );

			$datos['perfil'] 	= 5;
			$datos['proveedor']	= $REQ->REQ_PROVEEDOR; 		

			$USRS				= GPTIHelperUsuarios::getUsuarios( $datos );
			$emails				= '';
			foreach( $USRS as $i => $USR ):
				 $emails		.= ($i ? ',' : '' ).$USR['joomla']->get('email');
			endforeach;

			$datosCC['perfil'] 	= 1;
			$USRScc				= GPTIHelperUsuarios::getUsuarios( $datosCC );
			$emailsCC			= '';
			foreach( $USRScc as $i => $USRcc ):
				 $emailsCC		.= ( $i ? ',' : '' ) . $USRcc['joomla']->get('email');
			endforeach;

			$vars['rte'] 		= $GPTIuser->joomla->email ;
			$vars['dst'] 		= $emails;
			$vars['cc'] 		= $emailsCC;
			$vars['bcc'] 		= '' ;
			$vars['sujeto'] 	= '[GPTI] Tarea : '.$TAR->TAR_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['detalle'] 	= '' ;
			$vars['html'] 		= 'S' ;
			$vars['titulo'] 	= 'Tarea : '.$TAR->TAR_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['introtext'] 	= 'Se ha informado la tarea siguiente. ' ;
			$vars['fulltext'] 	= '' ;
				
			$plantilla			= 'tareas';
		
		$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
		if( file_exists( $tmpl ) ) :
			include_once( $tmpl );
			$vars['detalle'] 	= _plantilla( $TAR , $vars );
			GPTIHelperCorreo::Encolar( $vars );
		endif;	
		
		$msj	= base64_encode("La tarea ".$TAR->TAR_NOMBRE." ha sido informada con éxito. " );
		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&c=requerimientos&task=ver&REQ_ID=".$TAR->TAR_REQ."&msj=$msj" );


	}
}