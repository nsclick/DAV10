<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
	<?php echo $this->item->img; ?><h2><?php echo $this->item->category;?></h2><h1><a href="<?php echo $this->item->readmore_link; ?>" title="<?php echo $this->escape($this->item->title); ?>"><?php echo $this->escape($this->item->title); ?></a></h1>
	<div class="justificado"><?php echo $this->item->introtext; ?></div>
   	
