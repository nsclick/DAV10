<?php
/**
 * @version		$Id: inicio.php 2010-06-03 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*     			www.do.cl    	  	  */
	/*   		 contacto@do.cl  		  */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @package		Joomla
* @subpackage	DO
*/
class DOVistaInicio
{

	function display()
	{
		JToolBarHelper::title( JText::_( 'DO' ), 'do.png' );
		
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<div align="center" class="centermain">
		<div class="main">
			<table class="adminform">
				<tr>
					<td valign="top">
						<div id="cpanel">
							<div style="float:left;">
								<div class="icon">
									<a href="index2.php?option=com_do&amp;c=clientes" title="Clientes">
										<img src="images/categories.png" alt="Clientes" /><span>Clientes</span>
									</a>
								</div>
							</div>
							<div style="float:left;">
								<div class="icon">
									<a href="index2.php?option=com_do&amp;c=productos" title="Productos">
										<img src="images/categories.png" alt="Productos" /><span>Productos</span>
									</a>
								</div>
							</div>
							<div style="float:left;">
								<div class="icon">
									<a href="index2.php?option=com_do&amp;c=categorias" title="Categorías">
										<img src="images/categories.png" alt="Categorías" /><span>Categorías</span>
									</a>
								</div>
							</div>
							<div style="float:left;">
								<div class="icon">
									<a href="index2.php?option=com_do&amp;c=cotizaciones" title="Cotizaciones">
										<img src="images/categories.png" alt="Cotizaciones" /><span>Cotizaciones</span>
									</a>
								</div>
							</div>
                            <div style="float:left;">
								<div class="icon">
									<a href="index2.php?option=com_do&amp;c=seleccionables" title="Seleccionables">
										<img src="images/categories.png" alt="Seleccionables" /><span>Seleccionables</span>
									</a>
								</div>
							</div>
							<div style="float:left;">
								<div class="icon">
									<a href="index2.php?option=com_do&amp;c=config" title="Configuración">
										<img src="images/config.png" alt="Configuración" /><span>Configuración</span>
									</a>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
            <table width="100%" border="0">
				<tr>
					<td width="99%" align="right" valign="top">
                        <div align="center"><span class="smallgrey">DO Version 1.0.0, &copy; 2010-06 Copyright by <a href="http://www.disenobjetivo.cl" target="_blank" class="smallgrey">Dise&ntilde;o Objetivo</a> Contacte: 
						 <script language='JavaScript' type='text/javascript'>
                         <!--
                         var prefix = '&#109;a' + 'i&#108;' + '&#116;o';
                         var path = 'hr' + 'ef' + '=';
                         var addy57907 = 's&#111;p&#111;rt&#101;' + '&#64;';
                         addy57907 = addy57907 + 'd&#105;s&#101;n&#111;bj&#101;t&#105;v&#111;' + '&#46;' + 'cl';
                         document.write( addy57907 );
                         //-->\n </script><script language='JavaScript' type='text/javascript'>
                         <!--
                         document.write( '<span style=\'display: none;\'>' );
                         //-->
                         </script>Esta dirección de correo electrónico está protegida contra los robots de spam, necesita tener Javascript activado para poder verla
                         <script language='JavaScript' type='text/javascript'>
                         <!--
                         document.write( '</' );
                         document.write( 'span>' );
                         //-->
                         </script></span></div>
					</td>
				</tr>
            </table>
		</div>
		</div>
		<?php
	}
}
