<?php
/**
 * @version		$Id: reconocimientos.php 2010-08-07 sgarcia $
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
class DOReconocimientos extends JTable
{
	/** @var int */
	var $id						= null;
	/** @var int */
	var $rut					= null;
	/** @var int */
	var $rutdv				= null;
	/** @var string */
	var $nombre					= null;
	/** @var string */
	var $unidad					= null;
	/** @var int */
	var $jefe					= null;
	/** @var string */
	var $jefenombre				= null;
	/** @var text */
	var $mensaje				= null;
	/** @var datetime */
	var $fecha					= null;
	
	function __construct( &$_db )
	{
		parent::__construct( '#__do_reconocimientos', 'id', $_db );
	}

	function loadExt($id) 
	{
		$this->load($id);

		$oracle	=& JTable::getInstance('oracle', 'DO');

		$res = $oracle->buscarPorRut($this->rut);
		$this->rutdv = $res->rutDv;
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
	
	function lista( $filtro=null )
	{
		$where	= array();

		$oracle	=& JTable::getInstance('oracle', 'DO');

		if( $filtro->desde ) :
			$where[]	= "r.fecha >= '$filtro->desde'";
		endif;
		if( $filtro->unidad ) :
			$where[]	= "r.unidad = '$filtro->unidad'";
		endif;
		
		$where	= count( $where ) ? " WHERE " . implode( " AND ", $where ) : "";
		
		$query = "SELECT r.*"
		. " FROM $this->_tbl AS r"
		. $where
		//. ( $filtro->desde ? " WHERE r.fecha >= '$filtro->desde'" : "" )
		. " ORDER BY r.fecha DESC"
		. ( $filtro->limit ? " LIMIT 0, $filtro->limit" : "" )
		;
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();

		if(count($rows)) {
			foreach ($rows as $key => $row) {
				$res = $oracle->buscarPorRut($row->rut);
				$rows[$key]->foto = $res->linkFoto;
			}
		}
		return $rows;
	}
	
	function getLista( $filtro=null )
	{
		$where	= array();
		
		if( $filtro->jefe ) :
			$where[]	= "r.jefe = $filtro->jefe";
		endif;
		
		$where	= count( $where ) ? " WHERE " . implode( " AND ", $where ) : "";
		
		$query = "SELECT r.*"
		. " FROM $this->_tbl AS r"
		. $where
		//. ( $filtro->desde ? " WHERE r.fecha >= '$filtro->desde'" : "" )
		. " ORDER BY r.fecha DESC"
		//. ( $filtro->limit ? " LIMIT 0, $filtro->limit" : "" )
		;
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();
		
		if( count( $rows ) ) :
			foreach( $rows as $i => $row ) :

			endforeach;
		endif;
		
		return $rows;
	}
	
	function getServicios()
	{
		$query = "SELECT DISTINCT(r.unidad) AS servicio"
		. " FROM $this->_tbl AS r"
		. " ORDER BY r.unidad ASC"
		//. ( $filtro->desde ? " WHERE r.fecha >= '$filtro->desde'" : "" )
		//. ( $filtro->limit ? " LIMIT 0, $filtro->limit" : "" )
		;
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();
		
		return $rows;
	}

}
?>
