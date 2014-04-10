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
	
	//define( '_DO_FOTOS_BASE', 'http://banmeta4web.banmedica.cl/publico/cdavila/fotos/' );
	define( '_DO_FOTOS_BASE', 'http://'.$_SERVER['HTTP_HOST'].'/images/fotos/');

	
	ob_start();
	$rut				= JRequest::getVar( 'rut', 0, 'request' );
	$ancho				= JRequest::getInt( 'ancho', 0, 'request' );
	$alto				= JRequest::getInt( 'alto', 0, 'request' );
	$esquinas			= JRequest::getInt( 'esquinas',0, 'request' );
	$foto				= null;
	
	//if( fopen( _DO_FOTOS_BASE.$rutdv.'.JPG', "r" ) ) :
	//	$foto	= _DO_FOTOS_BASE.$rut.'.JPG';
	//elseif( fopen( _DO_FOTOS_BASE.$rut.'.jpg', "r" ) ) :
	//	$foto	= _DO_FOTOS_BASE.$rut.'.jpg';
	//else 
	//	$foto = 'http://'.$_SERVER['HTTP_HOST'].'/personas-admin/img/avatars/user.jpg';
	//endif;

	$ruta = '/var/www/html/portal/images/fotos/'.$rut.'/'.$rut.'.jpg';

	if(file_exists($ruta)) {
		$foto = 'http://'.$_SERVER['HTTP_HOST'].'/images/fotos/'.$rut.'/'.$rut.'.jpg';
	} else {
		$foto = 'http://'.$_SERVER['HTTP_HOST'].'/personas-admin/img/avatars/user.jpg';
	}
	

	$imgprops		= getimagesize( $foto );
	$imgcopia		= @imagecreatefromjpeg( $foto );
	
	if( $imgprops[0] > $ancho && $ancho ) :

		$razon		= $imgprops[0] / $imgprops[1];
		$_ancho		= $ancho;
		$_alto		= round( $ancho / $razon );
		
		if( $_alto > $alto && $alto ) :
			$_alto	= $alto;
			$_ancho	= round( $alto * $razon );
		endif;
	elseif( $imgprops[1] > $alto && $alto ) :

		$razon		= $imgprops[0] / $imgprops[1];
		$_alto		= $alto;
		$_ancho		= round( $alto * $razon );
	else :
		$_ancho		= $imgprops[0];
		$_alto		= $imgprops[1];
	endif;
	
	$imgnueva		= imagecreatetruecolor( $_ancho, $_alto );
	imagecopyresampled ( $imgnueva, $imgcopia, 0, 0, 0, 0, $_ancho, $_alto, $imgprops[0], $imgprops[1] );
	
	if( $esquinas ) :
		$topleft		= imagecreatefrompng( JPATH_BASE.DS.'images'.DS.'esquina_top_left.png' );
		$bottomleft		= imagecreatefrompng( JPATH_BASE.DS.'images'.DS.'esquina_bottom_left.png' );
		$topright		= imagecreatefrompng( JPATH_BASE.DS.'images'.DS.'esquina_top_right.png' );
		$bottomright	= imagecreatefrompng( JPATH_BASE.DS.'images'.DS.'esquina_bottom_right.png' );
		
		imagecopy($imgnueva, $topleft, 0, 0, 0, 0, imagesx($topleft), imagesy($topleft));
		imagecopy($imgnueva, $bottomleft, 0, imagesy($imgnueva) - imagesy($bottomleft), 0, 0, imagesx($bottomleft), imagesy($bottomleft));
		imagecopy($imgnueva, $topright, imagesx($imgnueva) - imagesx($topright), 0, 0, 0, imagesx($topright), imagesy($topright));
		imagecopy($imgnueva, $bottomright, imagesx($imgnueva) - imagesx($bottomright), imagesy($imgnueva) - imagesy($bottomright), 0, 0, imagesx($bottomright), imagesy($bottomright));
	endif;
	
	header('Content-type: image/jpg');
	imagejpeg( $imgnueva );