<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
	<script type="text/javascript">
jQuery(document).ready(function()
{
	/*jQuery("#mod_faq_anterior").click(function(){
		//jQuery("#mod_faq_lista").scrollLeft(jQuery("#mod_faq_lista").scrollLeft-888);	
		//alert(jQuery("#mod_faq_lista").scrollLeft());
		jQuery("#mod_faq_lista").scrollTo(jQuery("#mod_faq_lista").scrollLeft-888,0, {queue:true});	
	});
	jQuery("#mod_faq_siguiente").click(function(){
		//jQuery("#mod_faq_lista").scrollLeft(jQuery("#mod_faq_lista").scrollLeft+888);
		jQuery("#mod_faq_lista").scrollTo(jQuery("#mod_faq_lista").scrollLeft+888,0, {queue:true});
	});*/
	jQuery(".scrollable").scrollable();
});
	</script>
<div class="mod_faq">
    <div align="left">
      <span class="titulo"><a href="<?php echo $datos->link;?>" title="<?php echo $datos->titulo;?>"><?php echo $datos->titulo;?></a></span>
      <span class="link"><a href="<?php echo $datos->linkforo;?>" title="Haz tu consulta aqu&iacute;">Haz tu consulta aqu&iacute; &nbsp;&nbsp;&raquo;</a></span>
    </div>
    <div class="huincha" align="left">
      <div style="float:left; width:28px; padding-top:3px; padding-bottom:3px;"><a href="javascript:void(0);" onclick="javascript:return false;" class="prev browse left" title="Retroceder a la pregunta anterior"><img src="<?php echo $template;?>/imagenes/faq_izquierda.jpg" alt="" border="0" /></a></div>
      <div class="scrollable">
      	<div class="items">
        <?php foreach( $datos->items as $i => $item ): ?>
          <div>
              <span style="text-align:left;">
                <span class="categoria"><?php echo $item->cattitle;?></span><br />
                <a href="<?php echo $item->link;?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
              </span>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
      <div style="float:left; width:28px; padding-top:3px; padding-bottom:3px;" align="right"><a href="javascript:void(0);" onclick="javascript:return false;" class="next browse right" title="Avanzar a la siguiente pregunta"><img src="<?php echo $template;?>/imagenes/faq_derecha.jpg" alt="" border="0" /></a></div>
    </div>
  <?php /*if ( count( $datos->items ) ) : $pc = round(100/count( $datos->items )); $at = 888 * count( $datos->items );?>
    <div class="huincha" align="left">
      <div style="float:left; width:28px; padding-top:3px; padding-bottom:3px;"><a href="javascript:void(0);" onclick="javascript:return false;" id="mod_faq_anterior" title="Retroceder a la pregunta anterior"><img src="<?php echo $template;?>/imagenes/faq_izquierda.jpg" alt="" border="0" /></a></div>
      <div class="items" id="mod_faq_lista">
      	<div style="width:<?php echo $at;?>px;">
        <?php foreach( $datos->items as $i => $item ): ?>
          <div class="item" align="center">
          <span style="text-align:left;">
            <span class="categoria"><?php echo $item->cattitle;?></span><br />
            <a href="<?php echo $item->link;?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
          </span>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
      <div style="float:left; width:28px; padding-top:3px; padding-bottom:3px;" align="right"><a href="javascript:void(0);" onclick="javascript:return false;" id="mod_faq_siguiente" title="Avanzar a la siguiente pregunta"><img src="<?php echo $template;?>/imagenes/faq_derecha.jpg" alt="" border="0" /></a></div>
    </div>
  <?php endif;*/ ?>
</div>
