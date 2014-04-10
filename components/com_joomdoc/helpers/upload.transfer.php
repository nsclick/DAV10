<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: upload.transfer.php 608 2008-02-18 13:31:26Z mjaz $
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

if (defined('_DOCMAN_METHOD_TRANSFER')) {
    return true;
} else {
    define('_DOCMAN_METHOD_TRANSFER' , 1);
}

include_once dirname(__FILE__) . '/upload.transfer.html.php';

class DMUploadMethod
{
    function fetchMethodForm($uid, $step, $update = false)
    {
        $task = JRequest::getCmd('task');

        switch ($step)
        {
            case 2: // Input the filename (Form)
            {
                $lists = array();
                $lists['action']    = DocmanHelper::_taskLink($task, $uid, array('step' => $step + 1), false);
                return HTML_DMUploadMethod::transferFileForm($lists);
            } break;

            case 3: // Copy the file and edit the document
            {
                $url   = stripslashes(JRequest::getVar( 'url' , 'http://'));
                $file  = stripslashes(JRequest::getVar( 'localfile' , ''));
                $err = DMUploadMethod::transferFileProcess($uid, $step, $url, $file);
                if($err['_error']) {
                	DocmanHelper::_returnTo($task, $err['_errmsg'], '', array("method" => 'transfer' , "step" => $step - 1 ,"localfile" => $file , "url" => DOCMAN_Utils::safeEncodeURL($url)));
                }

                $catid = $update ? 0 : $uid;
                $docid = $update ? $uid : 0;

                return DocumentsHelper::fetchEditDocumentForm($docid , $file->name, $catid);
            } break;

            default: break;
        }
        return true;
    }

    function transferFileProcess($uid, $step, $url, &$file)
    {
        DOCMAN_token::check() or die('Invalid Token');
        $_DMUSER = &DocmanFactory::getDmuser();
        $_DOCMAN = &DocmanFactory::getDocman();



        if ($file == '') {
            return array(
				'_error' => 1,
				'_errmsg'=> _DML_FILENAME_REQUIRED
         	);
        }

        /* ------------------------------ *
     	*   MAMBOT - Setup All Mambots   *
     	* ------------------------------ */
        $logbot = new DOCMAN_mambot('onLog');
        $prebot = new DOCMAN_mambot('onBeforeUpload');
        $postbot = new DOCMAN_mambot('onAfterUpload');
        $logbot->setParm('filename' , $file);
        $logbot->setParm('user' , $_DMUSER);
        $logbot->copyParm('process' , 'upload');
        $prebot->setParmArray ($logbot->getParm()); // Copy the parms over
        $postbot->setParmArray($logbot->getParm());

        /* ------------------------------ *
     	*   Pre-upload                    *
     	* ------------------------------ */
        $prebot->trigger();
        if ($prebot->getError()) {
            $logbot->setParm('msg' , $prebot->getErrorMsg());
            $logbot->copyParm('status' , 'LOG_ERROR');
            $logbot->trigger();

            return array(
				'_error' => 1,
				'_errmsg'=> $prebot->getErrorMsg()
         	);
        }

		/* ------------------------------ *
     	*   Upload                        *
     	* ------------------------------ */

        $path = $_DOCMAN->getCfg('dmpath').'/';

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
  		$file = $upload->uploadURL($url, $path, $validate, $file);

      /* -------------------------------- *
	 	 *    Post-upload                   *
	 	 * -------------------------------- */

        if (! $file) {
            $msg = _DML_ERROR_UPLOADING . " - " . $upload->_err;
            $logbot->setParm('msg' , $msg);
             $logbot->setParm('file', $url);
            $logbot->copyParm('status' , 'LOG_ERROR');
            $logbot->trigger();

             return array(
				'_error' => 1,
				'_errmsg'=> $msg
         	);
        }

       	$msg = "&quot;" . $file->name . "&quot; " . _DML_UPLOADED;

       	$logbot->copyParm(array('msg' => $msg ,'status' => 'LOG_OK'));
       	$logbot->trigger();

       	$postbot->setParm('file', $file);
       	$postbot->trigger();

      	if ($postbot->getError()) {
          	$logbot->setParm('msg' , $postbot->getErrorMsg());
          	$logbot->copyParm('status' , 'LOG_ERROR');
           	$logbot->trigger();

          	return array(
				'_error' => 1,
				'_errmsg'=> $postbot->getErrorMsg()
         	);
        }

       	return array(
			'_error' => 0,
			'_errmsg'=> $msg
        );
    }
}

