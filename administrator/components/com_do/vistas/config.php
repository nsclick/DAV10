<?php
/**
 * @version		$Id: clientes.php 2010-06-03 sgarcia $
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
* @subpackage	ACTI
*/
class DOVistaConfig
{
	
	function display( &$row, &$lists )
	{
		JToolBarHelper::title( JText::_( 'CashBox - Configuración' ), 'do.png' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		
		JRequest::setVar( 'hidemainmenu', 1 );
		
		?>
		<form action="index.php" method="post" name="adminForm">

		<div class="col100">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'General' ); ?></legend>

				<table class="admintable">
				<tbody>
                    <tr>
                    	<td valign="top">
							<?php echo $lists['general']; ?>
                        </td>
					</tr>
				</tbody>
				</table>
			</fieldset>
			<!--<fieldset class="adminform">
				<legend><?php echo JText::_( 'Biblioteca' ); ?></legend>

				<table class="admintable">
				<tbody>
                    <tr>
                    	<td valign="top">
							<?php echo $lists['biblioteca']; ?>
                        </td>
					</tr>
				</tbody>
				</table>
			</fieldset>
            <fieldset class="adminform">
				<legend><?php echo JText::_( 'Noticias' ); ?></legend>

				<table class="admintable">
				<tbody>
                    <tr>
                    	<td valign="top">
							<?php echo $lists['noticias']; ?>
                        </td>
					</tr>
				</tbody>
				</table>
			</fieldset>
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Licitaciones' ); ?></legend>

				<table class="admintable">
				<tbody>
                    <tr>
                    	<td valign="top">
							<?php echo $lists['licitaciones']; ?>
                        </td>
					</tr>
				</tbody>
				</table>
			</fieldset>-->
		</div>
		<div class="clr"></div>

		<input type="hidden" name="option" value="com_do" />
        <input type="hidden" name="c" value="config" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}
}
