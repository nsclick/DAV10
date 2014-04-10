<?php
/**
 * @version		$Id: feriados.php 2010-07-20 sgarcia $
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
class DOFeriados extends JTable
{
	/** @var int */
	var $id					= null;
	/** @var int */
	var $published			= null;
	/** @var int */
	var $access				= null;
	/** @var string */
	var $tipo				= '';
	/** @var string */
	var $nombre				= '';
	/** @var int */
	var $creador			= null;
	/** @var datetime */
	var $fechacreacion		= null;
	
	private $_campos;

	function __construct( &$_db )
	{
		parent::__construct( '#__do_feriados', 'id', $_db );


	//	$now =& JFactory::getDate();
	//	$this->set( 'date', $now->toMySQL() );
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
		
	function getLista( &$total, $where = '', $order = '', $limitstart = 0, $limit = 0 )
	{
		$query = "SELECT COUNT(e.id) AS total"
		. " FROM $this->_tbl AS e"
		. " LEFT JOIN #__users AS uu ON uu.id = e.creador"
		. $where
		//. " GROUP BY e.id"
		. $order
		;
		$this->_db->setQuery( $query );
		$total = $this->_db->loadResult();
		
		$query = "SELECT e.*, uu.name AS uu_name"
		. " FROM $this->_tbl AS e"
		. " LEFT JOIN #__users AS uu ON uu.id = e.creador"
		. $where
		. $order
		;
		if( $limit )
		{
			$this->_db->setQuery( $query, $limitstart, $limit );
		}
		else
		{
			$this->_db->setQuery( $query );
		}
		$rows = $this->_db->loadObjectList();
		
		if( count( $rows ) )
		{
			foreach( $rows as $i => $row )
			{
				$rows[$i]->tipo_alias			= _SCMI_::getAlias( 'ejecutores_tipo', $row->tipo );
			}
		}
		
		return $rows;
	}
		
	function getListaGrupos( &$total, $where = '', $order = '' )
	{
		$seleccionables			=& JTable::getInstance('seleccionables', 'Table');
		$rows					= array();
		
		$query = "SELECT DISTINCT(e.tipo) AS tipo"
		. " FROM $this->_tbl AS e"
		. " ORDER BY e.tipo ASC"
		;
		$this->_db->setQuery( $query );
		$tipos = $this->_db->loadObjectList();
		
		if( count( $tipos ) )
		{
			foreach( $tipos as $tipo )
			{
				$tipoalias	= $seleccionables->getAlias( 'ejecutores_tipo', $tipo->tipo );
				$rows[]		= JHTML::_('select.optgroup', $tipoalias, 'id', 'nombre' );
				
				$query = "SELECT e.*, uu.name AS uu_name"
				. " FROM $this->_tbl AS e"
				. " LEFT JOIN #__users AS uu ON uu.id = e.creador"
				. $where
				. " AND e.tipo='$tipo->tipo'"
				. $order
				;
				$this->_db->setQuery( $query );
				$ejecutores = $this->_db->loadObjectList();
				if( count( $ejecutores ) ){ foreach( $ejecutores as $ejecutor ){ $rows[] = $ejecutor; } }
				
				$rows[]		= JHTML::_('select.optgroup_', 'id', 'nombre' );
			}
		}
		
		return $rows;
	}
	
	function lista( &$filtro, $seleccionable = false )
	{
		$user	=& JFactory::getUser();	
		
		$where				= array();
		$where[]			= "e.published = 1";
		$where[]			= "e.access <= " . (int)$user->get('acceso');
		
		/**
		 *	Filtros
		 */
		 
		// tipo
		if( $filtro->tipo && $filtro->tipo != 'Tipo' )
		{
			$where[]		= "e.tipo = '$filtro->tipo'";
		}
		// nombre
		if( $filtro->nombre && $filtro->nombre != 'Nombre' )
		{
			$where[]		= "e.nombre LIKE '%$filtro->nombre%'";
		}
		
		$where				= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		$orderby			= ' ORDER BY e.nombre ASC, e.fechacreacion DESC';
		
		$filtro->total = 0;
		
		if( empty( $filtro->tipo ) && $seleccionable )
		{
			return $this->getListaGrupos( $filtro->total, $where, $orderby );
		}
		else
		{
			return $this->getLista( $filtro->total, $where, $orderby, $filtro->limitstart, $filtro->limit, $seleccionable );
		}
	}
	
	function seleccionable( $tipo = '', $nombre = '', $sel = null, $attribs = null, $label='' )
	{
		$filtro			= new stdClass;
		$filtro->tipo	= $tipo;
		
		$ejecutores	= $this->lista( $filtro, true );
		
		if( $label != '' )
		{
			if( count( $ejecutores ) )
			{
				array_unshift( $ejecutores, JHTML::_('select.option', 0, $label, 'id', 'nombre' ) );	
			}
			else
			{
				$ejecutores[] = JHTML::_('select.option', 0, $label, 'id', 'nombre' );
			}
		}
		
		return JHTML::_('select.genericlist', $ejecutores, $nombre, $attribs, 'id', 'nombre', $sel);
	}
	
}
?>
