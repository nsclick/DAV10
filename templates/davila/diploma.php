<?php
/**
 * @version		$Id: diploma.php 2010-07-22 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Dise�o Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Dise�o Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Dise�o Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
	defined('_JEXEC') or die('Restricted access');
	
	global $Itemid, $mainframe;
	$user	=& JFactory::getUser();

	echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >
<head>
	<jdoc:include type="head" />
    <!--
	/**************************************/
	/*                                    */
	/*          <?php echo utf8_encode('Dise�o Objetivo');?>           */
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
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
  <tr>
	<td align="center">
    <div class="contenedor_inicio" align="center">
      <table cellpadding="0" cellspacing="0" border="0" width="944">
        <tr>
          <td>
            <!-- Encabezado -->
            <div class="contenedor_encabezado">
              <table width="944" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="150" align="left" valign="top" style="padding:30px 0px 10px;"><a title="<?php echo utf8_encode('Cl�nica D�vila');?>" href="<?php echo $this->baseurl; ?>"><img border="0" title="<?php echo utf8_encode('Cl�nica D�vila');?>" alt="<?php echo utf8_encode('Cl�nica D�vila');?>" src="<?php echo $this->baseurl; ?>/images/stories/logo.jpg" /></a></td>
                  <td width="794" align="right" valign="top">
                    <table width="794" cellpadding="0" cellspacing="0" border="0" class="encabezado">
                      <tr>
                        <td align="right" class="menu_superior" width="714"><jdoc:include type="modules" name="encabezado_home" style="raw" /></td>
						<?php if( $user->get('id') ) : ?>
                        <td align="right" valign="top" width="80">
                            <a href="<?php echo $this->baseurl; ?>/index.php?option=com_user&amp;task=logout" title="Salir" onmouseover="javascript:MM_swapImage('logout_link','','<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/imagenes/bot_salir_on.gif',1);" onmouseout="javascript:MM_swapImgRestore();"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/imagenes/bot_salir_off.gif" border="0" id="logout_link" name="logout_link" alt="Salir" /></a>
                        </td>
                        <?php endif;?>
                      </tr>
                    <?php if( $this->countModules('encabezado_cotenido') ) :?>
                      <tr>
                        <td align="right" class="menu_principal"<?php if( $user->get('id') ) : ?> colspan="2"<?php endif;?>><jdoc:include type="modules" name="encabezado_cotenido" style="raw" /></td>
                      </tr>
                    <?php endif;?>
                    </table>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div class="contenedor_contenido" align="center">
      <table cellpadding="0" cellspacing="0" border="0" width="1060">
        <tr>
          <td style="" align="center"><jdoc:include type="component" /></td>
        </tr>
      </table>
    </div>
    <!-- Pie -->
    <div class="contenedor_pie" align="center">
      <table cellpadding="0" cellspacing="0" border="0" width="944">
        <tr>
          <td><jdoc:include type="modules" name="pie" style="raw" /></td>
        </tr>
      </table>
    </div>
	</td>
  </tr>
</table>
<jdoc:include type="modules" name="debug" style="raw" />
</body>
</html>