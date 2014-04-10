<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: upload.php 1 2009-09-01 13:31:26Z j.trumpes $
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

include_once dirname ( __FILE__ ) . '/upload.html.php';

$_DOCMAN = &DocmanFactory::getDocman ();

require_once ($_DOCMAN->getPath ( 'classes', 'mambots' ));
require_once ($_DOCMAN->getPath ( 'classes', 'file' ));

class UploadHelper {	
	function fetchDocumentUploadForm($uid, $step, $method, $update) {
		$_DMUSER = &DocmanFactory::getDmuser(); 
		
		//preform permission check
		if ($_DMUSER->canPreformTask ( null, 'Upload' )) {
			DocmanHelper::_returnTo ( '', _DML_NOLOG_UPLOAD );
		}
		//check to see if method is available
		if (! UploadHelper::methodAvailable ( $method )) {
			DocmanHelper::_returnTo ( 'doc_details', _DML_UPLOADMETHOD, array ('step' => 1 ) );
		}
		switch ($step) {
			case '1' :
				return UploadHelper::fetchMethodsForm ( $uid, $step, $method );
				break;
			case '2' :
			case '3' :
				return UploadHelper::fetchMethodForm ( $uid, $step, $method, $update );				
				break;
			
			default :
				break;
		}
	}
	
	function fetchMethodsForm($uid, $step, $method) {
		$task = JRequest::getCmd('task');
		
		// Prompt with a list of upload methods
		$lists = array ();
		$lists ['methods'] = dmHTML::uploadSelectList ();
		$lists ['action'] = DocmanHelper::_taskLink ( $task, $uid, array ('step' => $step + 1 ), false );
		
		return HTML_DMUpload::uploadMethodsForm ( $lists );
	}
	
	function fetchMethodForm($uid, $step, $method, $update) {
		$_DOCMAN = &DocmanFactory::getDocman();
		$task = JRequest::getCmd('task');
		$method_file = $_DOCMAN->getPath ( 'helpers', 'upload.' . $method );
		if (! file_exists ( $method_file )) {
			DocmanHelper::_returnTo ( $task, "Protocol " . $method . " not supported", '', array ('step' => 1 ) );
		}
		require_once ($method_file);
		
		return DMUploadMethod::fetchMethodForm ( $uid, $step, $update );
	}
	
	function methodAvailable($method) {
		$_DOCMAN = &DocmanFactory::getDocman();
		$_DMUSER = &DocmanFactory::getDmuser();
		
		if ($_DMUSER->isSpecial || is_null ( $method )) {
			return true;
		}
		
		$methods = $_DOCMAN->getCfg ( 'methods', array ('http' ) );
		if (! in_array ( $method, $methods )) {
			return false;
		}
		return true;
	}
}