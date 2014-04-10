<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
		
		$primero	= $datos->rows[0];
		$segundo	= $datos->rows[1];
?>
<div class="mod_boxshome mod_reconocimientos" style="width:314px;" align="left">
	<h2><?php echo $datos->subtitulo;?></h2>
	<h1><?php echo $datos->titulo;?></h1>
    <div class="ultimos">
    <?php foreach( $datos->rows as $row ) : ?>
    	<div class="ultimo">
        	<div class="foto" align="center"><img src="<?php echo $row->foto;?>" width="62" height="58" alt="" border="0" /></div>
        	<span class="titulo"><?php echo $row->nombre;?></span>
            <span class="descripcion"><?php echo $row->mensaje;?></span>
        </div>
    <?php endforeach;?>
    	<!--<div class="ultimo">
        	<div class="foto" align="center"><img src="<?php echo $template;?>/imagenes/reconocimiento1.jpg" alt="" border="0" /></div>
        	<span class="titulo">Amanda Espinoza</span>
            <span class="descripcion">Por su gran entrega y dedicaci&oacute;n al trabaja</span>
        </div>
    	<div class="ultimo">
        	<div class="foto" align="center"><img src="<?php echo $template;?>/imagenes/reconocimiento2.jpg" alt="" border="0" /></div>
        	<span class="titulo">Alejandra Huerta</span>
            <span class="descripcion">Por su gran entrega y dedicaci&oacute;n al trabaja</span>
        </div>
    	<div class="ultimo">
        	<div class="foto" align="center"><img src="<?php echo $template;?>/imagenes/reconocimiento3.jpg" alt="" border="0" /></div>
        	<span class="titulo">Mauricio Jara</span>
            <span class="descripcion">Por su gran entrega y dedicaci&oacute;n al trabaja</span>
        </div>-->
    </div>
    <div class="botonera">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mod_reconocimientos">
        <tr>
          <td width="42%" align="left" valign="top">
          	<a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=historial");?>" title="Ir al historial">Ir al historial</a>
            <div class="box"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=3meses");?>" title="3 meses">3 meses</a> | <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=6meses");?>" title="6 meses">6 meses</a></div>
          </td>
          <td width="58%" align="left" valign="top" style="border-left:1px dotted #666; padding-left:10px;">
          	<a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=crear");?>" title="Crear un reconocimiento">Crear un reconocimiento</a>
            <!--<div class="box"><a href="#" title="Descripci&oacute;n de charlas">Descripci&oacute;n de charlas</a></div>-->
          </td>
        </tr>
      </table>
    </div>
</div>