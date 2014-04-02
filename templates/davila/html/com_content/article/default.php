<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>
	<div class="com_content articulo" align="left">
    	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
        <div class="box_descripcion">
        	<?php echo $this->article->introtext; ?>
        </div>
        <div class="box_descripcion_bottom"><img src="images/pix_transparente.gif" alt="" width="710" height="18" /></div>
        <!--<div class="descripcion">
        	<?php echo $this->article->introtext; ?>
        </div>-->
        <div class="detalle">
        	<?php echo $this->article->text; ?>
            <?php echo $this->article->event->afterDisplayContent;?>
        </div>
    </div>