 <?php
/**
 * @version		$Id: inicio.php 2011-05-26 Sebastián García Truan
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
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' );
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

class GPTIVistaTareas
{	
	function display( &$lists )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
		
    	<?php
	}	
	
	function informar( &$lists , $row )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
			<a name="top"></a>
            <div class="gpti_ancho">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
						<td class="gpti_centro_xl">
							<div class="gpti_titulo"><h1>Informar y Ver Tarea</h1></div>
                        </td>
                     	<td class="gpti_derecha" align="right">&nbsp;</td>
					</tr>	
				</table>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
						<td>
							<div class="gpti_izquierda gpti_float_l" align="center">
								<?php GPTIVistaGeneral::login();?>
							</div>
							<div class="gpti_centro gpti_float_l">
								<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmTarea" id="frmTarea" enctype="multipart/form-data">
								<div id="gpti_msj" class="gpti_msj"></div>
								<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
									<tr>
										<td class="gpti_tareas">
											<div class="gpti_overflow padding">
												<div class="ancho_xl float"><span>Nombre de la Tarea : </span><br /><?php echo $row->TAR_NOMBRE; ?></div>
												<div class="ancho_xl float"><span>Tipo Tarea : </span><br /><?php echo $row->TAT_NOMBRE; ?></div>
												<div><?php echo $lists['text-informada-hh']; ?></div>
												<div><span>Observaciones : </span><br /><?php echo $row->TAR_OBSERVACIONES; ?></div>
												<div><?php echo $lists['textarea-observaciones_ejecutor']; ?></div>
												<div class="ancho_xl float"><?php echo $lists['calendario-fecha-inicio-real']; ?></div>
												<div class="ancho_xl float"><?php echo $lists['calendario-fecha-termino-real']; ?></div> 
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="gpti_boton btn_ancho">
												<a href="javascript:void(0);" onclick="javascript:GPTI_Informar_Tarea(); return false;" title="Informar y Cerrar">Informar y Cerrar</a>
											</div>
											<div class="gpti_boton off">
												<a href="javascript:void(0);" onclick="javascript:document.frmTarea.reset();" title="Limpiar">Limpiar</a>
											</div>
										</td>
									</tr>
								</table>
								<input type="hidden" name="option" value="com_gpti" />
								<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
								<input type="hidden" name="c" value="tareas" />
								<input type="hidden" name="TAR_ID" value="<?php echo $row->TAR_ID; ?>" />
								<input type="hidden" name="task" value="" />
								<input type="hidden" name="TAR_FECHA_INICIO" value="<?php echo date( "Y-m-d", strtotime( $row->TAR_FECHA_INICIO )); ?>" />
								<input type="hidden" name="TAR_FECHA_TERMINO" value="<?php echo date( "Y-m-d", strtotime( $row->TAR_FECHA_TERMINO )); ?>" />
								</form>
							</div>
							<div class="gpti_derecha gpti_float_l" align="right">
								<?php  GPTIVistaGeneral::listas_derecha();?>
							</div>
						</td>
                    </tr>
               	</table>
            </div>
        </div>
    </div>
    	<?php
	}
	
	
	
	
}

?>