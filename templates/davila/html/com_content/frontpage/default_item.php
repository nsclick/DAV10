<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
	<?php echo $this->item->img; ?><a href="<?php echo $this->item->readmore_link; ?>" title="<?php echo $this->escape($this->item->title); ?>"><?php echo $this->escape($this->item->title); ?></a>