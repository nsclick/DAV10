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

echo $this->pane->startPane( 'stat-pane' );
echo $this->pane->startPanel( JText::_('GENERAL STATS'), 'general' );
?>
<table border="0">
<tr>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'GENERAL STATS' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<table class="adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_( 'TYPE' ); ?></th>
					<th><?php echo JText::_( '#' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td>
					<?php echo JText::_('TOTAL NR ITEMS'); ?>
				</td>
				<td align="center">
					<strong><?php echo $this->genstats[0]; ?></strong>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('TOTAL NR CATEGORIES'); ?>
				</td>
				<td align="center">
					<strong><?php echo $this->genstats[1]; ?></strong>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('TOTAL NR TAGS'); ?>
				</td>
				<td align="center">
					<strong><?php echo $this->genstats[2]; ?></strong>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('TOTAL NR FILES'); ?>
				</td>
				<td align="center">
					<strong><?php echo $this->genstats[3]; ?></strong>
				</td>
			</tr>
		</table>
	</div>
</div>
</td>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'MOST POPULAR' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<table class="adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_( 'TITLE' ); ?></th>
					<th><?php echo JText::_( 'HITS' ); ?></th>
					<th><?php echo JText::_( 'RATING' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count($this->popular); $i < $n; $i++) {
				$row = $this->popular[$i];
				$link 		= 'index.php?option=com_quickfaq&amp;controller=items&amp;task=edit&amp;cid[]='. $row->id;
				?>
				<tr>
					<td width="65%">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT FAQ ITEM' ); ?>::<?php echo $row->title; ?>">
							<a href="<?php echo $link; ?>">
								<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
							</a>
						</span>
					</td>
					<td width="1%" align="center">
						<strong><?php echo $row->hits; ?></strong>
					</td>
					<td width="34%">
						<strong><?php echo quickfaq_html::ratingbar( $row ); ?></strong>
					</td>
				</tr>
				<?php $k = 1 - $k; } ?>
			</tbody>
		</table>
	</div>
</div>
</td>
<tr>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'ITEM STATES' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<img src="http://chart.apis.google.com/chart?chs=500x150&amp;chd=t:<?php echo $this->statestats['values']; ?>&amp;cht=p3&amp;chl=<?php echo $this->statestats['labels']; ?>" alt="<?php echo JText::_('ITEM STATES CHART'); ?>" />
	</div>
</div>
</td>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'MOST FAVOURED' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<table class="adminlist">
		<thead>
				<tr>
					<th><?php echo JText::_( 'TITLE' ); ?></th>
					<th><?php echo JText::_( '#' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count($this->favoured); $i < $n; $i++) {
			$row = $this->favoured[$i];
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
				<td align="center">
					<strong><?php echo $row->favnr; ?></strong>
				</td>
			</tr>
			<?php $k = 1 - $k; } ?>
		</table>
	</div>
</div>
</td>
</tr>
</table>
<?php
echo $this->pane->endPanel();
echo $this->pane->startPanel( JText::_( 'RATING STATS' ), 'ratings' );
?>
<table border="0">
<tr>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'VOTE STATS' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<img src="http://chart.apis.google.com/chart?chs=500x150&amp;chd=t:<?php echo $this->votesstats['values']; ?>&amp;cht=p3&amp;chl=<?php echo $this->votesstats['labels']; ?>" alt="<?php echo JText::_('ITEM VOTES CHART'); ?>" />
	</div>
</div>
</td>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'BEST RATED' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<table class="adminlist">
		<thead>
				<tr>
					<th><?php echo JText::_( 'TITLE' ); ?></th>
					<th><?php echo JText::_( 'RATING' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count($this->rating); $i < $n; $i++) {
			$row = $this->rating[$i];
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
				<td>
					<strong><?php echo quickfaq_html::ratingbar( $row ); ?></strong>
				</td>
			</tr>
			<?php $k = 1 - $k; } ?>
		</table>
	</div>
</div>
</td>
</tr>
<tr>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'WORST RATED' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<table class="adminlist">
		<thead>
				<tr>
					<th><?php echo JText::_( 'TITLE' ); ?></th>
					<th><?php echo JText::_( 'RATING' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count($this->worstrating); $i < $n; $i++) {
			$row = $this->worstrating[$i];
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
				<td>
					<strong><?php echo quickfaq_html::ratingbar( $row ); ?></strong>
				</td>
			</tr>
			<?php $k = 1 - $k; } ?>
		</table>
	</div>
</div>
</td>
</tr>
</table>
<?php
echo $this->pane->endPanel();
echo $this->pane->startPanel( JText::_( 'USER STATS' ), 'users' );
?>
<table border="0">
<tr>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'TOP CONTRIBUTORS' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<table class="adminlist">
		<thead>
				<tr>
					<th><?php echo JText::_( 'USER' ); ?></th>
					<th><?php echo JText::_( '#' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count($this->creators); $i < $n; $i++) {
			$row = $this->creators[$i];
			$link 		= 'index.php?option=com_users&amp;view=user&amp;task=edit&amp;cid[]='. $row->id;
			?>
			<tr>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT USER' );?>::<?php echo $row->username; ?>">
						<a href="<?php echo $link; ?>">
							<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8').' ('.htmlspecialchars($row->username, ENT_QUOTES, 'UTF-8').')'; ?>
						</a>
					</span>
				</td>
				<td align="center">
					<strong><?php echo $row->counter; ?></strong>
				</td>
			</tr>
			<?php $k = 1 - $k; } ?>
		</table>
	</div>
</div>
</td>
<td>
<div class="cssbox">
	<div class="cssbox_head">
		<h2><?php echo JText::_( 'TOP EDITORS' ); ?></h2>
	</div>
	<div class="cssbox_body">
		<table class="adminlist">
		<thead>
				<tr>
					<th><?php echo JText::_( 'USER' ); ?></th>
					<th><?php echo JText::_( '#' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count($this->editors); $i < $n; $i++) {
			$row = $this->editors[$i];
			$link 		= 'index.php?option=com_users&amp;view=user&amp;task=edit&amp;cid[]='. $row->id;
			?>
			<tr>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT USER' );?>::<?php echo $row->username; ?>">
						<a href="<?php echo $link; ?>">
							<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8').' ('.htmlspecialchars($row->username, ENT_QUOTES, 'UTF-8').')'; ?>
						</a>
					</span>
				</td>
				<td align="center">
					<strong><?php echo $row->counter; ?></strong>
				</td>
			</tr>
			<?php $k = 1 - $k; } ?>
		</table>
	</div>
</div>
</td>
</tr>
</table>
<?php
echo $this->pane->endPanel();
echo $this->pane->endPane();
?>