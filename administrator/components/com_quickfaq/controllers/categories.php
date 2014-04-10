<?php
/**
 * @version 1.0 $Id: categories.php 136 2008-08-04 14:33:18Z schlu $
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
 * QuickFAQ Component Categories Controller
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqControllerCategories extends QuickfaqController
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
		$this->registerTask( 'accesspublic', 	'access' );
		$this->registerTask( 'accessregistered','access' );
		$this->registerTask( 'accessspecial', 	'access' );
	}

	/**
	 * Logic to save a category
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
		$post['text'] = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$model = $this->getModel('category');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_quickfaq&view=category&cid[]='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_quickfaq&view=categories';
					break;
			}
			$msg = JText::_( 'CATEGORY SAVED' );
			
			//Take care of access levels and state
			$categoriesmodel = & $this->getModel('categories');
			$categoriesmodel->access($model->get('id'), $model->get('access'));
			
			$pubid = array();
			$pubid[] = $model->get('id');
			if($model->get('published') == 1) {
				$categoriesmodel->publish($pubid, 1);
			} else {
				$categoriesmodel->publish($pubid, 0);
			}
			
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();

		} else {

			$msg 	= JText::_( 'ERROR SAVING CATEGORY' );
			//JError::raiseError( 500, $model->getError() );
			$link 	= 'index.php?option=com_quickfaq&view=category';
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
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
		} else {

			$model = $this->getModel('categories');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'CATEGORY PUBLISHED');
		
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_quickfaq&view=categories', $msg );
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
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM UNPUBLISH' ) );
		} else {

			$model = $this->getModel('categories');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'CATEGORY UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_quickfaq&view=categories', $msg );
	}

	/**
	 * Logic to orderup a category
	 *
	 * @access public
	 * @return void;
	 * @since 1.0
	 */
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$model = $this->getModel('categories');
		$model->move(-1);
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();
		
		$this->setRedirect( 'index.php?option=com_quickfaq&view=categories');
	}

	/**
	 * Logic to orderdown a category
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$model = $this->getModel('categories');
		$model->move(1);
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		$this->setRedirect( 'index.php?option=com_quickfaq&view=categories');
	}

	/**
	 * Logic to mass ordering categories
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(0), 'post', 'array' );

		$model = $this->getModel('categories');
		if(!$model->saveorder($cid, $order)) {
			$msg = '';
			JError::raiseWarning(500, $model->getError());
		}
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		$msg = 'NEW ORDERING SAVED';
		$this->setRedirect( 'index.php?option=com_quickfaq&view=categories', $msg );
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
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM DELETE' ) );
		} else {

			$model = $this->getModel('categories');

			$msg = $model->delete($cid);

			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_quickfaq&view=categories', $msg );
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
		
		$category = & JTable::getInstance('quickfaq_categories', '');
		$category->bind(JRequest::get('post'));
		$category->checkin();

		$this->setRedirect( 'index.php?option=com_quickfaq&view=categories' );
	}

	/**
	 * Logic to set the category access level
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function access( )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id			= (int)$cid[0];
		$task		= JRequest::getVar( 'task' );

		if ($task == 'accesspublic') {
			$access = 0;
		} elseif ($task == 'accessregistered') {
			$access = 1;
		} else {
			$access = 2;
		}

		$model = $this->getModel('categories');
		
		if(!$model->access( $id, $access )) {
			JError::raiseError(500, $model->getError());
		} else {
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}
		
		$this->setRedirect('index.php?option=com_quickfaq&view=categories' );
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
		// Check for request forgeries
		//JRequest::checkToken() or jexit( 'Invalid Token' );
		
		JRequest::setVar( 'view', 'category' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('category');
		$user	=& JFactory::getUser();

		// Error if checkedout by another administrator
		if ($model->isCheckedOut( $user->get('id') )) {
			$this->setRedirect( 'index.php?option=com_quickfaq&view=categories', JText::_( 'EDITED BY ANOTHER ADMIN' ) );
		}

		$model->checkout( $user->get('id') );
		
		parent::display();
	}
}