<?php
/**
 * @version		$Id: cumpleanos.php 2010-08-07 sgarcia $
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
class DOCumpleanos extends JTable
{
	/** @var int */
	var $funcionario			= null;
	/** @var year */
	var $anno					= null;
	/** @var string */
	var $remitente				= null;
	/** @var text */
	var $mensaje				= null;
	/** @var datetime */
	var $fecha					= null;
	
	function __construct( &$_db )
	{
		parent::__construct( '#__do_cumpleanos', 'funcionario', $_db );
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
	
	function lista( $funcionario=0, $anno=null )
	{
		$anno	= !$anno ? date("Y") : $anno;
		$query = "SELECT c.*"
		. " FROM $this->_tbl AS c"
		. " WHERE c.funcionario = $funcionario"
		. " AND c.anno = '$anno'"
		. " ORDER BY c.fecha DESC"
		;
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}

}
?>
