<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: upload.link.html.php 608 2008-02-18 13:31:26Z mjaz $
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

if (defined('_DOCMAN_METHOD_LINK_HTML')) {
    return;
} else {
    define('_DOCMAN_METHOD_LINK_HTML', 1);
}

class HTML_DMUploadMethod
{
    function linkFileForm($lists)
    {
        ob_start();
        ?>
    	<form action="<?php echo $lists['action'] ;?>" method="post" id="dm_frmupload" class="dm_form">
    	<fieldset class="input">
      		<p><label for="url"><?php echo _DML_REMOTEURL;?></label><br />
	   		<input name="url" type="text" id="url" value="<?php /*echo $parms['url'];*/ ?>" />
			<?php echo DOCMAN_Utils::mosToolTip(_DML_LINKURLTT . '</span>' , _DML_REMOTEURL . ':');?></p>
		</fieldset>
	   	<fieldset class="dm_button">
	   		<input name="submit" id="dm_btn_back"   class="button" value="<?php echo _DML_BACK;?>" onclick="window.history.back()" type="button" >
			<input name="submit" id="dm_btn_submit" class="button" value="<?php echo _DML_LINK;?>" type="submit" />
       	 </fieldset>
       	 <input type="hidden" name="method" value="link" />
         <?php echo DOCMAN_token::render();?>
       	</form>
   		<?php
		$html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}

