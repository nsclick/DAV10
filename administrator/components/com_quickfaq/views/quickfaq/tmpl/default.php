<?php
/**
 * @version 1.0 $Id: default.php 195 2009-01-30 06:33:12Z schlu $
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
			<td valign="top">
			<table class="adminlist">
				<tr>
					<td>
						<div id="cpanel">
						<?php
						global $option;

						$link = 'index.php?option='.$option.'&amp;view=items';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-items.png', JText::_( 'ITEMS' ) );

						$link = 'index.php?option='.$option.'&amp;view=item';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-itemedit.png', JText::_( 'NEW ITEM' ) );
						
						$link = 'index.php?option='.$option.'&amp;view=tags';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-tags.png', JText::_( 'TAGS' ) );

						$link = 'index.php?option='.$option.'&amp;view=tag';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-tagedit.png', JText::_( 'NEW TAG' ) );

						$link = 'index.php?option='.$option.'&amp;view=categories';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-categories.png', JText::_( 'CATEGORIES' ) );

						$link = 'index.php?option='.$option.'&amp;view=category';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-categoryedit.png', JText::_( 'NEW CATEGORY' ) );

						$link = 'index.php?option='.$option.'&amp;view=archive';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-archive.png', JText::_( 'ARCHIVE' ) );
				
						$link = 'index.php?option='.$option.'&amp;view=editcss';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-editcss.png', JText::_( 'EDIT CSS' ) );
						
						$link = 'index.php?option='.$option.'&amp;view=filemanager';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-filemanager.png', JText::_( 'FILEMANAGER' ) );
						
						$link = 'index.php?option='.$option.'&amp;view=stats';
						QuickfaqViewQuickfaq::quickiconButton( $link, 'icon-48-stats.png', JText::_( 'STATISTICS' ) );
						?>
						</div>
					</td>
				</tr>
			</table>
			</td>
			<td valign="top" width="320px" style="padding: 7px 0 0 5px">
			<?php
			$title = JText::_( 'UNAPPROVED' );
			echo $this->pane->startPane( 'stat-pane' );
			echo $this->pane->startPanel( $title, 'unapproved' );

				?>
				<table class="adminlist">
				<?php
					$k = 0;
					$n = count($this->unapproved);
					for ($i=0, $n; $i < $n; $i++) {
					$row = $this->unapproved[$i];
					$link 		= 'index.php?option=com_quickfaq&amp;controller=items&amp;task=edit&amp;cid[]='. $row->id;
				?>
					<tr>
						<td>
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT FAQ ITEM' );?>::<?php echo $row->title; ?>">
								<a href="<?php echo $link; ?>">
									<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</span>
						</td>
					</tr>
					<?php $k = 1 - $k; } ?>
				</table>
				<?php
				$title = JText::_( 'OPEN QUESTIONS' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, 'openquest' );

				?>
				<table class="adminlist">
				<?php
					$k = 0;
					$n = count($this->openquest);
					for ($i=0, $n; $i < $n; $i++) {
					$row = $this->openquest[$i];
					$link 		= 'index.php?option=com_quickfaq&amp;controller=items&amp;task=edit&amp;cid[]='. $row->id;
				?>
					<tr>
						<td>
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT FAQ ITEM' );?>::<?php echo $row->title; ?>">
								<a href="<?php echo $link; ?>">
									<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</span>
						</td>
					</tr>
					<?php $k = 1 - $k; } ?>
				</table>
				<?php
				$title = JText::_( 'DONATE' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, 'donate' );
				?>
				<table class="adminlist">
					<tr>
						<td>
							<?php echo JText::_('DONATION DESC'); ?>
						</td>
						<td align="center">
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_donations">
							<input type="hidden" name="business" value="webmaster@schlu.net">
							<input type="hidden" name="item_name" value="QuickFAQ Project">
							<input type="hidden" name="no_shipping" value="0">
							<input type="hidden" name="no_note" value="1">
							<input type="hidden" name="currency_code" value="EUR">
							<input type="hidden" name="tax" value="0">
							<input type="hidden" name="bn" value="PP-DonationsBF">
							<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
							<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
							</form>
						</td>
					</tr>
				</table>
				<?php
				echo $this->pane->endPanel();
				echo $this->pane->endPane();
				
				if($this->check['connect'] == 0) {
				?>
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'UPDATE CHECK' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
			<td colspan="2">
				<?php
		  			echo '<b><font color="red">'.JText::_( 'CONNECTION FAILED' ).'</font></b>';
		  		?>
			</td>
			</tr>
			</tbody>
			</table>
				<?php
				} elseif ($this->check['enabled'] == 1) {
				?>
			<table class="adminlist">
			<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'UPDATE CHECK' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
			<td width="40%">
				<?php
		  			if ($this->check['current'] == 0 ) {
		  				echo JHTML::_('image', 'administrator/templates/'. $this->template .'/images/header/icon-48-checkin.png', NULL);
		  			} elseif( $this->check['current'] == -1 ) {
		  				echo JHTML::_('image', 'administrator/templates/'. $this->template .'/images/header/icon-48-help_header.png', NULL);
		  			} else {
		  				echo JHTML::_('image', 'administrator/templates/'. $this->template .'/images/header/icon-48-help_header.png', NULL);
		  			}
		  		?>
			</td>
			<td>
				<?php
		  			if ($this->check['current'] == 0) {
		  				echo '<strong><font color="green">'.JText::_( 'LATEST VERSION INSTALLED' ).'</font></strong>';
		  			} elseif( $this->check['current'] == -1 ) {
		  				echo '<b><font color="red">'.JText::_( 'OLD VERSION' ).'</font></b>';
		  			} else {
		  				echo '<b><font color="orange">'.JText::_( 'NEWER VERSION' ).'</font></b>';
		  			}
		  		?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo JText::_( 'LATEST VERSION' ).':'; ?>
			</td>
			<td>
				<?php echo $this->check['versionread']; ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo JText::_( 'INSTALLED VERSION' ).':'; ?>
			</td>
			<td>
				<?php echo $this->check['versionread_current']; ?>
			</td>
		</tr>
		<tr>
		  	<td width="40%">
		  		<?php echo JText::_( 'RELEASE DATE' ).':'; ?>
		  	</td>
		  	<td>
		  		<?php echo $this->check['released']; ?>
		  	</td>
		</tr>
		<tr>
		  	<td colspan="2" align="center">
		  		<a href="http://www.schlu.net" title="<?php echo JText::_( 'QUICKFAQ PROJECT' ); ?>" target="_blanc">
		  			<?php echo JText::_( 'QUICKFAQ PROJECT' ); ?>
		  		</a>
		  	</td>
		</tr>
		</tbody>
		</table>
		<?php } ?>
			</td>
		</tr>
	</table>