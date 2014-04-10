<?php
/**
 * @version		$Id: vacaciones.php 2010-07-20 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
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

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DOVacaciones extends JTable
{
	/** @var int */
	var $id						= null;
	/** @var datetime */
	var $creado					= null;
	/** @var int */
	var $creado_por				= null;
	/** @var datetime */
	var $modificado				= null;
	/** @var int */
	var $modificado_por			= null;
	/** @var int */
	var $funcionarioid			= null;
	/** @var date */
	var $desde					= null;
	/** @var date */
	var $hasta					= null;
	/** @var tinyint */
	var $estado					= null;
	/** @var text */
	var $respuesta				= null;
	
	function __construct( &$_db )
	{
		parent::__construct( '#__do_vacaciones', 'id', $_db );
	}

	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		return true;
	}
	
	function detalles( &$row )
	{
		/*
		$config					=& JTable::getInstance('config', 'DO');
		$params					= $config->getGeneral();
		$productos				=& JTable::getInstance('productos', 'DO');
		
		switch( $row->estado ) :
			case 1 : $row->estado_alias		= 'Nueva';			break;
			case 2 : $row->estado_alias		= 'Pendiente';		break;
			case 3 : $row->estado_alias		= 'Enviada';		break;
		endswitch;
		*/
	}
	
	function cargar( $id=0 )
	{
		$query = "SELECT v.*, uc.name AS creador, um.name AS modificador, ff.name AS funcionario"
		. " FROM $this->_tbl AS v"
		. " LEFT JOIN #__users AS uc ON uc.id = v.creado_por"
		. " LEFT JOIN #__users AS um ON um.id = v.modificado_por"
		. " LEFT JOIN #__users AS ff ON ff.id = v.funcionarioid"
		//. " WHERE v.estado = 1"
		. ( $id ? " WHERE v.id = $id" : "" )
		;
		$this->_db->setQuery( $query );
		$row	= $this->_db->loadObject();
		$this->detalles( $row );
		
		return $row;
	}
	
	function getFiltro( &$filtro )
	{
		$user	=& JFactory::getUser();	
		
		$where				= array();
		//$where[]			= "c.section = 'com_do'";
		//$where[]			= "c.access <= " . (int)$user->get('acceso');
		
		/**
		 *	Filtros
		 */
		// funcionario
		if( $filtro->funcionarioid )
		{
			$where[]		= "v.funcionarioid = $filtro->funcionarioid";
		}
		// desde
		if( $filtro->desde && $filtro->desde != '0000-00-00' && $filtro->desde != 'Desde' )
		{
			$where[]		= "v.desde >= '$filtro->desde'";
		}
		// hasta
		if( $filtro->hasta && $filtro->hasta != '0000-00-00' && $filtro->hasta != 'Hasta' )
		{
			$where[]		= "v.hasta <= '$filtro->hasta'";
		}
		// estado
		if( $filtro->estado )
		{
			$where[]		= "v.estado = $filtro->estado";
		}
		
		// palabra
		if ( $filtro->palabra && $filtro->palabra != 'Palabra Clave' ) {
			$where[] =	  '('
						. ' LOWER( uc.name ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' OR LOWER( um.name ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' OR LOWER( ff.name ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' )';
		}
		
		$filtro->where				= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		$filtro->orden				= $filtro->orden ? $filtro->orden : ' ORDER BY v.creado DESC';
		$filtro->total				= 0;
		$filtro->limit				= $filtro->limit;
		$filtro->limitstart			= $filtro->limitstart;
	}
	
	function getLista( &$filtro )
	{
		// preparamos el filtro
		$this->getFiltro( $filtro );
		
		$query = "SELECT COUNT(v.id) AS total"
		. " FROM $this->_tbl AS v"
		. " LEFT JOIN #__users AS uc ON uc.id = v.creado_por"
		. " LEFT JOIN #__users AS um ON um.id = v.modificado_por"
		. " LEFT JOIN #__users AS ff ON ff.id = v.funcionarioid"
		. $filtro->where
		//. " GROUP BY v.id"
		. $filtro->orden
		;
		$this->_db->setQuery( $query );
		$filtro->total = $this->_db->loadResult();
		
		$query = "SELECT v.*, uc.name AS creador, um.name AS modificador, ff.name AS funcionario"
		. " FROM $this->_tbl AS v"
		. " LEFT JOIN #__users AS uc ON uc.id = v.creado_por"
		. " LEFT JOIN #__users AS um ON um.id = v.modificado_por"
		. " LEFT JOIN #__users AS ff ON ff.id = v.funcionarioid"
		. $filtro->where
		//. " GROUP BY v.id"
		. $filtro->orden
		;
		$this->_db->setQuery( $query, $filtro->limitstart, $filtro->limit );
		$rows = $this->_db->loadObjectList();
		
		if( count( $rows ) )
		{
			foreach( $rows as $i => $row )
			{
				$this->detalles( $rows[$i] );
			}
		}
		
		return $rows;
	}
	
	function getDiasDisponibles( $userid=0 )
	{
		$userid			= !$userid && $this->funcionarioid ? $this->funcionarioid : $userid;
		if( !$userid ) :
			return 0;
		endif;
		
		$user			= clone( JFactory::getUser() );
		$user->load( $userid );
		
		$disponible		= $user->_params->get('vacaciones', 0);
		if( !$disponible ) :
			$config					=& JTable::getInstance('config', 'DO');
			$params					= $config->getGeneral();
			$disponible				= $params->get('vacaciones', 0);
		endif;
		
		$solicitados	= $this->getDiasSolicitados();
	}
	
	function getDiasSolicitados( $id=0, $userid=0 )
	{
		if( !$id ) :
			$userid			= !$userid && $this->funcionarioid ? $this->funcionarioid : $userid;
		endif;
		if( !$id && !$userid ) :
			return 0;
		endif;
		
		$where				= array();
		if( $id ) :
			$rows			= array( $this->cargar( $id ) );
		elseif( $userid ) :
			$filtro			= new stdClass;
			$filtro->funcionarioid	= $userid;
			$filtro->desde	= date("Y").'-01-01';
			$filtro->hasta	= date("Y").'-12-31';
			$rows			= $this->getLista( $filtro );
		endif;
		
		if( !count( $rows ) ) :
			return 0;
		endif;
		
		$diaslaborales		= 0;
		foreach( $rows as $i => $row ) :
			$inicio			= strtotime( $row->desde );
			$termino		= strtotime( $row->hasta );
			for( $tiempo = $inicio; $tiempo <= $termino; $tiempo+=86400 ) :
				
			endfor;
		endforeach;
		return $diaslaborales;
	}
}
?>
