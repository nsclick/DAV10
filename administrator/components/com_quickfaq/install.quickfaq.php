<?php
/**
 * @version 1.0 $Id: install.quickfaq.php 199 2009-01-31 21:50:15Z schlu $
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

/**
 * Executes additional installation processes
 *
 * @since 1.0
 */
function com_install() {
?>

<center>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	<tr>
		<td valign="top">
    		<img src="<?php echo 'components/com_quickfaq/assets/images/logo.png'; ?>" height="108" width="250" alt="QuickFAQ Logo" align="left" />
		</td>
		<td valign="top" width="100%">
       	 	<strong>QuickFAQ</strong><br/>
        	<font class="small">by <a href="http://www.schlu.net" target="_blank">Christoph Lukes</a><br/>
		</td>
	</tr>
</table>
</center>
<?php
}
?>