<?php
/**
 * @version		$Id: contenido.php 2010-07-22 sgarcia $
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
	$session		=& JFactory::getSession();
	echo '<?xml version="1.0" encoding="utf-8"?>';
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
    <div align="center">	
        <div class="gpti_ancho">
            <div class="gpti_overflow">
            	<div><script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','770','height','90','title','Dávila - gpti','src','<?php echo $this->baseurl; ?>/images/banners/banner_gpti_cabecera','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','<?php echo $this->baseurl; ?>/images/banners/banner_gpti_cabecera', 'flashvars', '', 'wmode', 'transparent'); //end AC code </script></div>
                <jdoc:include type="modules" name="gpti_encabezado" style="raw" />
            </div>
            <div class="gpti_error gpti_overflow">
                <?php echo $session->get('GPTI_error');?>
            </div>
        </div>
    </div>
    <div align="center" class="gpti_gris gpti_margen">	
            <table cellpadding="0" cellspacing="0" border="0" class="gpti_ancho" >
                <tr>
                    <td>
                    	<div class="gpti_mod_pie">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="gpti_mod_pie">
                                <tr>
                                    <td valign="top" align="left" class="gpti_logo">
                                    	<a href="#" title="Inicio"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/imagenes/logo_pie.jpg" alt="Inicio" title="Inicio" border="0" /></a>
                                  	</td>
                                    <td valign="top" align="left" class="gpti_central">
                                        <div class="gpti_texto">
                                            © Copyright 2010 Clínica Dávila y Servicios Médicos S.A. - Avda. Recoleta 464, Santiago de Chile.<br />
                                            ® Todos los derechos reservados. Términos y Condiciones de Uso
                                        </div>
                                        <div class="gpti_modulo">
                                            <div class="gpti_menu">
                                                <a href="#" title="Mapa del Sitio">Mapa del Sitio</a> | 
                                                <a href="#" title="Home">Home</a> | 
                                                <a href="#" title="Contáctenos">Contáctenos</a>
                                            </div>
                                        </div>
        							</td>
        							<td valign="top" align="right" class="gpti_lateral">
        								<table border="0" cellpadding="0" cellspacing="0" class="gpti_lateral">
        									<tr>
        										<td align="left" width="50%">Fono GPTI:</td>
                                                <td align="right"><strong>730 8084</strong></td>
                                            </tr>
                                            <tr>
                                                <td align="left">&nbsp;</td>
                                                <td align="right"><strong>730 8088</strong></td>
                                            </tr>
                                            <tr>
                                                <td align="left">&nbsp;</td>
                                                <td align="right"><strong>730 8085</strong></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
</body>
</html>