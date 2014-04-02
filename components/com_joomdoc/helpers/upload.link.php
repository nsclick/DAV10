<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: upload.link.php 608 2008-02-18 13:31:26Z mjaz $
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

if (defined('_DOCMAN_LINK_TRANSFER')) {
    return true;
} else {
    define('_DOCMAN_LINK_TRANSFER' , 1);
}

include_once dirname(__FILE__) . '/upload.link.html.php';

class DMUploadMethod
{
    function fetchMethodForm($uid, $step, $update = false)
    {
        $task = JRequest::getCmd('task');

        switch ($step)
        {
            case 2: // Input the remote URL(Form)
            {
                $lists = array();
                $lists['action']    = DocmanHelper::_taskLink($task, $uid, array('step' => $step + 1), false);
                return HTML_DMUploadMethod::linkFileForm($lists);
            } break;

            case 3: // Create a link
            {
                $url = stripslashes(JRequest::getVar( 'url' , 'http://'));
                $err = DMUploadMethod::linkFileProcess($uid, $step, $url);
                if($err['_error']) {
                	DocmanHelper::_returnTo($task, $err['_errmsg'], '', array("method" => 'link' ,"step" => $step - 1 ,"localfile" => '' , "url" => DOCMAN_Utils::safeEncodeURL($url)));
                }

                $uploaded = DOCMAN_Utils::safeEncodeURL(_DM_DOCUMENT_LINK . $url);

                $catid = $update ? 0 : $uid;
                $docid = $update ? $uid : 0;

                return DocumentsHelper::fetchEditDocumentForm($docid , $uploaded, $catid);
            } break;

            default:
                break;
        }
        return true;
    }

    function linkFileProcess($uid, $step, $url)
    {
        DOCMAN_token::check() or die('Invalid Token');
        
        $_DMUSER = &DocmanFactory::getDmuser();
        $_DOCMAN = &DocmanFactory::getDocman();

        if ($url == '') {
        	return array(
				'_error' => 1,
				'_errmsg'=> _DML_FILENAME_REQUIRED
         	);
        }

    	$path = $_DOCMAN->getCfg('dmpath');

   		//get file validation settings
   		if ($_DMUSER->isSpecial) {
      		$validate = _DM_VALIDATE_ADMIN;
   		} else {
     		if ($_DOCMAN->getCfg('user_all', false)) {
        		$validate = _DM_VALIDATE_USER_ALL ;
      		} else {
           		$validate = _DM_VALIDATE_USER;
       		}
  		}

  		//upload the file
  		$upload = new DOCMAN_FileUpload();
  		$file = $upload->uploadLINK($url , $validate);

        if (!$file) {

            $msg = _DML_ERROR_LINKING . " - " . $upload->_err;

            return array(
				'_error' => 1,
				'_errmsg'=> $msg
         	);
        }

       $msg = _DML_LINKED;

       return array(
			'_error' => 0,
			'_errmsg'=> $msg
         );
    }
}

