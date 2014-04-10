<?php
/**
 * @version 1.0 $Id: default.php 197 2009-01-31 21:34:36Z schlu $
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

defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php?option=com_quickfaq&amp;view=itemelement&amp;tmpl=component" method="post" name="adminForm">

<table class="adminform">
	<tr>
		<td width="100%">
			<?php echo JText::_( 'SEARCH' ); ?>
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['state'];	?>
		</td>
	</tr>
</table>



	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'Num' ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'TITLE', 'i.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'STATE' ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'i.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row = $this->rows[$i];
			
				if ( $row->state == 1 ) {
					$img = 'tick.png';
					$alt = JText::_( 'Published' );
					$state = 1;
				} else if ( $row->state == 0 ) {
					$img = 'publish_x.png';
					$alt = JText::_( 'Unpublished' );
					$state = 0;
				} else if ( $row->state == -1 ) {
					$img = 'disabled.png';
					$alt = JText::_( 'Archived' );
					$state = -1;
				} else if ( $row->state == -2 ) {
					$img = 'publish_r.png';
					$alt = JText::_( 'PENDING' );
					$state = -2;
				} else if ( $row->state == -3 ) {
					$img = 'publish_y.png';
					$alt = JText::_( 'OPEN QUESTION' );
					$state = -3;
				} else if ( $row->state == -4 ) {
					$img = 'publish_g.png';
					$alt = JText::_( 'IN PROGRESS' );
					$state = -4;
				}
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td align="<?php echo $this->direction ? 'right' : 'left'; ?>">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT FAQ ITEM' );?>::<?php echo $row->title; ?>">
					<a style="cursor:pointer" onclick="window.parent.qfSelectItem('<?php echo $row->id; ?>', '<?php echo str_replace( array("'", "\""), array("\\'", ""), $row->title ); ?>');">
					<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
					</a></span>
			</td>
			<td align="center">
				<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
			</td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
	</tbody>

	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>