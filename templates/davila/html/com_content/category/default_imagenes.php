<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>
	<div class="com_content categoria" align="left">
    	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
        <div class="box_descripcion">
        	<?php echo $this->category->description; ?>
        </div>
        <div class="box_descripcion_bottom"><img src="images/pix_transparente.gif" alt="" width="710" height="18" /></div>
        <!--<div class="descripcion">
        	<?php echo $this->category->description; ?>
        </div>-->
        <div class="itemsgal">
		<?php
            $this->items =& $this->getItems();
            echo $this->loadTemplate('items_imagenes');
        ?>
        </div>
    </div>