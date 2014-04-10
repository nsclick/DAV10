<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: upload.http.html.php 608 2008-02-18 13:31:26Z mjaz $
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

if (defined('_DOCMAN_METHOD_HTTP_HTML')) {
    return;
} else {
    define('_DOCMAN_METHOD_HTTP_HTML', 1);
}

class HTML_DMUploadMethod
{
    function uploadFileForm($lists)
    {
        global $mosConfig_live_site;
        $progressImg = $mosConfig_live_site.'/administrator/components/com_joomdoc/images/dm_progress.gif';
        ob_start();
        ?>
		<form action="<?php echo $lists['action'] ;?>" method="post" enctype="multipart/form-data" id="dm_frmupload" class="dm_form">
		<fieldset class="input">
       		<p><div id="progress" style="display:none;"><img style="border:1px solid black;" src="<?php echo $progressImg?>" alt="Upload Progress" />&nbsp;<?php echo _DML_ISUPLOADING?></div>
            <label for="upload"><?php echo _DML_SELECTFILE;?></label><br />
	   		<input id="upload" name="upload" type="file" name="file" />

            </p>
       	</fieldset>
	   	<fieldset class="dm_button">
	   		<input name="submit" id="dm_btn_back"   class="button" value="<?php echo _DML_BACK;?>" onclick="window.history.back()" type="button" >
	   		<input name="submit" id="dm_btn_submit" class="button" value="<?php echo _DML_UPLOAD;?>" type="submit" onclick="document.getElementById('progress').style.display = 'block';" />
	   	</fieldset>
	   	<input type="hidden" name="method" value="http" />
        <?php echo DOCMAN_token::render();?>
		</form>

		<?php
		$html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}

