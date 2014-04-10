<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>
	<div class="com_content seccion">
    	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
        <div class="box_descripcion">
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
              <td width="<?php echo $pc;?>%" valign="top"<?php echo $ic ? ' style="border-left:1px dotted #CCC; padding-left:20px;"':'';?>>
              	<h2 id="mod_categorias-cat<?php echo $category->id;?>-title"<?php echo !$ic ? ' class="activo"':'';?>><?php echo $this->escape($category->title);?></h2>
              <?php if( count( $category->articles ) ) : ?>
                <ul>
                <?php foreach ($category->articles as $article) : ?>
                  <li><a href="<?php echo $article->link; ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></li>
                <?php endforeach;?>
                </ul>
              <?php endif;?>
              </td>
            <?php endforeach; ?>
            </tr>
          </table>
        </div>
      <?php endif; ?>
    </div>