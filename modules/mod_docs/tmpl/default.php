<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="mod_boxshome mod_docs" align="left">
	<h2><?php echo $datos->subtitulo;?></h2>
	<h1><?php echo $datos->titulo;?></h1>
<?php if( count( $datos->rows ) ) : ?>
  <div class="items">
  <?php foreach( $datos->rows as $r => $row ) : ?>
    <div class="item"><a href="<?php echo $row->link;?>" title="<?php echo $row->dmname;?>" target="_blank"><?php echo $row->dmname;?></a></div>
  <?php endforeach; ?>
  </div>
<?php endif;?>
</div>