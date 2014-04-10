<?php
/**
 * @version		$Id: componente.php 2010-07-22 sgarcia $
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
	
	defined('_JEXEC') or die('Restricted access');
	
	global $Itemid, $mainframe;
	$user	=& JFactory::getUser();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >
<head>
	<jdoc:include type="head" />
    <!--
	/**************************************/
	/*                                    */
	/*          <?php echo utf8_encode('Diseño Objetivo');?>           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
    -->
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
	<!--[if IE 6]><link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template_ie6.css" type="text/css" /><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template_ie7.css" type="text/css" /><![endif]-->
</head>
<body>
<jdoc:include type="component" />
</body>
</head>