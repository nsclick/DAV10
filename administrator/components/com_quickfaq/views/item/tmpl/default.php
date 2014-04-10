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
window.addEvent( "domready", function()
{  
	var tags = new itemscreen('tags', {id:<?php echo $this->row->id ? $this->row->id : 0; ?>, task:'gettags'});
    tags.fetchscreen();
    
    var hits = new itemscreen('hits', {id:<?php echo $this->row->id ? $this->row->id : 0; ?>, task:'gethits'});
    hits.fetchscreen();
    
    var votes = new itemscreen('votes', {id:<?php echo $this->row->id ? $this->row->id : 0; ?>, task:'getvotes'});
    votes.fetchscreen();
});

function addtag()
{
	var tagname = document.adminForm.tagname.value;
	
	if(tagname == ''){
    	alert('<?php echo JText::_('ENTER TAG', true); ?>');
		return;
	}
	
	var tag = new itemscreen();
    tag.addtag( tagname );
    var tags = new itemscreen('tags', {id:<?php echo $this->row->id ? $this->row->id : 0; ?>, task:'gettags'});
    tags.fetchscreen();
}

function reseter(task, id, div)
{	
	var form = document.adminForm;
	
	if (task == 'resethits') {
		form.hits.value = 0;
	} else {
		form.minus.value = 0;
		form.plus.value = 0;
	}
		
	var res = new itemscreen();
    res.reseter( task, id, div );
}

function submitbutton(pressbutton) {
	
	var form = document.adminForm;
	
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.title.value == ""){
		alert( "<?php echo JText::_( 'ADD TITLE' ); ?>" );
	} else if(form.cid.selectedIndex == -1) {
		alert( "<?php echo JText::_( 'SELECT CATEGORY' ); ?>" );
	} else {
		<?php echo $this->editor->save( 'text' ); ?>
		submitform( pressbutton );
	}
}
</script>
<?php
//Set the info image
$infoimage = JHTML::image ( 'components/com_quickfaq/assets/images/icon-16-hint.png', JText::_ ( 'NOTES' ) );
?>

<div class="quickfaq">

