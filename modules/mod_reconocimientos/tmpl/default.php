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
      <table width="264" border="0" cellpadding="0" cellspacing="0" class="mod_reconocimientos">
        <tr>
          <td width="42%" align="left" valign="top">
            <div class="ultimo">
              <div class="foto" align="center">
                <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=ver&id=$primero->id&tmpl=diploma");?>" title="<?php echo $primero->nombre;?>">
                  <img src="<?php echo $primero->foto ?>" height="60" width="45" align="middle" alt="" border="0" />
                </a>
              </div>
              <span class="titulo"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=ver&id=$primero->id&tmpl=diploma");?>" title="<?php echo $primero->nombre;?>"><?php echo $primero->nombre;?></a></span>
            </div>
            <div class="botonera">
                <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=historial");?>" title="Ir al historial">Ir al historial</a>
                <div class="box"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=tresmeses");?>" title="3 meses">3 meses</a> | <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=seismeses");?>" title="6 meses">6 meses</a></div>
            </div>
          </td>
          <td width="58%" align="left" valign="top" style="border-left:1px dotted #666; padding-left:10px;">
            <div class="ultimo">
              <div class="foto" align="center">
                <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=ver&id=$segundo->id&tmpl=diploma");?>" title="<?php echo $segundo->nombre;?>">
                  <img src="<?php echo $segundo->foto ?>" height="60" width="45" align="middle" alt="" border="0" />
                </a>
              </div>
              <span class="titulo">
                <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=ver&id=$segundo->id&tmpl=diploma");?>" title="<?php echo $segundo->nombre;?>"><?php echo $segundo->nombre;?></a>
              </span>
            </div>
            <div class="botonera">
                <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$datos->menu&c=reconocimientos&task=mantener");?>" title="Administrar Reconocimientos">Administrar Reconocimientos</a>
            </div>
          </td>
        </tr>
      </table>
    </div>
</div>