<?php
/**
 * @version 1.0 $Id: tags.php 136 2008-08-04 14:33:18Z schlu $
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
 * QuickFAQ Component Tags Controller
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqControllerTags extends QuickfaqController
{
	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra task
		$this->registerTask( 'add'  ,		 	'edit' );
		$this->registerTask( 'apply', 			'save' );
	}

	/**
	 * Logic to save a tag
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

		$model = $this->getModel('tag');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_quickfaq&view=tag&cid[]='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_quickfaq&view=tags';
					break;
			}
			$msg = JText::_( 'TAG SAVED' );

			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();

		} else {

			$msg = JText::_( 'ERROR SAVING TAG' );
			//JError::raiseWarning( 500, $model->getError() );
			$link 	= 'index.php?option=com_quickfaq&view=tag';
		}

		$model->checkin();

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to publish categories
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function publish()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
		} else {
			$model = $this->getModel('tags');

			if(!$model->publish($cid, 1)) {
				JError::raiseError( 500, $model->getError() );
			}

			$total = count( $cid );
			$msg 	= $total.' '.JText::_( 'TAG PUBLISHED');
		}
		
		$this->setRedirect( 'index.php?option=com_quickfaq&view=tags', $msg );
	}

	/**
	 * Logic to unpublish categories
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function unpublish()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM UNPUBLISH' ) );
		} else {
			$model = $this->getModel('tags');

			if(!$model->publish($cid, 0)) {
				JError::raiseError( 500, $model->getError() );
			}

			$total = count( $cid );
			$msg 	= $total.' '.JText::_( 'TAG UNPUBLISHED');
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_quickfaq&view=tags', $msg );
	}

	/**
	 * Logic to delete categories
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function remove()
	{
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM DELETE' ) );
		} else {
			$model = $this->getModel('tags');

			if (!$model->delete($cid)) {
				JError::raiseError(500, JText::_( 'OPERATION FAILED' ));
			}
			
			$msg = count($cid).' '.JText::_( 'TAGS DELETED' );
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_quickfaq&view=tags', $msg );
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

		$tag = & JTable::getInstance('quickfaq_tags', '');
		$tag->bind(JRequest::get('post'));
		$tag->checkin();

		$this->setRedirect( 'index.php?option=com_quickfaq&view=tags' );
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
		JRequest::setVar( 'view', 'tag' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('tag');
		$user	=& JFactory::getUser();

		// Error if checkedout by another administrator
		if ($model->isCheckedOut( $user->get('id') )) {
			$this->setRedirect( 'index.php?option=com_quickfaq&view=tags', JText::_( 'EDITED BY ANOTHER ADMIN' ) );
		}

		$model->checkout( $user->get('id') );

		parent::display();
	}

	/**
	 *  Add new Tag from item screen
	 *
	 */
	function addtag(){
		$name 	= JRequest::getString('name', '');
		$model 	= $this->getModel('tag');
		$model->addtag($name);
	}
}