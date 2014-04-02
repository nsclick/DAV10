<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<script type="text/javascript">
<?php if( count( $datos->rows ) ) : ?>
	window.addEvent('domready', function() {
		var modCumpleLista = new Fx.Scroll('mod_cumple_con_lista');
		$('mod_cumple_down').addEvent('click', function(){
			modCumpleLista.scrollTo(0,$('mod_cumple_con_lista').scrollTop+30);
		});
		$('mod_cumple_up').addEvent('click', function(){
			modCumpleLista.scrollTo(0,$('mod_cumple_con_lista').scrollTop-30);
		});
	});
<?php endif; ?>
	function teclaCumple(e)
	{
		tcl	= (document.all) ? e.keyCode : e.which;
		if (tcl==13){
			modCumpleSubmit();
		}
	}
	
	function modCumpleSubmit()
	{
		frm		= document.modCumple;
		srv		= String(frm.filtro_unidad.value);
		if( srv == "" || srv == frm.filtro_unidad.title )
		{
			alert("Debe ingresar un servicio");
			frm.filtro_unidad.focus();
			return false;
		}else if( srv.length < 3 )
		{
			alert("El servicio debe tener al menos 3 caracteres");
			frm.filtro_unidad.focus();
			frm.filtro_unidad.select();
			return false;
		}else
		{
			srv = srv.toUpperCase();
			srv = srv.replace(String.fromCharCode(193),"A");
			srv = srv.replace(String.fromCharCode(201),"E");
			srv = srv.replace(String.fromCharCode(205),"I");
			srv = srv.replace(String.fromCharCode(211),"O");
			srv = srv.replace(String.fromCharCode(218),"U");
			frm.filtro_unidad.value		= srv;
			
			frm.submit();
		}
	}
</script>
<div class="mod_boxshome mod_cumple" align="left">
	<h2><?php echo $datos->subtitulo;?></h2>
	<h1><?php echo $datos->titulo;?></h1>
<?php if( $datos->error  ) : ?>
    <div class="lista">
    	<span><?php echo $datos->error;?></span>
    </div>
<?php else : ?>
    <div class="lista">
    	<span><?php echo $datos->fecha;?></span>
	<?php if( count( $datos->rows ) ) : ?>
        <div id="mod_cumple_con_lista" class="con_listado">
            <div id="mod_cumple_lista" class="listado">
                <table width="100%" border="0" cellpadding="2" cellspacing="0" class="mod_cumple_lista">
                  <!--<tr>
                    <td align="left"><a href="#" title="Juan Rodriguez">Juan Rodriguez</a></td>
                    <td align="right">09 de Junio</td>
                  </tr>-->
              <?php foreach( $datos->rows as $row ) : list($dia,$mes,$ano)=explode("-",$row['FECHA_NACIMIENTO']); if($dia==date('d')): ?>
                  <tr>
                    <td align="left"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=cumpleanos&task=ver&id=".$row['RUT_FUNCIONARIO']);?>" title="<?php echo $row['NOMBRES'];?> <?php echo $row['APELLIDOS'];?>"><?php echo $row['NOMBRES'];?> <?php echo $row['APELLIDOS'];?></a></td>
                    <td align="right" width="80"><?php echo $dia;?> de <?php echo fixMes(ucfirst(strftime("%B")));?></td>
                  </tr>
              <?php endif; endforeach; ?>
                </table>
            </div>
        </div>
        <div class="flechas">
        	<a href="javascript:void(0);" onclick="javascript:return false;" title="Bajar" id="mod_cumple_down"><img src="<?php echo $template;?>/imagenes/mod_cumple_down.jpg" alt="" border="0" /></a>
        	<a href="javascript:void(0);" onclick="javascript:return false;" title="Subir" id="mod_cumple_up"><img src="<?php echo $template;?>/imagenes/mod_cumple_up.jpg" alt="" border="0" /></a>
        </div>
	<?php else: ?>
    	Hoy d&iacute;a no hay cumplea&ntilde;os.
	<?php endif; ?>
    </div>
<?php endif; ?>
    <div class="form">
      <form name="modCumple" id="modCumple" method="post" action="<?php echo JRoute::_("index.php?Itemid=$datos->menu");?>" onkeypress="javascript:teclaCumple(event);">
      	<h3>B&uacute;squeda de cumplea&ntilde;os por servicio</h3>
      	<div class="margen" style=" overflow:hidden; width:190px;">
          <div style="float:left; width:160px;"><input type="text" name="filtro_unidad" id="modCumple_filtro_unidad" class="inputbox" value="Ingrese aqu&iacute; servicio" title="Ingrese aqu&iacute; servicio" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="30" /></div> <div style="width:30px; float:left;"><a href="javascript:void(0);" onclick="javascript:modCumpleSubmit(); return false;" title="B&uacute;squeda"><img src="<?php echo $template;?>/imagenes/btn_mod_cumple.jpg" alt="B&uacute;squeda" border="0" style="float:right" /></a></div></div>
        <input type="hidden" name="option" value="com_do" />
        <input type="hidden" name="Itemid" value="<?php echo $datos->menu;?>" />
        <input type="hidden" name="c" value="cumpleanos" />
        <input type="hidden" name="task" value="" />
      </form>
    </div>
</div>