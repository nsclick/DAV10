<?php
/**
 * @version 1.0 $Id: default_categories.php 195 2009-01-30 06:33:12Z schlu $
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
?>

<?php foreach ($this->categories as $sub) : ?>

<div class="floattext">
    
	<h2 class="quickfaq cat<?php echo $sub->id; ?>">
		<a href="<?php echo JRoute::_( 'index.php?view=category&cid='. $sub->slug ); ?>">
			<?php echo $this->escape($sub->title); ?>
		</a>
	</h2>
	<?php if (!empty($sub->image)) : ?>
	<div class="catimg">
		<?php echo JHTML::image('images/stories/'.$sub->image, $this->escape($sub->title)); ?>
	</div>
	<?php endif; ?>
	
	<div class="catdescription">
		<?php echo $sub->text; ?>
	</div>
	
	<div class="catdets">
		<?php echo JText::_('ASSIGNED ITEMS').': '; ?><?php echo $sub->assignedfaqs != null ? $sub->assignedfaqs : 0; ?>
		<?php
		$n = count($sub->subcats);
		if ($n) :
			echo ' | '.JText::_('SUBCATEGORIES').': ';
		endif;
		
		$i = 0;
		foreach ($sub->subcats as $subcat) : ?>
			<a href="<?php echo JRoute::_( 'index.php?view=category&cid='. $subcat->slug ); ?>"><?php echo $this->escape($subcat->title); ?></a>
			<?php 
			$i++;
			if ($i != $n) :
				echo ',';
			endif;
		endforeach; ?>
	</div>
</div>

<?php endforeach; ?>