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
	if (form.altname.value == ""){
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
				<label for="filename">
					<?php echo JText::_( 'FILENAME' ).':'; ?>
				</label>
			</td>
			<td>
				<?php echo htmlspecialchars($this->row->filename, ENT_QUOTES, 'UTF-8'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="altname">
					<?php echo JText::_( 'DISPLAY NAME' ).':'; ?>
				</label>
			</td>
			<td>
				<input name="altname" value="<?php echo $this->row->altname; ?>" size="50" maxlength="100" />
			</td>
		</tr>
	</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_quickfaq" />
<input type="hidden" name="hits" value="<?php echo $this->row->hits; ?>" />
<input type="hidden" name="filename" value="<?php echo $this->row->filename; ?>" />
<input type="hidden" name="uploaded" value="<?php echo $this->row->uploaded; ?>" />
<input type="hidden" name="uploaded_by" value="<?php echo $this->row->uploaded_by; ?>" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="filemanager" />
<input type="hidden" name="view" value="file" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>