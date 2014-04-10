<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
	<div class="com_content seccion" align="left">
    	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
        <div class="box_descripcion" align="left">
        	<?php echo $this->section->description; ?>
        </div>
        <div class="box_descripcion_bottom"><img src="images/pix_transparente.gif" alt="" width="710" height="18" /></div>
        <!--<div class="descripcion">
        	<?php echo $this->section->description; ?>
        </div>-->
      <?php if ( count( $this->categories ) ) : $pc = round(100/count( $this->categories ));?>
      
      	<div class="categorias">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="com_content_section_cats">
            <tr>
            <?php foreach ($this->categories as $ic => $category) : ?>
              <td width="<?php echo $pc;?>%" align="left" valign="top"<?php echo $ic ? ' style="border-left:1px dotted #CCC; padding-left:20px;"':'';?>>
              <div style="height:50px; overflow:hidden;">
              	<h2><?php echo $this->escape($category->title);?></h2>
              </div>
              <?php if( count( $category->articles ) ) : ?>
                <ul>
                <?php /*foreach ($category->articles as $article) : ?>
                  <li><a href="javascript:void(0);" onclick="javascript:return false;" title="<?php echo $article->title; ?>" id="articulos-art<?php echo $article->id;?>-link"><?php echo $article->title; ?></a></li>
                <?php endforeach;*/ ?>
                <?php foreach ($category->articles as $article) : ?>
                  <li><!--<img src="<?php echo $template;?>/imagenes/icono_flecha.jpg" alt="" border="0" /> --><a href="<?php echo $article->link; ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></li>
                <?php endforeach;?>
                </ul>
              <?php endif;?>
              </td>
            <?php endforeach; ?>
            </tr>
          </table>
        </div>
      <?php /*  
        <div class="mod_categorias" style="padding-top:20px;">
        <?php foreach ($this->categories as $ic => $category) : ?>
          <?php if( count( $category->articles ) ) : ?>
        	<?php foreach ($category->articles as $article) : ?>
          <div class="pestanas" id="articulos-art<?php echo $article->id;?>-contenedor" align="left" style="display:none">
            <table width="" border="0" cellpadding="0" cellspacing="0" class="pestanas">
              <tr>
                <td class="pestana_on"><?php echo $article->title;?></td>
              </tr>
            </table>
            <div class="articulos"><?php echo $article->text;?></div>
          </div>
        	<?php endforeach; ?>
          <?php endif;?>
        <?php endforeach;?>
        </div>
      */ ?>
      <?php endif; ?>
    </div>