<?php
/**
 * @version		$Id: config.php 2010-06-07 sgarcia $
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
 * @subpackage	Banners
 */
class DOConfig extends JTable
{
	/** @var int */
	var $id					= null;
	/** @var datetime */
	var $modificado			= null;
	/** @var int */
	var $modificado_por		= null;
	/** @var text */
	var $general			= null;
	/** @var text */
	var $noticias			= null;
	/** @var text */
	var $biblioteca			= null;
	/** @var text */
	var $licitaciones		= null;
	
	function __construct( &$_db )
	{
		parent::__construct( '#__do_config', 'id', $_db );


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
	
	function getGeneral()
	{
		$this->load( 1 );
		
		return new JParameter( $this->general, JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_acti'.DS.'parametros'.DS.'general.xml', 'general' );
	}
	function getNoticias()
	{
		$this->load( 1 );
		return new JParameter( $this->noticias, JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_acti'.DS.'parametros'.DS.'noticias.xml', 'noticias' );
	}
	function getBiblioteca()
	{
		$this->load( 1 );
		
		$row	= new stdClass;
		$row->biblioteca	= "base=".JPATH_ROOT.DS."biblioteca\n".$this->biblioteca;
		return new JParameter( $row->biblioteca, JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_acti'.DS.'parametros'.DS.'biblioteca.xml', 'biblioteca' );
	}
	function getLicitaciones()
	{
		$this->load( 1 );
		return new JParameter( $this->licitaciones, JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_acti'.DS.'parametros'.DS.'licitaciones.xml', 'licitaciones' );
	}
}
?>
