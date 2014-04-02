<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: upload.html.php 608 2008-02-18 13:31:26Z mjaz $
 * @package JoomDOC
 * @copyright (C) 2009 Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

if (defined('_DOCMAN_HTML_UPLOAD')) {
    return;
} else {
    define('_DOCMAN_HTML_UPLOAD', 1);
}

class HTML_DMUpload
{
    function uploadMethodsForm($lists)
    {
        ob_start();
        ?>
	   <form action="<?php echo $lists['action'];?>" method="post" id="dm_frmupload" class="dm_form">
       <fieldset class="input">
       		<p><label for="method"><?php echo _DML_UPLOADMETHOD;?></label><br />
			<?php echo $lists['methods'];?></p>
       </fieldset>
       <fieldset class="dm_button">
        	<p><input name="submit" class="button" value="<?php echo _DML_NEXT;?>" type="submit" /></p>
       </fieldset>
    	</form>
		<?php
 		$html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    function updateDocumentForm($list, $links, $paths, $data)
    {
    	$action = _taskLink('doc_update_process', $data->id);

		ob_start();
        ?>
       <form action="<?php echo $action ?>" method="post" enctype="multipart/form-data" id="dm_frmupdate" class="dm_form" >
       <fieldset class="input">
       		<p>
       			<label for="upload"><?php echo _DML_SELECTFILE;?></label><br />
	   			<input id="upload" name="upload" type="file" />
	   		</p>
       </fieldset>
	   <fieldset class="dm_button">
	   		<p>
	   			<input name="submit" class="button" value="<?php echo _DML_UPLOAD ?>" type="submit" />
	   		</p>
	   </fieldset>
       <?php echo DOCMAN_token::render();?>
 	   </form>
        <?php
 		$html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
