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

<script language="javascript" type="text/javascript">
window.onDomReady(stateselector.init.bind(stateselector));

function dostate(state, id)
{	
	var change = new processstate();
    change.dostate( state, id );
}
</script>

<div class="qickfaq">
<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['state']; ?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'Num' ); ?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'TITLE', 'i.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="20%"><?php echo JHTML::_('grid.sort', 'ALIAS', 'i.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="20%"><?php echo JText::_('CATEGORY'); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'STATE' ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'RATING', 'votes', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'HITS', 'i.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="80"><?php echo JHTML::_('grid.sort', 'REORDER', 'i.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%"><?php echo JHTML::_('grid.order', $this->rows, 'filesave.png', 'saveorder' ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'i.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="11">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row = $this->rows[$i];

			$link 		= 'index.php?option=com_quickfaq&amp;controller=items&amp;task=edit&amp;cid[]='. $row->id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			
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
			<td width="7"><?php echo $checked; ?></td>
			<td align="<?php echo $this->direction ? 'right' : 'left'; ?>">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $this->user->get('id') ) ) {
					echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');
				} else {
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT FAQ ITEM' );?>::<?php echo $row->title; ?>">
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
			<td>
				<?php 
				$nr = count($row->categories);
				$ix = 0;
				foreach ($row->categories as $key => $category) :
				
					$catlink	= 'index.php?option=com_quickfaq&amp;controller=categories&amp;task=edit&amp;cid[]='. $category->id;
					$title = htmlspecialchars($category->title, ENT_QUOTES, 'UTF-8');
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT CATEGORY' );?>::<?php echo $title; ?>">
					<a href="<?php echo $catlink; ?>">
						<?php 
						if (JString::strlen($title) > 20) {
							echo JString::substr( $title , 0 , 20).'...';
						} else {
							echo $title;
						}
						?></a></span><?php
					$ix++;
					if ($ix != $nr) :
						echo ', ';
					endif;
				endforeach;
				?>
			</td>
			<td align="center">
			<ul id="statetoggler">
				<li class="topLevel">
					<a href="javascript:void(0);" class="opener">
					<div id="row<?php echo $row->id; ?>">
						<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
					</div>
					</a>
					<div class="options">
						<ul>
							<li>
								<a href="javascript:void(0);" onclick="dostate('1', '<?php echo $row->id; ?>')" class="closer">
									<img src="images/tick.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Published' ); ?>" />
								</a>
							</li>
							<li>
								<a href="javascript:void(0);" onclick="dostate('0', '<?php echo $row->id; ?>')" class="closer">
									<img src="images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Unpublished' ); ?>" />
								</a>	
							</li>
							<li>
								<a href="javascript:void(0);" onclick="dostate('-1', '<?php echo $row->id; ?>')" class="closer">
									<img src="images/disabled.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Archived' ); ?>" />
								</a>
							</li>
							<li>
								<a href="javascript:void(0);" onclick="dostate('-2', '<?php echo $row->id; ?>')" class="closer">
									<img src="images/publish_r.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'PENDING' ); ?>" />
								</a>
							</li>
							<li>
								<a href="javascript:void(0);" onclick="dostate('-3', '<?php echo $row->id; ?>')" class="closer">
									<img src="images/publish_y.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'OPEN QUESTION' ); ?>" />
								</a>	
							</li>
							<li>
								<a href="javascript:void(0);" onclick="dostate('-4', '<?php echo $row->id; ?>')" class="closer">
									<img src="images/publish_g.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'IN PROGRESS' ); ?>" />
								</a>	
							</li>
						</ul>
					</div>
				</li>
			</ul>
			
			</td>
			<td><?php echo quickfaq_html::ratingbar( $row ); ?></td>
			<td align="center"><?php echo $row->hits; ?></td>
			
			<td class="order" colspan="2">
				<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'orderup', 'Move Up', $this->ordering ); ?></span>

				<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $this->ordering );?></span>

				<?php $disabled = $this->ordering ?  '' : '"disabled=disabled"'; ?>

				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
			</td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
	</tbody>

	</table>
	
	<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr>
			<td>
			<img src="images/tick.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'PUBLISHED' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'PUBLISHED DESC' ); ?> <u><?php echo JText::_( 'PUBLISHED' ); ?></u>
			</td>
			<td>
			<img src="images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'UNPUBLISHED' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'UNPUBLISHED DESC' ); ?>
			</td>
			<td>
			<img src="images/disabled.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'ARCHIVED' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'ARCHIVED STATE' ); ?> <u><?php echo JText::_( 'UNPUBLISHED DESC' ); ?></u>
			</td>
		</tr>
		<tr>
		<td>
			<img src="images/publish_r.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'PENDING' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'NEED TO BE APROVED' ); ?> <u><?php echo JText::_( 'UNPUBLISHED DESC' ); ?></u>
			</td>
			<td>
			<img src="images/publish_y.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'OPEN QUESTION' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'OPEN  QUESTION DESC' ); ?> <u><?php echo JText::_( 'UNPUBLISHED DESC' ); ?></u>
			</td>
			<td>
			<img src="images/publish_g.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'IN PROGRESS' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'NOT FINISHED YET' ); ?> <u><?php echo JText::_( 'PUBLISHED' ); ?></u>
			</td>
		</tr>
	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_quickfaq" />
	<input type="hidden" name="controller" value="items" />
	<input type="hidden" name="view" value="items" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>