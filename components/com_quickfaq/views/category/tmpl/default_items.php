<?php
/**
 * @version 1.0 $Id: default_items.php 195 2009-01-30 06:33:12Z schlu $
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
<script type="text/javascript">

	function tableOrdering( order, dir, task )
	{
		var form = document.getElementById("adminForm");

		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		document.getElementById("adminForm").submit( task );
	}
</script>

<?php if ($this->params->get('filter') || $this->params->get('display')) : ?>

<form action="<?php echo $this->action; ?>" method="post" id="adminForm">

<div id="qf_filter" class="floattext">
		<?php if ($this->params->get('filter')) : ?>
		<div class="qf_fleft">
			<input type="text" name="filter" id="filter" value="<?php echo $this->lists['filter'];?>" class="text_area" onchange="document.getElementById('adminForm').submit();" />
			<button onclick="document.getElementById('adminForm').submit();"><?php echo JText::_( 'GO' ); ?></button>
			<button onclick="document.getElementById('filter').value='';document.getElementById('adminForm').submit();"><?php echo JText::_( 'RESET' ); ?></button>
		</div>
		<?php endif; ?>
		<?php if ($this->params->get('display')) : ?>
		<div class="qf_fright">
			<?php
			echo '<label for="limit">'.JText::_('Display Num').'</label>&nbsp;';
			echo $this->pageNav->getLimitBox();
			?>
		</div>
		<?php endif; ?>
</div>
<?php endif; ?>

<table class="faqitemtable" width="100%" border="0" cellspacing="0" cellpadding="0" summary="quickfaq">
	<thead>
			<tr>
				<th id="qf_title" class="sectiontableheader"><?php echo JHTML::_('grid.sort', JText::_('ITEMS'), 'i.title', $this->lists['filter_order_Dir'], $this->lists['filter_order'] ); ?></th>
				<?php if ($this->params->get('show_vote')) : ?>
					<th id="qf_votes" class="sectiontableheader"><?php echo JHTML::_('grid.sort', JText::_('RATING'), 'votes', $this->lists['filter_order_Dir'], $this->lists['filter_order'] ); ?></th>
				<?php endif; ?>
				<?php if ($this->params->get('show_hits')) : ?>
					<th id="qf_hits" class="sectiontableheader"><?php echo JHTML::_('grid.sort', JText::_('HITS'), 'i.hits', $this->lists['filter_order_Dir'], $this->lists['filter_order'] ); ?></th>
				<?php endif; ?>
			</tr>
	</thead>
	
	<tbody>
	
	<?php foreach ($this->items as $item) : ?>
  			<tr class="sectiontableentry" >

    			<td headers="qf_title">
    				<strong><a href="<?php echo JRoute::_( 'index.php?view=items&cid='. $this->category->slug.'&id='. $item->slug ); ?>"><?php echo $this->escape($item->title); ?></a></strong>
    				<?php echo quickfaq_html::stateicon( $item->state, $this->params ); ?>
				</td>
				<?php if ($this->params->get('show_vote')) : ?>
					<td headers="qf_votes">
						<?php echo quickfaq_html::ratingbar( $item ); ?>
					</td>
				<?php endif; ?>
				<?php if ($this->params->get('show_hits')) : ?>
					<td headers="qf_hits">
						<?php echo $item->hits; ?>
					</td>
				<?php endif; ?>
				
			</tr>
	<?php endforeach; ?>
			
	</tbody>
</table>

<?php if ($this->params->get('filter') || $this->params->get('display')) : ?>
<p>
<input type="hidden" name="option" value="com_quickfaq" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
</p>
</form>

<?php endif; ?>