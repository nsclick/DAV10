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
class DOCupones extends JTable
{
	/** @var int */
	var $id						= null;
	/** @var string */
	var $usuario				= null;
	/** @var date */
	var $nacimiento				= null;
	/** @var datetime */
	var $impresion				= null;
	
	function __construct( &$_db )
	{
		parent::__construct( '#__do_cupones', 'id', $_db );
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
	
	function revisar()
	{
		$user		=& JFactory::getUser();
		$anno		= date('Y');
		$query 		= "SELECT *"
					. " FROM $this->_tbl"
					. " WHERE usuario = '" . $user->get('username') . "'"
					. " AND impresion >= '".date('Y')."-01-01 00:00:00'"
					. " AND impresion <= '".date('Y')."-12-31 23:59:59'"
					;
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();
		
		return !count($rows);
	}
	
	function lista( &$lists=array() )
	{
		$query 		= "SELECT cp.*, uu.name AS nombre"
					. " FROM $this->_tbl AS cp"
					. " LEFT JOIN #__users AS uu ON uu.username = cp.usuario"
					. " ORDER BY cp.impresion DESC"
					;
		$this->_db->setQuery( $query );
		$rows		= $this->_db->loadObjectList();
		
		/*$y			= date("Y");
		$rows		= array();
		if( count( $r ) ) :
			foreach( $r as $index => $row ) :
				$fechatiempo	= strtotime("$y-".date("m-d",strtotime($row->nacimiento)));
				if( array_search($fechatiempo,array_keys($rows)) === false ) :
					$rows[$fechatiempo]	= $row;
				else :
					$flag = false;
					for($k=1;$flag==false;++$k) :
						if( array_search($fechatiempo+$k,array_keys($rows)) === false ) :
							$rows[$fechatiempo+$k]	= $row;
							$flag = true;
							break;
						endif;
					endfor;
				endif;
			endforeach;
		endif;
				
		krsort($rows);*/
		
		$lists['total']		= count( $rows );
		if( $lists['total'] > $lists['limit'] ) :
			$rows			= array_slice( $rows, $lists['limitstart'], $lists['limit'] );
		endif;
		
		return $rows;
	}

}
?>
