<?php
/**
* @version		$Id: foto.php 2010-08-11 sgarcia $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$mainframe =& JFactory::getApplication('site');

	/**************************************/
	/*                                    */
	/*          Diseno Objetivo           */
	/*      Fono: (56-02) 228 13 91       */
	/*     http://www.disenobjetivo.cl    */
	/*   disenobjetivo@disenobjetivo.cl   */
	/*                                    */
	/**************************************/
	
	$user				=& JFactory::getUser();
	
	
	ob_start();
	$rut				= base64_decode(JRequest::getVar( 'rut', base64_encode(''), 'request' ));
	$idcupon			= base64_decode(JRequest::getVar( 'cid', base64_encode(0), 'request' ));
	$foto				= JPATH_BASE . DS . 'images' . DS . 'cupon.jpg';
	
	/*if( !$foto ) :
		exit;
	endif;*/
		
	$imgprops		= getimagesize( $foto );
	$imgcopia		= @imagecreatefromjpeg( $foto );
	
	$_ancho			= $imgprops[0];
	$_alto			= $imgprops[1];
	
	$imgnueva		= imagecreatetruecolor( $_ancho, $_alto );		
		
	imagecopyresampled ( $imgnueva, $imgcopia, 0, 0, 0, 0, $_ancho, $_alto, $imgprops[0], $imgprops[1] );
		
	$textcolor		= imagecolorallocate($imgnueva, 255, 255, 255);
	imagestringup( $imgnueva, 4, 10, 300, utf8_decode("RUT : $rut | Tu Número: $idcupon"), $textcolor );
	
	header('Content-type: image/png');
	imagejpeg( $imgnueva , null, 100 );
