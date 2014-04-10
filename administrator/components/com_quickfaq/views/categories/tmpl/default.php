<?php
/**
 * @version 1.0 $Id: default.php 197 2009-01-31 21:34:36Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2005 - 2008 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
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

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
			  <?php
			  echo $this->lists['state'];
				?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'Num' ); ?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'CATEGORY', 'c.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="20%"><?php echo JHTML::_('grid.sort', 'ALIAS', 'c.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="15"><?php echo JHTML::_('grid.sort', 'ASSIGNED TO', 'nrassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'PUBLISHED' ); ?></th>
			<th width="7%"><?php echo JHTML::_('grid.sort', 'ACCESS', 'c.access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="80"><?php echo JHTML::_('grid.sort', 'REORDER', 'c.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%"><?php echo JHTML::_('grid.order', $this->rows, 'filesave.png', 'saveorder' ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'c.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
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
		$i = 0;
		$n = count($this->rows);
		foreach ($this->rows as $row) {

			$link 		= 'index.php?option=com_quickfaq&amp;controller=categories&amp;task=edit&amp;cid[]='. $row->id;
			$published 	= JHTML::_('grid.published', $row, $i );
			$access 	= JHTML::_('grid.access', $row, $i );
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><?php echo $checked; ?></td>
			<td align="<?php echo $this->direction ? 'right' : 'left'; ?>">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $this->user->get('id') ) ) {
					echo htmlspecialchars($row->treename.$row->title, ENT_QUOTES, 'UTF-8');
				} else {
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT CATEGORY' );?>::<?php echo $row->alias; ?>">
					<?php echo $row->treename.' ';?>
					<a href="<?php echo $link; ?>">
					<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
					</a></span>
				<?php
				}
				?>
			</td>
			<td>
				<?php
				if (JString::strlen($row->alias) > 25) {
					echo JString::substr( htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
				}
				?>
			</td>
			<td align="center"><?php echo $row->nrassigned?></td>
			<td align="center">
				<?php echo $published; ?>
			</td>
			<td align="center">
				<?php echo $access; ?>
			</td>
			<td class="order" colspan="2">
				<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'orderup', 'Move Up', $this->ordering ); ?></span>

				<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $this->ordering );?></span>

				<?php $disabled = $this->ordering ?  '' : '"disabled=disabled"'; ?>

				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
			</td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		} 
		?>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_quickfaq" />
	<input type="hidden" name="controller" value="categories" />
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>