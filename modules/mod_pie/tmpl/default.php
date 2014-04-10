<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="mod_pie">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mod_pie">
    <tr>
      <td valign="top" align="left" class="logo"><a href="<?php echo $datos->link;?>" title="<?php echo $datos->titulo;?>"><img src="<?php echo $template;?>/imagenes/logo_pie.jpg" alt="<?php echo $datos->titulo;?>" title="<?php echo $datos->titulo;?>" border="0" /></a></td>
      <td valign="top" align="left" class="central">
        <div class="texto"><?php echo $datos->textoCentral;?></div>
        <?php if( $datos->modulo ): ?>
        <div class="modulo">
            <?php echo $datos->text;?>
        </div>
        <?php endif;?>
      </td>
      <td valign="top" align="right" class="lateral">
	    <table border="0" cellpadding="0" cellspacing="0" class="lateral">
          <tr>
           <td align="left" width="50%">Fono RRHH:</td>
           <td align="right"><strong>2730 8084</strong></td>
          </tr>
          <tr>
           <td align="left">&nbsp;</td>
           <td align="right"><strong>2730 8088</strong></td>
          </tr>
          <tr>
           <td align="left">&nbsp;</td>
           <td align="right"><strong>2730 8085</strong></td>
          </tr>
        </table>
	  <!--<?php echo $datos->textoLateral;?>-->
      </td>
    </tr>
  </table>
</div>