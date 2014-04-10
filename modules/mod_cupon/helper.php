<?php
/**
 * @version		$Id: helper.php 2010-07-26 sgarcia $
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
	
// No se puede acceder directamente
defined('_JEXEC') or die('Restricted access');

class modCuponHelper
{
	function getDatos( &$params )
	{
		$user 					=& JFactory::getUser();
		$datos					= new stdClass();
		
		ob_start();
			$oracle				=& JTable::getInstance('oracle', 'DO');
			$cupones			=& JTable::getInstance('cupones', 'DO');
		ob_end_clean();
		
		$row					= $oracle->funcionario( $user->get('username') );

		$ahora					= time();
		$cumpletime				= strtotime($row->fechanacimeinto);
		$cumple					= date("Y-m-d", $cumpletime);
		list($yy,$mm,$dd)		= explode("-",$cumple);
		$dosMesesAtras			= time() - ( 60*60*24*60 );
		$cumpletimeActual		= strtotime(date('Y').'-'.$mm.'-'.$dd.' 00:00:00');
		$cumpletimePasado		= strtotime((date('Y')-1).'-'.$mm.'-'.$dd.' 00:00:00');		
		
		$datos->cumple				= false;
		if( ( $cumpletimePasado >= $dosMesesAtras  || ( $cumpletimeActual >= $dosMesesAtras && $cumpletimeActual <= $ahora ) ) && $cupones->revisar() ) :
			$datos->cumple			= true;
			JHTML::script('modal.js');
			JHTML::stylesheet('modal.css');
			
		endif;
		
		$session				=& JFactory::getSession();
		$session->set('DAV_CUPON_CUMPLEANOS',1);
		
		return $datos;
	}	
}
