<?php
/**
 * @version 1.0 $Id: default.php 136 2008-08-04 14:33:18Z schlu $
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

defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.name.value == ""){
		alert( "<?php echo JText::_( 'ADD NAME TAG' ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td>
				<label for="title">
					<?php echo JText::_( 'TAG NAME' ).':'; ?>
				</label>
			</td>
			<td>
				<input name="name" value="<?php echo $this->row->name; ?>" size="50" maxlength="100" />
			</td>
			<td>
				<label for="published">
					<?php echo JText::_( 'PUBLISHED' ).':'; ?>
				</label>
			</td>
			<td>
				<?php
				$html = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->row->published );
				echo $html;
				?>
			</td>
		</tr>
	</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_quickfaq" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="tags" />
<input type="hidden" name="view" value="tag" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>