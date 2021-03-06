<?php 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
global $mainframe;
$uri	= $mainframe->getSiteURL();
?>
<script language="JavaScript">
function url(uri)
{
	word	= document.getElementById('purge').value;
	document.getElementById('surl').value = '<?php echo JURI::root(); ?>index2.php?option=com_jbolo&purge='+word
}
</script>

<form method="POST" name="adminForm" action="index.php">
	<table border="0" width="100%" class="adminlist">
		<tr>
			<td align="left" width="15%"><strong><?php echo JText::_('PURGE') ?>:</strong></td>
			<td align="left" width="25%"><?php echo JHTML::_('select.booleanlist',  'purge', '', $chat_config['purge'] ); ?></td>
			<td align="left" width="60%"><?php echo JHTML::tooltip(JText::_('Allow purging'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('PURGE_D') ?>:</strong></td>
			<td><input type="text" name="days" width="90%" value="<?php echo $chat_config['days'] ?>" /></td>
			<td><?php echo JHTML::tooltip(JText::_('CHATS OLDER'), '', 'tooltip.png', '', ''); ?></td>
		</tr>						
		<tr>
			<td><strong><?php echo JText::_('PURGE_K') ?>:</strong></td>
			<td><input type="text" id="purge" onkeyup="url();" name="key" width="90%" value="<?php echo $chat_config['key'] ?>" />
			</td> 
			<td><?php echo JHTML::tooltip(JText::_('AUTH KEY'), '', 'tooltip.png', '', ''); ?>
			&nbsp; &nbsp; &nbsp; <?php echo JText::_('URL')?>:&nbsp; &nbsp;
			<input size="100" style='border:none' id="surl" value="<?php echo JURI::root() . 'index2.php?option=com_jbolo&purge='.$chat_config['key'] ?>" />
			</td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('CHAT_USERS') ?>:</strong></td>
			<?php
			$username	= '';
			$name		= '';
			if($chat_config['chatusertitle'])$username	= 'checked'; else $name	= 'checked'; ?>
			<td align="left" width="15%"><input type="radio" name="chatusertitle" value="1" <?php echo $username ?> /><?php echo Jtext::_("U_NAME") ?>
			 <input type="radio" name="chatusertitle" value="0" <?php echo $name ?> /><?php echo Jtext::_("NAME") ?></td>
			<td><?php echo JHTML::tooltip(JText::_('NAME USERNAME'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('COMMUNITY') ?>:</strong></td>
			<?php
				$sa	= '';	//Stand Alone
				$cb	= '';	//Community Builder
				$js	= '';	//JomSocial
				if($chat_config['community']==1) $cb	= 'checked'; 
				else if(($chat_config['community']==2)) $sa	= 'checked';
				else  $js	= 'checked';
			?>
			<td align="left" width="15%">
				 <input type="radio" name="community" value="2" <?php echo $sa ?> ><?php echo Jtext::_("SA")."<br />"  ?>
				 <input type="radio" name="community" value="1" <?php echo $cb ?> ><?php echo Jtext::_("CB")."<br />" ?>
				 <input type="radio" name="community" value="0" <?php echo $js ?> ><?php echo Jtext::_("JS") ?>
			</td>
			<td><?php echo JHTML::tooltip(JText::_('INTEGRATION'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('FRIENDS_ONLY') ?>:</strong></td>
			<?php
			$frinds		= '';
			$everyone	= '';
			if($chat_config['fonly'])$frinds	= 'checked'; else $everyone	= 'checked'; ?>
			<td align="left" width="15%">
				<input type="radio" name="fonly" value="1" <?php echo $frinds ?> ><?php echo Jtext::_("F_ONLY") ?>
				<input type="radio" name="fonly" value="0" <?php echo $everyone ?> ><?php echo Jtext::_("E_ONE") ?></td>
			<td><?php echo JHTML::tooltip(JText::_('PRIVACY'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
<!--
		<tr>
			<td><strong><?php echo JText::_('ALLOW_ALL_TO_CHAT') ?>:</strong></td>
			<td><?php echo JHTML::_('select.booleanlist',  'aatchat', '', $chat_config['aatchat'] ); ?></td>
			<td><?php echo JHTML::tooltip('allow all user to chat who is online', '', 'tooltip.png', '', ''); ?></td>
		</tr>
-->
		
		<tr>
			<td><strong><?php echo JText::_('CHAT_HISTORY') ?>:</strong></td>
			<td><?php echo JHTML::_('select.booleanlist',  'chathistory', 'disabled', 0 ); ?></td>
			<td><?php echo JHTML::tooltip(JText::_('CHAT HISTORY INFO'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />		
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="config" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
