<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: downloads.php 1 2009-09-01 13:31:26Z j.trumpes $
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

if (defined('_DOCMAN_HTML_DOWNLOAD')) {
    return;
} else {
    define('_DOCMAN_HTML_DOWNLOAD', 1);
}

class DownloadsHelper {
	
	function fetchDocumentLicenseForm($uid, $inline = 0) {
		$doc = new DOCMAN_Document ( $uid );
		
		$data = $doc->getDataObject ();
		
		$gid = JRequest::getInt('gid');
		
		$action = DocmanHelper::_taskLink('license_result', $gid , array('bid' => $data->id));

        ob_start();
        ?>
		<form action="<?php echo $action;?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="inline" value="<?php echo $inline?>" />
			<input type="radio" name="agree" value="0" checked /><?php echo _DML_DONT_AGREE;?>
			<input type="radio" name="agree" value="1" /><?php echo _DML_AGREE;?>
			<input name="submit" value="<?php echo _DML_PROCEED;?>" type="submit" />
		</form>

		<?php

		$html = ob_get_contents();
        ob_end_clean();

        return $html;
	
	}
	
	function licenseDocumentProcess($uid) {
		// this needs to use REQUEST , so onBeforeDownload plugins can use redirect
		$accepted = JRequest::getInt('agree');
		$inline = JRequest::getInt('inline');
		$doc = new DOCMAN_Document ( $uid );
		
		if ($accepted) {
			DownloadsHelper::download ( $doc, $inline );
		} else {
			DocmanHelper::_returnTo ( 'view_cat', _DML_YOU_MUST, $doc->getData ( 'catid' ) );
		}
	}
	function download(&$doc, $inline = false) {
		global $mainframe;
		
		$docman = &DocmanFactory::getDocman();
		$dmuser = &DocmanFactory::getDmuser();
		$db = &JFactory::getDBO();
		$config = &JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset'); 

		require_once ($docman->getPath ( 'classes', 'file' ));
		
		$data = &$doc->getDataObject ();
		
		/* ------------------------------ *
	 *   CORE AUTHORIZATIONS          *
	 * ------------------------------ */
		
		// if the user is not authorized to download this document, redirect
		if (! $dmuser->canDownload ( $doc->getDBObject () )) {
			//echo "1"; exit;
			//DocmanHelper::_returnTo ( 'cat_view', _DML_NOLOG_DOWNLOAD, $data->catid );
			$mainframe->redirect( "index.php" );
		}
		
		// If the document is not approved, redirect
		if (! $data->approved and ! $dmuser->canApprove ()) {
			//echo "2"; exit;
			//DocmanHelper::_returnTo ( 'cat_view', _DML_NOAPPROVED_DOWNLOAD, $data->catid );
			$mainframe->redirect( "index.php" );
		}
		
		// If the document is not published, redirect
		if (! $data->published and ! $dmuser->canPublish ()) {
			//echo "3"; exit;
			//DocmanHelper::_returnTo ( 'cat_view', _DML_NOPUBLISHED_DOWNLOAD, $data->catid );
			$mainframe->redirect( "index.php" );
		}
		
		// if the document is checked out, redirect
		if ($data->checked_out && $dmuser->userid != $data->checked_out) {
			//echo "4"; exit;
			//DocmanHelper::_returnTo ( 'cat_view', _DML_NOTDOWN, $data->catid );
			$mainframe->redirect( "index.php" );
		}
		
		// If the remote host is not allowed, show anti-leech message and die.
		if (! DOCMAN_Utils::checkDomainAuthorization ()) {
			//echo "5"; exit;
			$from_url = parse_url ( $_SERVER ['HTTP_REFERER'] );
			$from_host = trim ( $from_url ['host'] );
			
			//DocmanHelper::_returnTo ( 'cat_view', _DML_ANTILEECH_ACTIVE . " (" . $from_host . ")", $data->catid );
			$mainframe->redirect( "index.php" );
		}
		
		/* ------------------------------ *
	 *   GET FILE 					  *
	 * ------------------------------ */
	 
	 	if( ereg("Link: ",$data->dmfilename) ) :
			$mainframe->redirect( str_replace( "Link: ", "", $data->dmfilename ) );
		endif;
		
		$file = new DOCMAN_File ( $data->dmfilename, $docman->getCfg ( 'dmpath' ) );
		
		// If the file doesn't exist, redirect
		if (! $file->exists ()) {
			DocmanHelper::_returnTo ( 'cat_view', _DML_FILE_UNAVAILABLE, $data->catid );
		}
		
		/* ------------------------------ *
	 *   MAMBOT - Setup All Mambots   *
	 * ------------------------------ */
		
		$doc_dbo = $doc->getDBObject (); //Fix for PHP 5
		

		$logbot = new DOCMAN_mambot ( 'onLog' );
		$prebot = new DOCMAN_mambot ( 'onBeforeDownload' );
		$postbot = new DOCMAN_mambot ( 'onAfterDownload' );
		$logbot->setParm ( 'document', $doc_dbo );
		$logbot->setParm ( 'file', $file );
		$logbot->setParm ( 'user', $dmuser );
		$logbot->copyParm ( 'process', 'download' );
		$prebot->setParmArray ( $logbot->getParm () ); // Copy the parms over
		$postbot->setParmArray ( $logbot->getParm () );
		
		/* ------------------------------ *
	 *   MAMBOT - PREDOWNLOAD         *
	 * ------------------------------ */
		$prebot->trigger ();
		if ($prebot->getError ()) {
			$logbot->copyParm ( 'msg', $prebot->getErrorMsg () );
			$logbot->copyParm ( 'status', 'LOG_ERROR' );
			$logbot->trigger ();
			DocmanHelper::_returnTo ( 'cat_view', $prebot->getErrorMsg () );
		}
		
		// let's increment the counter
		$dbobject = $doc->getDBObject ();
		$dbobject->incrementCounter ();
		
		// place an entry in the log
		if ($docman->getCfg ( 'log' )) {
			require_once ($docman->getPath ( 'classes', 'jbrowser' ));
			$browser = & JBrowser::getInstance ( $_SERVER ['HTTP_USER_AGENT'] );
			
			$now = date ( "Y-m-d H:i:s", time ( "Y-m-d g:i:s" ) + $tzoffset * 60 * 60 );
			$remote_ip = $_SERVER ['REMOTE_ADDR'];
			$row_log = new mosDMLog ( $db );
			$row_log->log_docid = $data->id;
			$row_log->log_ip = $remote_ip;
			$row_log->log_datetime = $now;
			$row_log->log_user = $dmuser->userid;
			$row_log->log_browser = $browser->getBrowser ();
			$row_log->log_os = $browser->getPlatform ();
			if (! $row_log->store ()) {
				exit ();
			}
		}
		$logbot->copyParm ( array ('msg' => 'Download Complete', 'status' => 'LOG_OK' ) );
		$logbot->trigger ();
		$file->download ( $inline );
		
		/* ------------------------------ *
	 *   MAMBOT - PostDownload        *
	 * Currently - we die and no out  *
	 * ------------------------------ */
		$postbot->trigger ();
		/* if( $postbot->getError() ){
	*		$logbot->copyParm( array(	'msg'	=> $postbot->getErrorMsg() ,
	*			 			  			'status'=> 'LOG_ERROR'
	*								)
	*						);
	*		$logbot->trigger();
	*		DocmanHelper::_returnTo('cat_view',$postbot->getErrorMsg() );
	*}
	*/
		
		die (); // REQUIRED
	}

}