<?php
/**
 * @version 1.0 $Id: default_subcategories.php 195 2009-01-30 06:33:12Z schlu $
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

<div class="subcategories">
<?php echo JText::_('SUBCATEGORIES'); ?>
</div>
<?php
$n = count($this->categories);
$i = 0;
?>
<div class="subcategorieslist">
	<?php foreach ($this->categories as $sub) : ?>
		<strong><a href="<?php echo JRoute::_( 'index.php?view=category&cid='. $sub->slug ); ?>"><?php echo $this->escape($sub->title); ?></a></strong> (<?php echo $sub->assignedfaqs != null ? $sub->assignedfaqs : 0; ?>)
		<?php 
		$i++;
		if ($i != $n) :
			echo ',';
		endif;
	endforeach; ?>
</div>