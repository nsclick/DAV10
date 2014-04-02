<?php
/**
 * @version		$Id: mod_cupon.php 2010-07-26 sgarcia $
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

$session	=& JFactory::getSession();

//if( !$session->get('DAV_CUPON_CUMPLEANOS',0) ) :
	// Include the syndicate functions only once
	require_once (dirname(__FILE__).DS.'helper.php');
	$datos	= modCuponHelper::getDatos( $params );
	if( $datos->cumple ) :
		require(JModuleHelper::getLayoutPath('mod_cupon'));
	endif;
//endif;
