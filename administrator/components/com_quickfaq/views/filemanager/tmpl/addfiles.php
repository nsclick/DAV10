<?php
/**
 * @version 1.0 $Id: addfiles.php 195 2009-01-30 06:33:12Z schlu $
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
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td>
		    <?php if ($this->require_ftp): ?>
            <form action="index.php?option=com_quickfaq&amp;controller=filemanager&amp;task=ftpValidate" name="ftpForm" id="ftpForm" method="post">
                <fieldset title="<?php echo JText::_('DESCFTPTITLE'); ?>">
                    <legend><?php echo JText::_('DESCFTPTITLE'); ?></legend>
                    <?php echo JText::_('DESCFTP'); ?>
                    <table class="adminform nospace">
                        <tbody>
                            <tr>
                                <td width="120">
                                    <label for="username"><?php echo JText::_('Username'); ?>:</label>
                                </td>
                                <td>
                                    <input type="text" id="username" name="username" class="input_box" size="70" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td width="120">
                                    <label for="password"><?php echo JText::_('Password'); ?>:</label>
                                </td>
                                <td>
                                    <input type="password" id="password" name="password" class="input_box" size="70" value="" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </form>
            <?php endif; ?>
				
			<!-- File Upload Form -->
            <form action="<?php echo JURI::base(); ?>index.php?option=com_quickfaq&amp;controller=filemanager&amp;task=upload&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JUtility::getToken();?>=1" id="uploadForm" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend><?php echo JText::_( 'Upload File' ); ?> [ <?php echo JText::_( 'Max' ); ?>&nbsp;<?php echo ($this->params->get('upload_maxsize') / 1000000); ?>M ]</legend>
                    <fieldset class="actions">
                    	<?php echo JText::_( 'DISPLAY NAME' ).': '; ?><input type="text" id="file-upload-name" name="altname" />
                    	<br /><br />
                        <input type="file" id="file-upload" name="Filedata" />                        
                        <input type="submit" id="file-upload-submit" value="<?php echo JText::_('Start Upload'); ?>"/>
                        <span id="upload-clear"></span>
                    </fieldset>
                    <ul class="upload-queue" id="upload-queue">
                        <li style="display: none" />
                    </ul>
                </fieldset>
                <input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_quickfaq&view=filemanager&layout=addfiles&tmpl=component'); ?>" />
            </form>
		</td>
	</tr>
</table>