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

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DOPersonas extends JTable
{
	/** @var int */
	var $id						= null;
	/** @var string */
	var $name					= null;
	/** @var string */
	var $username				= null;
	/** @var string */
	var $email					= null;
	/** @var string */
	var $password				= null;
	/** @var string */
	var $usertype				= null;
	/** @var tinyint */
	var $block					= null;
	/** @var tinyint */
	var $sendEmail				= null;
	/** @var tinyint */
	var $gid					= null;
	/** @var datetime */
	var $registerDate			= null;
	/** @var datetime */
	var $lastvisitDate			= null;
	/** @var string */
	var $activation				= null;
	/** @var text */
	var $params					= null;
	
	function __construct( &$_db )
	{
		parent::__construct( '#__users', 'id', $_db );
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
		$query = "SELECT u.*"
		. " FROM $this->_tbl AS u"
		. ( $id ? " WHERE u.id = $id" : "" )
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
		// block 
		if( $filtro->block ) :
			$where[]		= "u.block >= 0";
		else :
			$where[]		= "u.block = 0";
		endif;
		// block 
		if( $filtro->gid ) :
			$where[]		= "u.gid = $filtro->gid";
		else :
			$where[]		= "u.gid = 18";
		endif;
		// username
		if( $filtro->username ) :
			$where[]		= 'u.username LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->username, true ).'%', false );
		endif;
		// nombrecompleto
		if( $filtro->nombrecompleto ) :
			$where[]		= 'u.name LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->nombrecompleto, true ).'%', false );
		endif;
		// email
		if( $filtro->email ) :
			$where[]		= 'u.email LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->email, true ).'%', false );
		endif;
		// nombre
		if( $filtro->nombre ) :
			$where[]		= 'u.params LIKE '.$this->_db->Quote( '%nombre='.$this->_db->getEscaped( $filtro->nombre, true ).'%', false );
		endif;
		// apellido
		if( $filtro->apellido ) :
			$where[]		= 'u.params LIKE '.$this->_db->Quote( '%apellido='.$this->_db->getEscaped( $filtro->apellido, true ).'%', false );
		endif;
		// cargo
		if( $filtro->cargo ) :
			$where[]		= 'u.params LIKE '.$this->_db->Quote( '%cargo='.$this->_db->getEscaped( $filtro->cargo, true ).'%', false );
		endif;
		// unidad
		if( $filtro->unidad ) :
			$where[]		= 'u.params LIKE '.$this->_db->Quote( '%unidad='.$this->_db->getEscaped( $filtro->unidad, true ).'%', false );
		endif;
		
		// palabra
		if ( $filtro->palabra && $filtro->palabra != 'Palabra Clave' ) {
			$where[] =	  '('
						. ' LOWER( u.name ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' OR LOWER( u.email ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' OR LOWER( u.params ) LIKE '.$this->_db->Quote( '%nombre='.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' OR LOWER( u.params ) LIKE '.$this->_db->Quote( '%apellido='.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' OR LOWER( u.params ) LIKE '.$this->_db->Quote( '%cargo='.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' OR LOWER( u.params ) LIKE '.$this->_db->Quote( '%unidad='.$this->_db->getEscaped( $filtro->palabra, true ).'%', false )
						. ' )';
		}
		
		$filtro->where				= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		$filtro->orden				= $filtro->orden ? $filtro->orden : ' ORDER BY u.name ASC';
		$filtro->total				= 0;
		$filtro->limit				= $filtro->limit;
		$filtro->limitstart			= $filtro->limitstart;
	}
	
	function getLista( &$filtro )
	{
		// preparamos el filtro
		$this->getFiltro( $filtro );
		
		$query = "SELECT COUNT(u.id) AS total"
		. " FROM $this->_tbl AS u"
		. $filtro->where
		. $filtro->orden
		;
		$this->_db->setQuery( $query );
		$filtro->total = $this->_db->loadResult();
		
		$query = "SELECT u.*"
		. " FROM $this->_tbl AS u"
		. $filtro->where
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
	
}
?>
