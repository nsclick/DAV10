<?php
/**
 * @version		$Id: personas.php 2010-07-22 sgarcia $
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
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DOControllerPersonas extends JController  
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
		
		$app					=& JFactory::getApplication();
		
		$lists					= array();
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', '', 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'personas.php');
		ob_end_clean();
		DoVistaPersonas::display( $lists );
	}

	function buscar() 
	{ 
		ob_start();
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$oracle					=& JTable::getInstance('oracle', 'DO');
		
		$lists					= array();
		$filtro					= new stdClass;
		$filtro->nombres		= JRequest::getVar( 'filtro_nombres', 'Nombre', 'request', 'string' ) != 'Nombre' ? JRequest::getVar( 'filtro_nombres', '', 'request', 'string' ) : '';
		$filtro->apaterno		= JRequest::getVar( 'filtro_apaterno', 'Apellido Paterno', 'request', 'string' ) != 'Apellido Paterno' ? JRequest::getVar( 'filtro_apaterno', '', 'request', 'string' ) : '';
		$filtro->amaterno		= JRequest::getVar( 'filtro_amaterno', 'Apellido Materno', 'request', 'string' ) != 'Apellido Materno' ? JRequest::getVar( 'filtro_amaterno', '', 'request', 'string' ) : '';
		$filtro->unidad			= JRequest::getVar( 'filtro_unidad', 'Unidad', 'request', 'string' ) != 'Unidad' ? JRequest::getVar( 'filtro_unidad', '', 'request', 'string' ) : '';
		$filtro->cargo			= JRequest::getVar( 'filtro_cargo', 'Cargo', 'request', 'string' ) != 'Cargo' ? JRequest::getVar( 'filtro_cargo', '', 'request', 'string' ) : '';
		//$filtro->limit			= JRequest::getInt( 'limit', $mainframe->getCfg('list_limit'), 'request' );
		//$filtro->limitstart 	= JRequest::getInt( 'limitstart', 0, 'request' );

		if(!$rows = $oracle->personas($filtro)) {
			$lists['error']		= $oracle->_error;
		}
		
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', '', 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'personas.php');

		//echo '<pre>'; print_r($rows); exit;
		ob_end_clean();
		DoVistaPersonas::buscar( $rows, $lists );
	}

	function chat() 
	{ 
		global $mainframe, $Itemid;
		
		$app					=& JFactory::getApplication();
		$db						= JFactory::getDBO();
		$search					= JRequest::getVar('query','');
		
		$query = "SELECT DISTINCT u.id, u.username, u.name  " .
		"FROM #__users u, #__session s ".
		"WHERE u.id = s.userid ".
		"AND u.name LIKE ".$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ).
		"ORDER BY u.name";
		$db->setQuery($query);
		$usuarios				= $db->loadObjectList();
		
		if( count( $usuarios ) ) : ?>
		  <ul>
		  <?php foreach( $usuarios as $usuario ) : ?>
			<li><a href="javascript:void(0);" onclick="javascript:chatWith('<?php echo $usuario->username;?>')" title="<?php echo $usuario->name;?>"><?php echo $usuario->name;?></a></li>
		  <?php endforeach; ?>
		  </ul>
        <?php endif;
	}

}