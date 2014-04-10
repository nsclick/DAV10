<?php
/**
* @version		$Id: cupon.php 2011-03-14 Sebastián García Truan $
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

/**
 * INITIALISE THE APPLICATION
 *
 * NOTE :
 */
// set the language
$mainframe->initialise();

/**
 * DO
 *
 * NOTE :
 */
require_once ( JPATH_BASE .DS.'includes'.DS.'do.php' );
//JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_do'.DS.'tablas' );
	/**************************************/
	/*                                    */
	/*          Diseno Objetivo           */
	/*      Fono: (56-02) 228 13 91       */
	/*     http://www.disenobjetivo.cl    */
	/*   disenobjetivo@disenobjetivo.cl   */
	/*                                    */
	/**************************************/
	
	$user 					=& JFactory::getUser();
	$db 					=& JFactory::getDBO();
	date_default_timezone_set('America/Santiago');
	ob_start();
		$oracle				=& JTable::getInstance('oracle', 'DO');
		$cupon				=& JTable::getInstance('cupones', 'DO');
	ob_end_clean();
	
	$row					= $oracle->funcionario( $user->get('username') );
	
	$ahora					= time();
	$cumpletime				= strtotime($row->fechanacimeinto);
	$cumple					= date("Y-m-d", $cumpletime);
	list($yy,$mm,$dd)		= explode("-",$cumple);
	$dosMesesAtras			= time() - ( 60*60*24*60 );
	$cumpletimeActual		= strtotime(date('Y').'-'.$mm.'-'.$dd.' 00:00:00');
	$cumpletimePasado		= strtotime((date('Y')-1).'-'.$mm.'-'.$dd.' 00:00:00');		
	
	$generarcupon				= false;
	if( ( $cumpletimePasado >= $dosMesesAtras  || ( $cumpletimeActual >= $dosMesesAtras && $cumpletimeActual <= $ahora ) ) && $cupon->revisar() ) :
		$query	= "SELECT MAX(id) AS id FROM #__do_cupones";
		$db->setQuery($query);
		$check					= JRequest::getInt('check',0,'request');
		$cupon->id				= (int)$db->loadResult()+1;
		$cupon->usuario			= $user->get('username');
		$cupon->nacimiento		= $cumple;
		$cupon->impresion		= date("Y-m-d H:i:s");
		//if( $check ) :
				$cupon->id			= 0;
				$cupon->store();
		//endif;
		$generarcupon			= true;
	endif;
	
	$session				=& JFactory::getSession();
	$session->set('DAV_CUPON_CUMPLEANOS',1);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Clínica Dávila - Cupón</title>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript" src="<?php echo JURI::base();?>media/system/js/do.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>media/system/js/mootools.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>media/system/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
	jQuery.noConflict();
	function cuponCerrar()
	{
		var t = setTimeout("window.parent.SqueezeBox.close();",2000);
	}
</script>
<style type="text/css">
	body					{ color:#999; font-size:12px; font-family:Arial, Helvetica, sans-serif; margin:20px 0px 0px 0px; }
	img						{ border:none; }
	div.cupon				{ font-size:20px; color:#F00; text-align:left; padding:2px 8px 0px 10px; font-weight:bold; }
	div.boton				{ background-color:#F00; width:500px; height:30px; margin:10px 0px 10px 10px; padding:0px 0px 0px 0px; }
	div.boton a,
	div.boton a:hover		{ display:block; width:100%; height:100%; color:#FFF; font-size:14px; line-height:30px; text-align:center; }
	div.boton a:hover		{ background-color:#F60; }
	div.texto				{ width:490px; padding:2px 8px 0px 10px; color:#999; }
</style>
</head>
<body>
	<?php

	if( $generarcupon ) :
		?>
        	<div class="cupon" align="center">
            	<img src="<?php echo JURI::base();?>images/cupon.jpg" alt="Cupón Descuento nro. <?php echo $cupon->id;?>" title="Cupón nro. <?php echo $cupon->id;?>" />
            </div>
            <div class="boton"><a href="javascript:void(0);" title="Cerrar este Aviso" onclick="javascript:cuponCerrar(); return false;">Cerrar este Aviso</a></div>
        <?php
	else :
		?>
        	<div class="cupon">CUPÓN NO DISPONIBLE - <?php echo $cumple;?></div>
        <?php
	endif;
?>
</body>
</html>