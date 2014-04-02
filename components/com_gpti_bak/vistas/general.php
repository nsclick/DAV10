 <?php
/**
 * @version		$Id: requerimientos.php 2011-05-26 Sebastián García Truan
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

class GPTIVistaGeneral
{	

	function login()
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		?>
                            <div class="gpti_login_int">
                                <div class="gpti_out_int"><a href="index.php?option=com_user&amp;task=logout" title="Salir">Salir</a></div>
                                <div class="gpti_perfil"><?php echo $GPTIuser->USR_GERENCIA ? $GPTIuser->GER_NOMBRE : $GPTIuser->PRO_NOMBRE;?></div>
                                <div class="gpti_nombre"><?php echo $GPTIuser->joomla->name;?></div>
                                <div class="gpti_imagen_perfil"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/perfil.jpg" width="84" height="113" alt="Perfil <?php echo $GPTIuser->PFL_NOMBRE;?>" /></div>
                            </div>
    	<?php
	}

	function login_inicio()
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		?>
		
		       	<div class="gpti_login">
                    <div class="gpti_out"><a href="index.php?option=com_user&amp;task=logout" title="Salir">Salir</a></div>
                    <div class="gpti_perfil"><?php echo $GPTIuser->USR_GERENCIA ? $GPTIuser->GER_NOMBRE : $GPTIuser->PRO_NOMBRE;?></div>
                    <?php /*<div class="gpti_perfil">Perfil <?php echo $GPTIuser->PFL_NOMBRE;?></div>*/ ?>
                    <div class="gpti_nombre"><?php echo $GPTIuser->joomla->name;?></div>
                </div>
    	<?php
	}
	
	function listas_derecha()
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		
		$lists							= array();
		
		$lists['perfil-Admin']				= $GPTIuser->USR_PERFIL == 1;
		$lists['perfil-Gerente']			= $GPTIuser->USR_PERFIL == 2;
		$lists['perfil-Gerencia']			= $GPTIuser->USR_PERFIL == 3;
		$lists['perfil-Usuario']			= $GPTIuser->USR_PERFIL == 4;
		$lists['perfil-Gerente-Proveedor']	= $GPTIuser->USR_PERFIL == 5;
		$lists['perfil-Ejecutor']			= $GPTIuser->USR_PERFIL == 6;
						
		if( $lists['perfil-Admin'] ) :
			$lists['lista-1']				= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>4,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-1-label']			= 'Requerimientos Ingresados';
			
			$lists['lista-2']				= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>7,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-2-label']			= 'Requerimientos HH Estimadas';
			
			$lists['lista-3']				= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>9,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-3-label']			= 'Requerimientos Desarrollo';
			
			$lists['lista-4']				= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>10,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-4-label']			= 'Requerimientos Prueba';
			
			$lists['lista-5']				= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>13,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-5-label']			= 'Requerimientos Informados';
		endif;
		if( $lists['perfil-Gerente'] ) :
			$lists['lista-ingresados']		= GPTIHelperHtml::ListaUltimos( array('REQ_FASE_DESDE'=>1,'REQ_FASE_HASTA'=>3) );
			$lists['lista-ejecucion']		= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>13) );
			$lists['lista-estimacion']		= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>9) );
		endif;
		if( $lists['perfil-Gerencia'] ) :
			$lists['lista-1']				= GPTIHelperHtml::ListaUltimos( array('REQ_GERENCIA'=>$GPTIuser->USR_GERENCIA,'REQ_ESTADO'=>2,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-1-label']			= 'Requerimientos Ingresados';
			
			$lists['lista-2']				= GPTIHelperHtml::ListaUltimos( array('REQ_GERENCIA'=>$GPTIuser->USR_GERENCIA,'REQ_ESTADO'=>9,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-2-label']			= 'Requerimientos Desarrollo';
			
			$lists['lista-3']				= GPTIHelperHtml::ListaUltimos( array('REQ_GERENCIA'=>$GPTIuser->USR_GERENCIA,'REQ_ESTADO'=>10,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-3-label']			= 'Requerimientos Prueba';
		endif;
		if( $lists['perfil-Usuario'] ) :
			$lists['lista-1']		= GPTIHelperHtml::ListaUltimos( array('REQ_USUARIO'=>$GPTIuser->USR_ID,'REQ_ESTADO'=>2,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-1-label']	= 'Requerimientos Ingresados';
			
			$lists['lista-2']		= GPTIHelperHtml::ListaUltimos( array('REQ_USUARIO'=>$GPTIuser->USR_ID,'REQ_ESTADO'=>3,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-2-label']	= 'Requerimientos Rechazados';
			
			$lists['lista-3']			= GPTIHelperHtml::ListaUltimos( array('REQ_USUARIO'=>$GPTIuser->USR_ID,'REQ_ESTADO'=>9,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-3-label']		= 'Requerimientos Desarrollo';
			
			$lists['lista-4']			= GPTIHelperHtml::ListaUltimos( array('REQ_USUARIO'=>$GPTIuser->USR_ID,'REQ_ESTADO'=>10,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-4-label']		= 'Requerimientos Prueba';
		endif;
		if( $lists['perfil-Gerente-Proveedor'] || $lists['perfil-Ejecutor'] ) :
		//Últimos Requerimientos Derivados, Últimos Requerimientos Desarrollo, Últimos Requerimientos en Prueba y Últimos 
			$lists['lista-1']			= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>6,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-1-label']		= 'Requerimientos Derivados';
			
			$lists['lista-2']			= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>8,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-2-label']		= 'Requerimientos HH Rechazadas';
			
			$lists['lista-3']			= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>9,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-3-label']		= 'Requerimientos Desarrollo';
			
			$lists['lista-4']			= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>10,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-4-label']		= 'Requerimientos Prueba';
			
			$lists['lista-5']			= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>11,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-5-label']		= 'Requerimientos Prueba Rechazada';
			
			$lists['lista-6']			= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>13,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC') );
			$lists['lista-6-label']		= 'Requerimientos Informados';
		endif;
		?>
        
        <?php $flag = true; for($i=1;$flag;++$i):
			if( !isset( $lists["lista-$i"] ) || !isset( $lists["lista-$i-label"] ) ) :
				$flag = false;
				continue;
			endif;
		?>
                            <div class="gpti_ultimos">
                                <table cellpadding="0" cellspacing="0" border="0" >
                                    <tr>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td width="100%"></td>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>   
                                    <tr>
                                        <td></td>
                                        <td class="pt" onclick="javascript:actionToggle('td_jq_<?php echo $i;?>'); return false;"><h1><?php echo $lists["lista-$i-label"];?></h1></td>
                                        <td></td>
                                    </tr>   
                                    <tr>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td></td>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>
                                    <tr class="gpti_blanco">
                                        <td class="gpti_blanco"></td>
                                        <td class="gpti_blanco">
                                        	<div id="td_jq_<?php echo $i;?>">
                                            	<?php echo $lists["lista-$i"];?>
                                            </div>
                                        </td>
                                        <td class="gpti_blanco"></td>
                                    </tr>                  
                                </table>
                            </div>
        <?php endfor;?>
        					<?php /*
                            <div class="gpti_ultimos">
                                <table cellpadding="0" cellspacing="0" border="0" >
                                    <tr>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td width="100%"></td>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>   
                                    <tr>
                                        <td></td>
                                        <td class="pt" onclick="javascript:actionToggle('td_jq_a'); return false;"><h1><?php echo $lists['lista-1-label'];?></h1></td>
                                        <td></td>
                                    </tr>   
                                    <tr>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td></td>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>
                                    <tr class="gpti_blanco">
                                        <td class="gpti_blanco"></td>
                                        <td class="gpti_blanco">
                                        	<div id="td_jq_a">
                                            	<?php echo $lists['lista-1'];?>
                                            </div>
                                        </td>
                                        <td class="gpti_blanco"></td>
                                    </tr>                  
                                </table>
                            </div>
                            <div class="gpti_ultimos">
                                <table cellpadding="0" cellspacing="0" border="0" >
                                    <tr>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td width="100%"></td>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>   
                                    <tr>
                                        <td></td>
                                        <td class="pt" onclick="javascript:actionToggle('td_jq_b'); return false;"><h1><?php echo $lists['lista-2-label'];?></h1></td>
                                        <td></td>
                                    </tr>   
                                    <tr>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td></td>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>
                                    <tr class="gpti_blanco">
                                        <td class="gpti_blanco"></td>
                                        <td class="gpti_blanco">
                                        	<div id="td_jq_b">
                                            	<?php echo $lists['lista-2'];?>
                                            </div>
                                        </td>
                                        <td class="gpti_blanco"></td>
                                    </tr>                  
                                </table>
                            </div>
                            <div class="gpti_ultimos">
                                <table cellpadding="0" cellspacing="0" border="0" >
                                    <tr>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td width="100%"></td>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>   
                                    <tr>
                                        <td></td>
                                        <td class="pt" onclick="javascript:actionToggle('td_jq_c'); return false;"><h1><?php echo $lists['lista-3-label'];?></h1></td>
                                        <td></td>
                                    </tr>   
                                    <tr>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td></td>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>
                                    <tr class="gpti_blanco">
                                        <td class="gpti_blanco"></td>
                                        <td class="gpti_blanco">
                                        	<div id="td_jq_c">
                                            	<?php echo $lists['lista-3'];?>
                                            </div>
                                        </td>
                                        <td class="gpti_blanco"></td>
                                    </tr>                  
                                </table>
                            </div>
                            <?php if( isset( $lists['lista-4'] ) && isset( $lists['lista-4-label'] ) ) : ?>
                            <div class="gpti_ultimos">
                                <table cellpadding="0" cellspacing="0" border="0" >
                                    <tr>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td width="100%"></td>
                                        <td width="9"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>   
                                    <tr>
                                        <td></td>
                                        <td class="pt" onclick="javascript:actionToggle('td_jq_c'); return false;"><h1><?php echo $lists['lista-4-label'];?></h1></td>
                                        <td></td>
                                    </tr>   
                                    <tr>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td></td>
                                        <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>
                                    <tr class="gpti_blanco">
                                        <td class="gpti_blanco"></td>
                                        <td class="gpti_blanco">
                                        	<div id="td_jq_c">
                                            	<?php echo $lists['lista-4'];?>
                                            </div>
                                        </td>
                                        <td class="gpti_blanco"></td>
                                    </tr>                  
                                </table>
                            </div>
                            <?php endif;*/?>
        <?php
	}
}

?>