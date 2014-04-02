<?php
/**
 * @version		$Id: cotizaciones.php 2010-06-02 sgarcia $
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
class DOControllerCotizaciones extends JController
{
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct( array() );
		
		$this->registerTask( 'add',					'edit' );
		$this->registerTask( 'apply',				'save' );
		$this->registerTask( 'unpublish',			'publish' );
		
		$this->registerTask( 'editar_add',			'edit' );
		$this->registerTask( 'editar_del',			'edit' );
	}
	
	/**
	 * Muestra los movientos de materiales
	 */
	function display()
	{
		global $mainframe;

		$db					=& JFactory::getDBO();

		$context			= 'com_do.cotizaciones.list.';
		$filtro				= new stdClass;
		$filtro->order		= $mainframe->getUserStateFromRequest( $context.'filtro_order',		'filtro_order',		'',			'cmd' );
		$filtro->order_Dir	= $mainframe->getUserStateFromRequest( $context.'filtro_order_Dir',	'filtro_order_Dir',	'',			'word' );
		$filtro->palabra	= $mainframe->getUserStateFromRequest( $context.'filtro_palabra',	'filtro_palabra',	'',			'string' );
		$filtro->estado		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		0,			'int' );
		$filtro->cliente	= $mainframe->getUserStateFromRequest( $context.'filtro_cliente',	'filtro_cliente',	0,			'int' );
		$filtro->ejecutivo	= $mainframe->getUserStateFromRequest( $context.'filter_ejecutivo',	'filter_ejecutivo',	0,			'int' );
		$filtro->desde		= $mainframe->getUserStateFromRequest( $context.'filtro_desde',		'filtro_desde',		'',			'string' );
		$filtro->hasta		= $mainframe->getUserStateFromRequest( $context.'filtro_hasta',		'filtro_hasta',		'',			'string' );
		
		$filtro->limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$filtro->limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
		
		$clientes			=& JTable::getInstance('clientes', 'DO');
		$cotizaciones		=& JTable::getInstance('cotizaciones', 'DO');
		$rows				= $cotizaciones->getLista( $filtro );
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $filtro->total, $filtro->limitstart, $filtro->limit );

		// build list of categories
		$attribs			= 'class="inputbox" size="1" onchange="document.adminForm.submit();"';
		$attribs2			= 'class="inputbox" size="12" onchange="document.adminForm.submit();"';
		
		$estados				= array(
									JHTML::_('select.option', 0, '- Estado -') ,
									JHTML::_('select.option', 1, 'Nueva') ,
									JHTML::_('select.option', 2, 'Pendiente') ,
									JHTML::_('select.option', 3, 'Enviada')
								);
		
		$query = "SELECT u.id AS value, u.name AS text"
		. " FROM #__users AS u"
		. " WHERE u.gid = 19"
		. " ORDER BY u.name ASC"
		;
		$db->setQuery( $query );
		$ejecutivos			= $db->loadObjectList();
		array_unshift( $ejecutivos, JHTML::_('select.option', 0, '- Ejecutivo -') );
		
		//$lists['estado']	= JHTML::_('grid.state',  $filtro->estado );
		$lists['estado']		= JHTML::_('select.genericlist', $estados, 'filter_state', $attribs, 'value', 'text', $filtro->estado);
		$lists['cliente']		= $clientes->getSeleccionable('filtro_cliente', $filtro->cliente, $attribs);
		$lists['ejecutivo']		= JHTML::_('select.genericlist', $ejecutivos, 'filter_ejecutivo', $attribs, 'value', 'text', $filtro->ejecutivo);
		$lists['desde']			= ' Desde: '.JHTML::_('calendar', $filtro->desde, 'filtro_desde', 'filtro_desde', '%Y-%m-%d', $attribs2);
		$lists['hasta']			= ' Hasta: '.JHTML::_('calendar', $filtro->hasta, 'filtro_hasta', 'filtro_hasta', '%Y-%m-%d', $attribs2);
		
		// table ordering
		$lists['order_Dir']	= $filtro->order_Dir;
		$lists['order']		= $filtro->order;

		// search filter
		$lists['palabra']	= $filtro->palabra;

		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'cotizaciones.php');
		DoVistaCotizaciones::display( $rows, $lists, $pageNav );
	}
	
	function edit()
	{
		$db					=& JFactory::getDBO();
		$user				=& JFactory::getUser();

		if ($this->_task == 'edit') {
			$cid			= JRequest::getVar('cid', array(0), 'method', 'array');
			$cid			= array((int) $cid[0]);
		} else {
			$cid			= array( JRequest::getVar('id', 0, 'method', 'int') );
		}
		
		$seleccionables			=& JTable::getInstance('seleccionables', 'DO');
		$cotizaciones			=& JTable::getInstance('cotizaciones', 'DO');
		$categorias				=& JTable::getInstance('categorias', 'DO');
		$productos				=& JTable::getInstance('productos', 'DO');
		$clientes				=& JTable::getInstance('clientes', 'DO');
		$config					=& JTable::getInstance('config', 'DO');
		$params					= $config->getGeneral();
		$row					= $cotizaciones->cargar( $cid[0] );
		
		if( JRequest::getCmd( 'task' ) == 'editar_add' || JRequest::getCmd( 'task' ) == 'editar_del' ) :
			$cotizaciones->getProductosPost( $row );
		endif;
		
		// listas
		$lists					= array();
		
		$estados				= array(
									JHTML::_('select.option', 0, '- Seleccionar -') ,
									JHTML::_('select.option', 1, 'Nueva') ,
									JHTML::_('select.option', 2, 'Pendiente') ,
									JHTML::_('select.option', 3, 'Enviada')
								);
		
		
		//$lists['estado']	= JHTML::_('grid.state',  $filtro->estado );
		$lists['estado']		= JHTML::_('select.genericlist', $estados, 'estado', array("class"=>"inputbox"), 'value', 'text', $row->estado);
		
		$lists['categorias']		= array();
		//getSeleccionable( &$arreglo, $sentencia = array(), $padre = 0, $nivel = 0, $html = true )
		$categorias->getSeleccionableLista( $lists['categorias'], array("published"=>"c.published>=0"), 0, 0, false );
		array_unshift( $lists['categorias'], JHTML::_('select.option',0,'- Seleccionar Categoría -') );
		$lists['pro_catid']			= JHTML::_('select.genericlist', $lists['categorias'], 'pro_catid', array("class"=>"inputbox","onchange"=>"javascript:frmCategoriaCambia( this );"), 'value', 'text', 0);
		
		$lists['pro_producto']		= JHTML::_('select.genericlist', array(JHTML::_('select.option',0,'- Seleccionar Producto -')), 'pro_producto', array("class"=>"inputbox"), 'value', 'text', 0);
		
		$f							= new stdClass;
		$lists['productos']			= $productos->getLista( $f );
		$lists['script']			= $categorias->script('pro_producto', $lists);
		
		$lists['cliente']			= $clientes->getSeleccionable('cliente', 0, array("class"=>"inputbox"));
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'cotizaciones.php');
		if( $row->estado == 3 ) :
			DoVistaCotizaciones::ver( $row, $lists );
		else :
			DoVistaCotizaciones::edit( $row, $lists );
		endif;
	}
	
	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_do&c=cotizaciones' );

		$this->guardar();

		switch (JRequest::getCmd( 'task' ))
		{
			case 'apply':
				$link = 'index.php?option=com_do&c=cotizaciones&task=edit&cid[]='. JRequest::getInt('id',0,'request');
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_do&c=cotizaciones';
				break;
		}

		$this->setRedirect( $link, JText::_( 'Item Saved' ) );
	}
	
	function enviar()
	{
		global $Itemid, $mainframe;
		
		// Check for request forgeries 
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$this->setRedirect( 'index.php?option=com_do&c=cotizaciones&Itemid='.$Itemid );
		// Initialize variables
		
		$this->guardar();
		
		$ejecutivo		= clone( JFactory::getUser() );
		$cotizaciones	=& JTable::getInstance('cotizaciones', 'DO');
		$id				= JRequest::getInt('id',0,'request');
		$row			= $cotizaciones->cargar($id);
		
		$sitename 		= $mainframe->getCfg( 'sitename' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= JURI::base();
		
		$adjuntos		= array();
		
		$adjuntoindice	= -1;
		if( file_exists( $row->pdf_ruta ) ) :
			$adjuntos[++$adjuntoindice][0]	= $row->pdf_ruta;
			$adjuntos[$adjuntoindice][1]	= "cashbox_cotizacion_".$row->id.".pdf";
		endif;
		if( file_exists( $row->html_ruta ) ) :
			$adjuntos[++$adjuntoindice][0]	= $row->html_ruta;
			$adjuntos[$adjuntoindice][1]	= "cashbox_cotizacion_".$row->id.".html";
		endif;
		
		$subject 	= sprintf ( "%s: Cotización #%s", $sitename, $row->id);
		$subject 	= html_entity_decode($subject, ENT_QUOTES);
		
		$ejecutivo->load( $row->ejecutivoid );
		
		$message = sprintf ( "Estimado(a) %s,\n\nPor medio del sistema de cotizaciones de %s, adjuntamos la cotización realizada el día %s a las %s horas.\n\n--\n%s", $row->cliente, $sitename, date("d-m-Y", strtotime($row->fecha)), date("H:i", strtotime($row->fecha)), $ejecutivo->_params->get('firma') );
		$message = html_entity_decode($message, ENT_QUOTES);

		JUtility::sendMail($mailfrom, $fromname, $row->email, $subject, $message, 0, NULL, NULL, $adjuntos);
		
		switch (JRequest::getCmd( 'task' ))
		{
			default:
				$link = 'index.php?option=com_do&c=cotizaciones&Itemid='.$Itemid.'&msj='.base64_encode('La Cotización fue enviada con éxito');
				break;
		}
		
		$this->setRedirect( $link, JText::_( 'Item Saved' ) );
	}
	
	/**
	 * Save method
	 */
	function guardar()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize variables
		$db 		=& JFactory::getDBO();
		$usuario	=& JFactory::getUser();
		$post		= JRequest::get( 'post' );
		// fix up special html fields

		$row		=& JTable::getInstance('cotizaciones', 'DO');

		$row->load( $post['id'] );
		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}
			$row->modificadorid			= $usuario->get('id');
			$row->fechamodificacion		= date('Y-m-d H:i:s');
			$row->estado				= $row->estado < 3 ? (JRequest::getCmd( 'task' )=='enviar'?3:2) : $row->estado;
			$row->fechaenvio			= JRequest::getCmd( 'task' ) == 'enviar' ? date('Y-m-d H:i:s') : $row->fechaenvio;
		
			$row->detalle		= "";
			// productos
			$pids				= JRequest::getVar( 'pid', array(), 'request', 'array' );
			if( count( $pids ) )
			{
				foreach( $pids as $p => $pid )
				{
					/*
					$row->detalle		.= ( $row->detalle ? "\n" : "" ) . "$proid|".$prodcantidad[$p]."|".$prodprecio[$p]."|".str_replace("\n","<br>",$proddescripcion[$p]);
					*/
					$cantidad			= JRequest::getInt("cantidades$pid",0,"request");
					$precio				= JRequest::getInt("precios$pid",0,"request");
					$descripcion		= JRequest::getVar("descripciones$pid","","request","string");
					$row->detalle		.= ( $row->detalle ? "\n" : "" ) . "$pid|".$cantidad."|".$precio."|".str_replace("\n","<br>",$descripcion);
				}
			}
			
		if (!$row->store()) {
			return JError::raiseWarning( 500, $row->getError() );
		}
		
		$row->pdf( $row->id );
		$row->html( $row->id );

	}
	
	function copiar()
	{
		global $Itemid, $mainframe;
		
		// Check for request forgeries 
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$this->setRedirect( 'index.php?option=com_do&c=cotizaciones' );
		// Initialize variables
		
		$user		=& JFactory::getUser();
		$post		= JRequest::get( 'post' );
		$row		=& JTable::getInstance('cotizaciones', 'DO');
		
		$row->load( (int)$post['id'] );
		
		$row->id					= 0;
		$row->fecha					= date('Y-m-d H:i:s');
		$row->fechaenvio			= '0000-00-00 00:00:00';
		$row->estado				= 1;
		$row->modificadorid			= 0;
		$row->fechamodificacion		= '0000-00-00 00:00:00';
		$row->clienteid				= $post['cliente'];
			
		//guarda actualiza
		if (!$row->store()) {
			return JError::raiseWarning( 500, $row->getError() );
		}
		
		$this->setRedirect( "index.php?option=com_do&c=cotizaciones&task=edit&cid[]=".$row->id );
	}
	
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_do&c=cotizaciones' );
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_acti&c=precios' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$n		= count( $cid );
		JArrayHelper::toInteger( $cid );

		if ($n)
		{
			$query = 'DELETE FROM #__acti_precios'
			. ' WHERE id = ' . implode( ' OR id = ', $cid )
			;
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
		}

		$this->setMessage( JText::sprintf( 'Items removed', $n ) );
	}
	
}
