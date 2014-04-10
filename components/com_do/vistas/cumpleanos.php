 <?php
/**
 * @version		$Id: cumpleanos.php 2010-07-22 sgarcia $
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

class DoVistaCumpleanos
{	

	function display( &$rows, &$lista, &$lists )
	{
		global $Itemid;
		
		?>
        <script type="text/javascript">
		window.addEvent('domready', function() {
		  <?php if( is_array($lista) && count($lista) ) : ?>
			var modCumpleLista = new Fx.Scroll('cumpleanos-lista-contenido');
		  <?php endif;?>
		  <?php if( is_array($rows) && count($rows) ) : ?>
			var modCumpleMes = new Fx.Scroll('cumpleanos-mes-contenido');
		  <?php endif;?>
			$('cumpleanos-lista-down').addEvent('click', function(){
			  <?php if( is_array($lista) && count($lista) ) : ?>
				modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop+150);
			  <?php endif;?>
			  <?php if( is_array($rows) && count($rows) ) : ?>
				modCumpleMes.scrollTo(0,$('cumpleanos-mes-contenido').scrollTop+150);
			  <?php endif;?>
			});
			$('cumpleanos-lista-up').addEvent('click', function(){
			  <?php if( is_array($lista) && count($lista) ) : ?>
				modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop-150);
			  <?php endif;?>
			  <?php if( is_array($rows) && count($rows) ) : ?>
				modCumpleMes.scrollTo(0,$('cumpleanos-mes-contenido').scrollTop-150);
			  <?php endif;?>
			});
		  <?php if( is_array($lista) && count($lista) ) : ?>
			$('cumpleanos-lista-link').addEvent('click', function(){
			  <?php if( is_array($rows) && count($rows) ) : ?>
				$('cumpleanos-mes-contenido').setStyle('display', 'none');
				$('cumpleanos-mes-td').setProperty('class', 'pestana_off' );
			  <?php endif;?>
				$('cumpleanos-lista-contenido').setStyle('display', 'block');
				$('cumpleanos-lista-td').setProperty('class', 'pestana_on' );
			});
		  <?php endif;?>
		  <?php if( is_array($rows) && count($rows) ) : ?>
			$('cumpleanos-mes-link').addEvent('click', function(){
			  <?php if( is_array($lista) && count($lista) ) : ?>
				$('cumpleanos-lista-contenido').setStyle('display', 'none');
				$('cumpleanos-lista-td').setProperty('class', 'pestana_off' );
			  <?php endif;?>
				$('cumpleanos-mes-contenido').setStyle('display', 'block');
				$('cumpleanos-mes-td').setProperty('class', 'pestana_on' );
			});
		  <?php endif;?>
		});		
		</script>
        <div class="componente" align="left">
            <h1>Cumpleaños</h1>
            <div class="box_descripcion">
                <p>Queremos felicitar a todos quienes  están de cumpleaños.<br />
                Además invitamos a todos quienes lo deseen a dejarles mensajes personalizados pinchando sobre el nombre de quienes quieran saludar.</p>
            </div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
            <!--<div class="descripcion">
                Queremos felicitar a todos quienes  están de cumpleaños.<br />
                Además invitamos a todos quienes lo deseen a dejarles mensajes personalizados pinchando sobre el nombre de quienes quieran saludar.
            </div>-->
          <?php if( $lists['msj'] ) : ?>
          	<div class="formulario"><div class="msg"><?php echo $lists['msj'];?></div></div>
          <?php endif; ?>
            <div class="cumpleanos">
              <div class="pestanas">
                <table width="" border="0" cellpadding="0" cellspacing="0" class="pestanas">
                  <tr>
				  <?php if( is_array($lista) && count($lista) ) : ?>
                    <td id="cumpleanos-lista-td" class="<?php echo /*$c ? 'pestana_off':*/'pestana_on';?>"><a href="javascript:void(0);" onclick="javascript:return false;" id="cumpleanos-lista-link" title="Lista de cumpleaños">Lista de cumpleaños</a></td>
				  <?php endif;?>
				  <?php if( is_array($rows) && count($rows) ) : ?>
                    <td id="cumpleanos-mes-td" class="<?php echo is_array($lista) && count($lista)?'pestana_off':'pestana_on';?>"><a href="javascript:void(0);" onclick="javascript:return false;" id="cumpleanos-mes-link" title="<?php echo $lists['mes'];?>"><?php echo $lists['mes'];?></a></td>
				  <?php endif;?>
                  </tr>
                </table>
              </div>
            <?php if( is_array($lista) && count($lista) ) : ?>
              <div class="contenido" id="cumpleanos-lista-contenido" style="display:block;">
                <div class="contenido_scroll">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="pestanas cumpleanos">
                  <!--<tr>
                    <td align="left" valign="middle">
                        <div class="titulo"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=cumpleanos&task=ver&id=1");?>" title="Ver saludos de Luz María Muños Oses">Luz María Muños Oses  /  Dirección médica</a></div>
                        <div class="bajada">01 de Agosto</div>
                    </td>
                  </tr>-->
                <?php foreach( $lista as $list ) : ?>
                  <tr>
                    <td align="left" valign="middle">
                        <div class="titulo"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=cumpleanos&task=ver&id=".$list['RUT_FUNCIONARIO']);?>" title="Ver saludos a <?php echo $list['NOMBRES'];?> <?php echo $list['APELLIDOS'];?>"><?php echo $list['NOMBRES'];?> <?php echo $list['APELLIDOS'];?>  /  <?php echo $list['UNIDAD'];?></a></div>
                        <div class="bajada"><?php list($dia,$mes,$ano)=explode("-",$list['FECHA_NACIMIENTO']); echo $dia;/* echo date("d",strtotime($row['FECHA_NACIMIENTO']));*/?> de <?php echo fixMes(ucfirst(strftime("%B",$list['FN_TIEMPO'])));?></div>
                    </td>
                  </tr>
                <?php endforeach;?>
                </table>
                </div>
              </div>
            <?php endif; ?>
            <?php if( is_array($rows) && count($rows) ) : ?>
              <div class="contenido" id="cumpleanos-mes-contenido" style="display:<?php echo is_array($lista) && count($lista)?'none':'block';?>;">
                <div class="contenido_scroll">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="pestanas cumpleanos">
                  <!--<tr>
                    <td align="left" valign="middle">
                        <div class="titulo"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=cumpleanos&task=ver&id=1");?>" title="Ver saludos de Claudio Quiroga Nuñez">Claudio Quiroga Nuñez  /  Dirección médica</a></div>
                        <div class="bajada">01 de Agosto</div>
                    </td>
                  </tr>-->
                <?php foreach( $rows as $row ) : ?>
                  <tr>
                    <td align="left" valign="middle">
                        <div class="titulo"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=cumpleanos&task=ver&id=".$row['RUT_FUNCIONARIO']);?>" title="Ver saludos a <?php echo $row['NOMBRES'];?> <?php echo $row['APELLIDOS'];?>"><?php echo $row['NOMBRES'];?> <?php echo $row['APELLIDOS'];?>  /  <?php echo $row['UNIDAD'];?></a></div>
                        <div class="bajada"><?php list($dia,$mes,$ano)=explode("-",$row['FECHA_NACIMIENTO']); echo $dia;/* echo date("d",strtotime($row['FECHA_NACIMIENTO']));*/?> de <?php echo fixMes(ucfirst(strftime("%B")));?></div>
                    </td>
                  </tr>
                <?php endforeach;?>
                </table>
                </div>
              </div>
            <?php endif; ?>
            <?php if( ( is_array($rows) && count($rows) ) || ( is_array($lista) && count($lista) ) ) : ?>
              <div class="flechas" align="center">
                <a href="javascript:void(0);" onclick="javascript:return false;" title="Bajar" id="cumpleanos-lista-down"><img src="<?php echo $lists['tmpl'];?>/imagenes/com_do_cumpleanos_down.jpg" alt="" border="0" /></a>
                <a href="javascript:void(0);" onclick="javascript:return false;" title="Subir" id="cumpleanos-lista-up"><img src="<?php echo $lists['tmpl'];?>/imagenes/com_do_cumpleanos_up.jpg" alt="" border="0" /></a>
              </div>
            <?php endif; ?>
            </div>
        </div>
    	<?php
	}	

	function ver( $row, $rows, $lists )
	{
		global $Itemid;
		?>
     <?php if( count( $rows ) ) : ?>
        <script type="text/javascript">
		window.addEvent('domready', function() {
			var modCumpleLista = new Fx.Scroll('cumpleanos-lista-contenido');
			$('cumpleanos-lista-down').addEvent('click', function(){
				modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop+80);
			});
			$('cumpleanos-lista-up').addEvent('click', function(){
				modCumpleLista.scrollTo(0,$('cumpleanos-lista-contenido').scrollTop-80);
			});
			
		});
		</script>
     <?php endif; ?>
        <div class="componente" align="left">
            <h1>Cumpleaños</h1>
            <!--<div class="margen"><img src="<?php echo $lists['tmpl'];?>/imagenes/cumpleanos.jpg" alt="" title="" border="0" /></div>-->
            <div class="margen">
				<script type='text/javascript'>AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,28,0','width','710','height','398','title','Tarjeta de Cumpleaños de <?php echo $row->nombre;?>','src','<?php echo JURI::base();?>images/banners/tarjeta_cumpleanos','flashvars','&amp;nombre=<?php echo $row->nombre;?>','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','wmode','transparent','movie','<?php echo JURI::base();?>images/banners/tarjeta_cumpleanos' );</script>
            </div>
            <div class="margen" style="windth:710px; overflow:hidden;">
            	<div style="float:left; width:300px;" align="left"><input type="button" name="btnsaludar" value="Saludar &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=cumpleanos&task=saludar&id=$row->rut");?>';" class="button_2" /></div>
                <div style="float:left; width:410px;" align="right"><input type="button" name="btnsaludar" value="Volver a la lista de cumpleaños &raquo;" onclick="javascript:window.location='personas.html?c=cumpleanos';" class="button_2" /></div>
            </div>
          <?php if( $lists['msj'] ) : ?>
          	<div class="formulario"><div class="msg"><?php echo $lists['msj'];?></div></div>
          <?php endif; ?>
     	<?php if( count( $rows ) ) : ?>
            <div class="margen cumpleanos">
              <div class="contenido" id="cumpleanos-lista-contenido" style="display:block;">
                <div class="contenido_scroll">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="pestanas cumpleanos">
                  <!--<tr>
                    <td align="left" valign="middle">
                        <div class="titulo">Francisco García Mera <small>dijo:</small></div>
                        <div class="bajada">Esta es una instancia que permite a todos los trabajadores, en conjunto con su jefatura, evaluar su labor y redireccionarla, en caso de ser necesario, con el fin de ir creciendo y apoyando.</div>
                    </td>
                  </tr>-->
                <?php foreach( $rows as $fila ) : ?>
                  <tr>
                    <td align="left" valign="middle">
                        <div class="titulo"><?php echo $fila->remitente;?> <small>dijo:</small></div>
                        <div class="bajada"><?php echo nl2br($fila->mensaje);?></div>
                    </td>
                  </tr>
                <?php endforeach;?>
                </table>
                </div>
              </div>
              <div class="flechas" align="center">
                <a href="javascript:void(0);" onclick="javascript:return false;" title="Bajar" id="cumpleanos-lista-down"><img src="<?php echo $lists['tmpl'];?>/imagenes/com_do_cumpleanos_down.jpg" alt="" border="0" /></a>
                <a href="javascript:void(0);" onclick="javascript:return false;" title="Subir" id="cumpleanos-lista-up"><img src="<?php echo $lists['tmpl'];?>/imagenes/com_do_cumpleanos_up.jpg" alt="" border="0" /></a>
              </div>
            </div>
		 <?php endif; ?>
        </div>
        <?php
	}

	function saludar( $row, $lists )
	{
		global $Itemid;
		?>
        <script type="text/javascript">
			function modCumpleSaludo_submit()
			{
				frm			= document.modCumpleSaludo;
				if( frm.comentarios.value == '' )
				{
					alert("Debe ingresar un saludo");
					frm.comentarios.focus();
					return false;
				}
				frm.submit();
			}
		</script>
        <div class="componente" align="left">
          <form name="modCumpleSaludo" id="modCumpleSaludo" method="post" action="<?php echo JRoute::_("index.php?Itemid=$Itemid&c=cumpleanos");?>">
            <h1>Cumpleaños</h1>
            <div class="box_descripcion">
                <p>Favor complete los comentarios que desea enviarle a <?php echo $row->nombre;?> en su cumpleaños.</p>
            </div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
            <div class="margen">
            	<textarea name="comentarios" class="inputbox_area" rows="6" cols="" style="width:99%; font-size:14px;"></textarea>
            </div>
            <div class="margen">
            	<div style="float:left; width:300px;" align="left"><input type="button" name="btnsaludar" value="Enviar saludo &raquo;" onclick="javascript:modCumpleSaludo_submit();" class="button_2" /></div>
                <div style="float:left; width:410px;" align="right"><input type="button" name="btnsaludar" value="Volver a la lista de cumpleaños &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=cumpleanos");?>';" class="button_2" /></div>
            </div>
            <input type="hidden" name="option" value="com_do" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
            <input type="hidden" name="c" value="cumpleanos" />
            <input type="hidden" name="task" value="saludo" />
            <input type="hidden" name="id" value="<?php echo $row->rut;?>" />
          </form>
        </div>
        <?php
	}
	
}

?>
