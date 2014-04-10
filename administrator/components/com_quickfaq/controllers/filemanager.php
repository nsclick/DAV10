<?php
/**
 * @version 1.0 $Id: filemanager.php 136 2008-08-04 14:33:18Z schlu $
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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * QuickFAQ Component Files Controller
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqControllerFilemanager extends QuickfaqController
{
	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Upload a file
	 *
	 * @since 1.0
	 */
	function upload()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$file 		= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$format		= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return		= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$err		= null;
		
		jimport('joomla.utilities.date');

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name'])) {

			$path = COM_QUICKFAQ_FILEPATH.DS;

			//sanitize filename further and make unique
			$filename = quickfaq_upload::sanitize($path, $file['name']);
			$filepath = JPath::clean(COM_QUICKFAQ_FILEPATH.DS.strtolower($filename));
			
			if (!quickfaq_upload::check( $file, $err )) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('com_quickfaq.error.php');
					$log->addEntry(array('comment' => 'Invalid: '.$filepath.': '.$err));
					header('HTTP/1.0 415 Unsupported Media Type');
					die('Error. Unsupported Media Type!');
				} else {
					JError::raiseNotice(100, JText::_($err));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return));
					}
					return;
				}
			}
			
			if (!JFile::upload($file['tmp_name'], $filepath)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('com_quickfaq.error.php');
					$log->addEntry(array('comment' => 'Cannot upload: '.$filepath));
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				} else {
					JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return));
					}
					return;
				}
			} else {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance();
					$log->addEntry(array('comment' => $filepath));
					
					$db 	= &JFactory::getDBO();
					$user	= &JFactory::getUser();
					$config = &JFactory::getConfig();

					$tzoffset = $config->getValue('config.offset');
					$date = & JFactory::getDate( 'now', -$tzoffset);

					$obj = new stdClass();
					$obj->filename 			= $filename;
					$obj->altname 			= $file['name'];
					$obj->hits				= 0;
					$obj->uploaded			= $date->toMySQL();
					$obj->uploaded_by		= $user->get('id');

					$db->insertObject('#__quickfaq_files', $obj);
					
					jexit('Upload complete');
				} else {

					$db 	= &JFactory::getDBO();
					$user	= &JFactory::getUser();
					$config = &JFactory::getConfig();

					$tzoffset = $config->getValue('config.offset');
					$date = & JFactory::getDate( 'now', -$tzoffset);

					$obj = new stdClass();
					$obj->filename 			= $filename;
					$obj->altname 			= $file['name'];
					$obj->hits				= 0;
					$obj->uploaded			= $date->toMySQL();
					$obj->uploaded_by		= $user->get('id');

					$db->insertObject('#__quickfaq_files', $obj);

					$mainframe->enqueueMessage(JText::_('Upload complete'));
					
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return));
					}
					return;
				}
			}
		}
		$mainframe->redirect(base64_decode($return));
	}
	
	function ftpValidate()
	{
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
	}

	/**
	 * Logic to create the view for the edit categoryscreen
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function edit( )
	{	
		JRequest::setVar( 'view', 'file' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('file');
		$user	=& JFactory::getUser();

		// Error if checkedout by another administrator
		if ($model->isCheckedOut( $user->get('id') )) {
			$this->setRedirect( 'index.php?option=com_quickfaq&controller=filemanager&task=edit', JText::_( 'EDITED BY ANOTHER ADMIN' ) );
		}

		$model->checkout( $user->get('id') );

		parent::display();
	}
	
	/**
	 * Logic to delete files
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM DELETE' ) );
		}

		$model = $this->getModel('filemanager');

		if (!$model->delete($cid)) {
			$msg = '';
			JError::raiseError(500, JText::_( 'OPERATION FAILED' ));
		} else {
			$msg = count($cid).' '.JText::_( 'FILES DELETED' );
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}
				
		$this->setRedirect( 'index.php?option=com_quickfaq&view=filemanager', $msg );
	}
	
	/**
	 * Logic to save the altname
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );

		$model = $this->getModel('file');

		if ($model->store($post)) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_quickfaq&controller=filemanager&task=edit&cid[]='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_quickfaq&view=filemanager';
					break;
			}
			$msg = JText::_( 'FILE SAVED' );

			$model->checkin();
			
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();

		} else {

			$msg = JText::_( 'ERROR SAVING FILENAME' );
			JError::raiseError( 500, $model->getError() );
			$link 	= 'index.php?option=com_quickfaq&view=filemanager';
		}

		$this->setRedirect($link, $msg);
	}
	
	/**
	 * logic for cancel an action
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$file = & JTable::getInstance('quickfaq_files', '');
		$file->bind(JRequest::get('post'));
		$file->checkin();

		$this->setRedirect( 'index.php?option=com_quickfaq&view=filemanager' );
	}
}