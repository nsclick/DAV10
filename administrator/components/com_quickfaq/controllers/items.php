<?php
/**
 * @version 1.0 $Id: items.php 192 2009-01-09 01:02:45Z schlu $
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
 * QuickFAQ Component Item Controller
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqControllerItems extends QuickfaqController
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
	 * Logic to save an item
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

		$model = $this->getModel('item');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_quickfaq&view=item&cid[]='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_quickfaq&view=items';
					break;
			}
			$msg = JText::_( 'ITEM SAVED' );

			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();

		} else {
			$msg = JText::_( 'ERROR SAVING ITEM' );
			JError::raiseError( 500, $model->getError() );
			$link 	= 'index.php?option=com_quickfaq&view=item';
		}

		$model->checkin();

		$this->setRedirect($link, $msg);
	}
	
	/**
	 * Logic to orderup an item
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$model = $this->getModel('items');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_quickfaq&view=items');
	}

	/**
	 * Logic to orderdown an item
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$model = $this->getModel('items');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_quickfaq&view=items');
	}

	/**
	 * Logic to mass ordering items
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

		$model = $this->getModel('items');
		if(!$model->saveorder($cid, $order)) {
			$msg = '';
			JError::raiseError(500, $model->getError());
		} else {
			$msg = JText::_( 'NEW ORDERING SAVED' );
		}

		$this->setRedirect( 'index.php?option=com_quickfaq&view=items', $msg );
	}

	/**
	 * Logic to change the state
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function setitemstate()
	{
		$id 	= JRequest::getInt( 'id', 0 );
		$state 	= JRequest::getVar( 'state', 0 );

		$model = $this->getModel('items');

		if(!$model->setitemstate($id, $state)) {
			JError::raiseError(500, $model->getError());
		}

		if ( $state == 1 ) {
			$img = 'tick.png';
			$alt = JText::_( 'Published' );
		} else if ( $state == 0 ) {
			$img = 'publish_x.png';
			$alt = JText::_( 'Unpublished' );
		} else if ( $state == -1 ) {
			$img = 'disabled.png';
			$alt = JText::_( 'Archived' );
		} else if ( $state == -2 ) {
			$img = 'publish_r.png';
			$alt = JText::_( 'PENDING' );
		} else if ( $state == -3 ) {
			$img = 'publish_y.png';
			$alt = JText::_( 'OPEN QUESTION' );
		} else if ( $state == -4 ) {
			$img = 'publish_g.png';
			$alt = JText::_( 'IN PROGRESS' );
		}
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		echo '<img src="images/'.$img.'" width="16" height="16" border="0" alt="'.$alt.'" />';
	}

	/**
	 * Logic to delete items
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

		$model = $this->getModel('items');

		if(!$model->delete($cid)) {
			$msg = '';
			JError::raiseError(500, $model->getError());
		} else {
			$total 	= count( $cid );
			$msg 	= $total.' '.JText::_('ITEMS DELETED');

			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_quickfaq&view=items', $msg );
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

		$item = & JTable::getInstance('quickfaq_items', '');
		$item->bind(JRequest::get('post'));
		$item->checkin();

		$this->setRedirect( 'index.php?option=com_quickfaq&view=items' );
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
		JRequest::setVar( 'view', 'item' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('item');
		$user	=& JFactory::getUser();

		// Error if checkedout by another administrator
		if ($model->isCheckedOut( $user->get('id') )) {
			$this->setRedirect( 'index.php?option=com_quickfaq&view=items', JText::_( 'EDITED BY ANOTHER ADMIN' ) );
		}

		$model->checkout( $user->get('id') );

		parent::display();
	}

	/**
	 * Method to reset hits
	 * 
	 * @since 1.0
	 */
	function resethits()
	{
		$id		= JRequest::getInt( 'id', 0 );
		$model = $this->getModel('item');

		$model->resetHits($id);
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		echo 0;
	}

	/**
	 * Method to reset votes
	 * 
	 * @since 1.0
	 */
	function resetvotes()
	{
		$id		= JRequest::getInt( 'id', 0 );
		$model = $this->getModel('item');

		$model->resetVotes($id);
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		echo '+ 0 | - 0';
	}

	/**
	 * Method to fetch the tags form
	 * 
	 * @since 1.0
	 */
	function gettags()
	{
		$id 	= JRequest::getInt('id', 0);
		$model 	= $this->getModel('item');
		$tags 	= $model->gettags();

		$used = null;

		if ($id) {
			$used 	= $model->getusedtags($id);
		}
		if(!is_array($used)){
			$used = array();
		}

		$rsp = '<div class="qf_tagbox">';
		$n = count($tags);
		for( $i = 0, $n; $i < $n; $i++ ){
			$tag = $tags[$i];

			if( ( $i % 5 ) == 0 ){
				if( $i != 0 ){
					$rsp .= '</div>';
				}
				$rsp .=  '<div class="qf_tagline">';
			}
			$rsp .=  '<span class="qf_tag"><span class="qf_tagidbox"><input type="checkbox" name="tag[]" value="'.$tag->id.'"' . (in_array($tag->id, $used) ? 'checked="checked"' : '') . ' /></span>'.$tag->name.'</span>';
		}
		$rsp .= '</div>';
		$rsp .= '</div>';
		$rsp .= '<div class="clear"></div>';
		$rsp .= '<div class="qf_addtag">';
		$rsp .= '<label for="addtags">'.JText::_('ADD TAG').'</label>';
		$rsp .= '<input type="text" id="tagname" class="inputbox" size="30" />';
		$rsp .=	'<input type="button" class="button" value="'.JText::_('ADD').'" onclick="addtag()" />';
		$rsp .= '</div>';

		echo $rsp;
	}

	/**
	 * Method to fetch the votes
	 * 
	 * @since 1.0
	 */
	function getvotes()
	{
		$id 	= JRequest::getInt('id', 0);
		$model 	= $this->getModel('item');
		$votes 	= $model->getvotes($id);

		if ($votes) {
			echo '+ '.(int)$votes[0]->plus.' | - '.(int)$votes[0]->minus;
		} else {
			echo '+ 0 | - 0';
		}
	}

	/**
	 * Method to get hits
	 * 
	 * @since 1.0
	 */
	function gethits()
	{
		$id 	= JRequest::getInt('id', 0);
		$model 	= $this->getModel('item');
		$hits 	= $model->gethits($id);

		if ($hits) {
			echo $hits;
		} else {
			echo 0;
		}
	}
}