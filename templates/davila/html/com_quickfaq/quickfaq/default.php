<?php
/**
 * @version 1.0 $Id: default.php 195 2009-01-30 06:33:12Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2008 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENCE.php
 * QuickFAQ is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * QuickFAQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with QuickFAQ; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
	<div class="com_content seccion" align="left">
    	<h1><?php echo $this->params->get('page_title'); ?></h1>
        <div class="box_descripcion">
        	<?php echo $this->params->get('introtext'); ?>
        </div>
        <div class="box_descripcion_bottom"><img src="images/pix_transparente.gif" alt="" width="710" height="18" /></div>
        <!--<div class="descripcion">
        	<?php echo $this->params->get('introtext'); ?>
        </div>-->
      <?php if ( count( $this->categories ) ) : $pc = round(100/count( $this->categories ));?>
      	<div class="categorias">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="com_content_section_cats">
            <tr>
            <?php foreach ($this->categories as $ic => $category) : ?>
              <td width="<?php echo $pc;?>%" valign="top"<?php echo $ic ? ' style="border-left:1px dotted #CCC; padding-left:20px;"':'';?>>
              <div style="height:50px; overflow:hidden;">
              	<h2 id="quickfaq-cat<?php echo $category->id;?>-title"<?php /*echo !$ic ? ' class="activo"':'';*/?>><?php echo $this->escape($category->title);?></h2>
              </div>
              <?php if( count( $category->items ) ) : ?>
                <ul>
                <?php /*foreach ($category->items as $article) : ?>
                  <li><a href="javascript:void(0);" onclick="javascript:return false;" id="articulos-art<?php echo $article->id;?>-link" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></li>
                <?php endforeach;*/ ?>
                <?php foreach ($category->items as $article) : ?>
                  <li><!--<img src="<?php echo $template;?>/imagenes/icono_flecha.jpg" alt="" border="0" /> --><a href="<?php echo $article->link; ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></li>
                <?php endforeach; ?>
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
          <?php if( count( $category->items ) ) : ?>
        	<?php foreach ($category->items as $article) : ?>
          <div class="pestanas" id="articulos-art<?php echo $article->id;?>-contenedor" style="display:none">
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