<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td valign="top">
				<table  class="adminform">
					<tr>
						<td>
							<label for="title">
								<?php echo JText::_( 'TITLE' ).':'; ?>
							</label>
						</td>
						<td>
							<input name="title" value="<?php echo $this->row->title; ?>" size="50" maxlength="254" />
						</td>
						<td>
							<label for="published">
								<?php echo JText::_( 'STATE' ).':'; ?>
							</label>
						</td>
						<td>
							<?php
							echo $this->lists['state'];
							?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="alias">
								<?php echo JText::_( 'ALIAS' ).':'; ?>
							</label>
						</td>
						<td colspan="3">
							<input class="inputbox" type="text" name="alias" id="alias" size="50" maxlength="254" value="<?php echo $this->row->alias; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="tags">
								<?php echo JText::_( 'TAGS' ).':'; ?>
							</label>
						</td>
						<td colspan="3">
							<div id="tags"></div>
						</td>
					</tr>
				</table>

			<table class="adminform">
				<tr>
					<td>
						<?php
						// parameters : areaname, content, hidden field, width, height, rows, cols
						echo $this->editor->display( 'text',  $this->row->text, '100%;', '350', '75', '20', array('pagebreak') ) ;
						?>
					</td>
				</tr>
			</table>
			</td>
			<td valign="top" width="320px" style="padding: 7px 0 0 5px">
			
		<?php
		// used to hide "Reset Hits" when hits = 0
		if ( !$this->row->hits ) {
			$visibility = 'style="display: none; visibility: hidden;"';
		} else {
			$visibility = '';
		}
		
		if ( !$this->row->plus && !$this->row->minus ) {
			$visibility2 = 'style="display: none; visibility: hidden;"';
		} else {
			$visibility2 = '';
		}

		?>
		<table width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
		<?php
		if ( $this->row->id ) {
		?>
		<tr>
			<td>
				<strong><?php echo JText::_( 'FAQ ITEM ID' ); ?>:</strong>
			</td>
			<td>
				<?php echo $this->row->id; ?>
			</td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td>
				<strong><?php echo JText::_( 'STATE' ); ?></strong>
			</td>
			<td>
				<?php echo $this->row->state > 0 ? JText::_( 'PUBLISHED' ) : ($this->row->state < 0 ? JText::_( 'ARCHIVED' ) : JText::_( 'DRAFT UNPUBLISHED' ) );?>
			</td>
		</tr>
		<tr>
			<td>
				<strong><?php echo JText::_( 'HITS' ); ?></strong>
			</td>
			<td>
				<div id="hits"></div>
				<span <?php echo $visibility; ?>>
					<input name="reset_hits" type="button" class="button" value="<?php echo JText::_( 'RESET' ); ?>" onclick="reseter('resethits', '<?php echo $this->row->id; ?>', 'hits')" />
				</span>
				<div id="hits"></div>
			</td>
		</tr>
				<tr>
			<td>
				<strong><?php echo JText::_( 'VOTES' ); ?></strong>
			</td>
			<td>
				<div id="votes"></div>
				<span <?php echo $visibility2; ?>>
					<input name="reset_votes" type="button" class="button" value="<?php echo JText::_( 'RESET' ); ?>" onclick="reseter('resetvotes', '<?php echo $this->row->id; ?>', 'votes')" />
				</span>
			</td>
		</tr>
		<tr>
			<td>
				<strong><?php echo JText::_( 'REVISED' ); ?></strong>
			</td>
			<td>
				<?php echo $this->row->version;?> <?php echo JText::_( 'TIMES' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<strong><?php echo JText::_( 'CREATED' ); ?></strong>
			</td>
			<td>
				<?php
				if ( $this->row->created == $this->nullDate ) {
					echo JText::_( 'NEW FAQ' );
				} else {
					echo JHTML::_('date',  $this->row->created,  JText::_('DATE_FORMAT_LC2') );
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<strong><?php echo JText::_( 'MODIFIED' ); ?></strong>
			</td>
			<td>
				<?php
					if ( $this->row->modified == $this->nullDate ) {
						echo JText::_( 'NOT MODIFIED' );
					} else {
						echo JHTML::_('date',  $this->row->modified, JText::_('DATE_FORMAT_LC2'));
					}
				?>
			</td>
		</tr>
		</table>
		
		<table width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
			<tr>
				<td>
					<strong><?php echo JText::_( 'CATEGORY' ); ?></strong>
					<span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'CATEGORIES NOTES' );?>">
						<?php echo $infoimage; ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['cid']; ?>
				</td>
			</tr>
		</table>
		
		<table width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
			<tr>
				<td>
					<strong><?php echo JText::_( 'FILES' ); ?></strong>
				</td>
				<td>
					<div id="filelist">
						<?php echo $this->fileselect; ?>
					</div>
					<br /><br />
					<div class="button2-left"><div class="blank"><a class="modal" title="<?php echo JText::_('SELECT'); ?>" href="<?php echo $this->linkfsel; ?>" rel="{handler: 'iframe', size: {x: 650, y: 375}}"><?php echo JText::_('SELECT'); ?></a></div></div>
				</td>
			</tr>
		</table>
		
			<?php
			$title = JText::_( 'DETAILS' );
			echo $this->pane->startPane( 'det-pane' );
			echo $this->pane->startPanel( $title, 'details' );
			echo $this->form->render('details');
			
			$title = JText::_( 'PARAMETERS ADVANCED' );
			echo $this->pane->endPanel();
			echo $this->pane->startPanel( $title, "params-page" );
			echo $this->form->render('params', 'advanced');

			$title = JText::_( 'METADATA INFORMATION' );
			echo $this->pane->endPanel();
			echo $this->pane->startPanel( $title, "metadata-page" );
			echo $this->form->render('meta', 'metadata');

			echo $this->pane->endPanel();
			echo $this->pane->endPane();
			?>
		</td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_quickfaq" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="items" />
<input type="hidden" name="view" value="item" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="version" value="<?php echo $this->row->version; ?>" />
<input type="hidden" name="hits" value="<?php echo $this->row->hits; ?>" />
<input type="hidden" name="minus" value="<?php echo $this->row->minus; ?>" />
<input type="hidden" name="plus" value="<?php echo $this->row->plus; ?>" />
</form>

</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>