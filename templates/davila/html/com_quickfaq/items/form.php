<?php
/**
 * @version 1.0 $Id: form.php 195 2009-01-30 06:33:12Z schlu $
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

<script language="javascript" type="text/javascript">

	Window.onDomReady(function(){
		
		<?php if ($this->user->authorize('com_quickfaq', 'newtags')) : ?>
		var tags = new tagajax('tags', {id:<?php echo $this->item->id ? $this->item->id : 0; ?>, task:'gettags'});
    	tags.fetchscreen();
    	<?php endif; ?>
    	
			document.formvalidator.setHandler('cid',
				function (value) {
					if(value == -1) {
						return true;
					} else {
						timer = new Date();
						time = timer.getTime();
						regexp = new Array();
						regexp[time] = new RegExp('^[1-9]{1}[0-9]{0,}$');
						return regexp[time].test(value);
					}
				}
			);
			
	});
		
	<?php if ($this->user->authorize('com_quickfaq', 'newtags')) : ?>
		
		function addtag()
		{
			var tagname = document.adminForm.tagname.value;
	
			if(tagname == ''){
    			alert('<?php echo JText::_('ENTER TAG', true); ?>');
				return;
			}
	
			var tag = new tagajax();
    		tag.addtag( tagname );  		
    		
    		var tags = new tagajax('tags', {id:<?php echo $this->item->id ? $this->item->id : 0; ?>, task:'gettags'});
    		
    		tags.fetchscreen();
		}

	<?php endif; ?>
		
		function submitbutton( pressbutton ) {


			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			var form = document.adminForm;
			var validator = document.formvalidator;
			var title = $(form.title).getValue();
			title.replace(/\s/g,'');

			if ( title.length==0 ) {
   				alert("<?php echo JText::_( 'ADD TITLE', true ); ?>");
   				validator.handleResponse(false,form.title);
   				//form.title.focus();
   				return false;
			} else if ( form.cid.selectedIndex == -1 ) {
    			alert("<?php echo JText::_( 'SELECT CATEGORY', true ); ?>");
    			//form.cid.focus();
    			return false;
  			} else {
  			<?php
			// JavaScript for extracting editor text
				echo $this->editor->save( 'text' );
			?>
				submitform(pressbutton);

				return true;
			}
		}
</script>

<div id="quickfaq" class="qf_edit">

    <?php if ($this->params->def( 'show_page_title', 1 )) : ?>
    <h1 class="componentheading">
        <?php echo $this->params->get('page_title'); ?>
    </h1>
    <?php endif; ?>

	<form action="<?php echo $this->action ?>" method="post" name="adminForm">
	
		<div class="qf_save_buttons floattext">
            <button type="submit" class="submit" onclick="return submitbutton('save')">
        	    <?php echo JText::_('SAVE') ?>
        	</button>
        	<button type="reset" class="button cancel" onclick="submitbutton('cancel')">
        	    <?php echo JText::_('CANCEL') ?>
        	</button>
        </div>
         
        <br class="clear" />
	
        <fieldset class="qf_fldst_details">

        <legend><?php echo JText::_('GENERAL'); ?></legend>

          <div class="qf_title floattext">
              <label for="title">
                  <?php echo JText::_( 'TITLE' ).':'; ?>
              </label>

              <input class="inputbox required" type="text" id="title" name="title" value="<?php echo $this->escape($this->item->title); ?>" size="65" maxlength="254" />
          </div>

          <div class="qf_category floattext">
          		<label for="cid" class="cid">
                  <?php echo JText::_( 'CATEGORY' ).':';?>
                  <span class="editlinktip hasTip" title="<?php echo JText::_ ( 'NOTES' ); ?>::<?php echo JText::_ ( 'CATEGORIES NOTES' );?>">
						<?php echo JHTML::image ( 'components/com_quickfaq/assets/images/icon-16-hint.png', JText::_ ( 'NOTES' ) ); ?>
				</span>
              </label>
          		<?php
                	echo $this->lists['cid'];
          		?>
          </div>
          <?php if ($this->user->authorize('com_quickfaq', 'state')) : ?>
          <div class="qf_state floattext">
          		<label for="state">
                  <?php echo JText::_( 'STATE' ).':';?>
              </label>
          		<?php
                	echo $this->lists['state'];
          		?>
          </div>
          <?php endif; ?>
          
          </fieldset>
          
        <fieldset class="qf_fldst_tags">

        <legend><?php echo JText::_('TAGS'); ?></legend>

		<div id="tags" class="qf_tags floattext">
        	<?php
        	if (!$this->user->authorize('com_quickfaq', 'newtags')) :
        		$n = count($this->tags);
        		for( $i = 0, $n; $i < $n; $i++ ){
					$tag = $this->tags[$i];
			
					if( ( $i % 4 ) == 0 ){
						if( $i != 0 ){
							echo '</div>';
						}
						echo '<div class="qf_tagline">';
					}
					echo '<span class="qf_tag"><span class="qf_tagidbox"><input type="checkbox" name="tag[]" value="'.$tag->id.'"' . (in_array($tag->id, $this->used) ? 'checked="checked"' : '') . ' /></span>'.$this->escape($tag->name).'</span>';	
				}
				echo '</div>';
			endif; 
			?>
        </div>
        </fieldset>
        
        <fieldset class="description">
      	<legend><?php echo JText::_('ANSWER'); ?></legend>

      		<?php
      			echo $this->editor->display('text', $this->item->text, '100%', '400', '60', '15', array('pagebreak') );
      		?>

    	</fieldset>
    	
    	<fieldset class="qf_fldst_meta">
       	<legend><?php echo JText::_('METADATA INFORMATION'); ?></legend>

            <div class="qf_box_left">
              	<label for="metadesc"><?php echo JText::_( 'META DESCRIPTION' ); ?></label>
          			<textarea class="inputbox" cols="20" rows="5" name="meta_description" id="metadesc" style="width:250px;"><?php echo $this->item->meta_description; ?></textarea>
            </div>

            <div class="qf_box_right">
        			<label for="metakey"><?php echo JText::_( 'META KEYWORDS' ); ?></label>
        			<textarea class="inputbox" cols="20" rows="5" name="meta_keywords" id="metakey" style="width:250px;"><?php echo $this->item->meta_keywords; ?></textarea>
            </div>

      	</fieldset>
<!-- disabled second submit button because ie7 doesn't like the same submit event twice and executed every single one.
	... doubled entries
      <div class="qf_save_buttons floattext">
          <button type="submit" class="submit" onclick="return submitbutton('save')">
        	    <?php echo JText::_('SAVE') ?>
        	</button>
        	<button type="reset" class="button cancel" onclick="submitbutton('cancel')">
        	    <?php echo JText::_('CANCEL') ?>
        	</button>
      </div>
       -->
      <br class="clear" />
        
        <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
    	<input type="hidden" name="referer" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
    	<?php echo JHTML::_( 'form.token' ); ?>
    	<input type="hidden" name="task" value="" />
        
	</form>
</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>