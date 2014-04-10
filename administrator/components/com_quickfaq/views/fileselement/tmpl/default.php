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

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<form action="index.php?option=com_quickfaq&amp;view=fileselement&amp;tmpl=component" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
			  	<?php echo $this->lists['filter']; ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'Num' ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'FILENAME', 'f.filename', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="20%"><?php echo JHTML::_('grid.sort', 'DISPLAY NAME', 'f.altname', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="7%"><?php echo JText::_('SIZE'); ?></th>
			<th width="15"><?php echo JHTML::_('grid.sort', 'HITS', 'f.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="15"><?php echo JHTML::_('grid.sort', 'ASSIGNED TO', 'nrassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'UPLOADER', 'uploader', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="10%"><?php echo JHTML::_('grid.sort', 'UPLOAD TIME', 'f.uploaded', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'f.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
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
		$index = JRequest::getInt('index', 0);
		$k = 0;
		$i = 0;
		$n = count($this->rows);
		foreach ($this->rows as $row) {
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td align="<?php echo $this->direction ? 'right' : 'left'; ?>">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'SELECT' );?>::<?php echo $row->filename; ?>">
				<a style="cursor:pointer" onclick="qffileselementadd('<?php echo $row->id; ?>', '<?php echo str_replace( array("'", "\""), array("\\'", ""), $row->filename ); ?>');">
				<?php echo JHTML::image($row->icon, '').' '.htmlspecialchars($row->filename, ENT_QUOTES, 'UTF-8'); ?>
				</a></span>
			</td>
			<td>
				<?php
				if (JString::strlen($row->altname) > 25) {
					echo JString::substr( htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8');
				}
				?>
			</td>
			<td align="center"><?php echo $row->size; ?></td>
			<td align="center"><?php echo $row->hits; ?></td>
			<td align="center"><?php echo $row->nrassigned; ?></td>
			<td align="center"><?php echo $row->uploader; ?></td>
			<td align="center"><?php echo JHTML::Date( $row->uploaded, JText::_( 'DATE_FORMAT_LC1' ) );; ?></td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		} 
		?>
	</tbody>

	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="file" value="" />
	<input type="hidden" name="files" value="<?php echo $this->files; ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>