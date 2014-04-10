<?php
/**
 * @version 1.0 $Id: category.php 136 2008-08-04 14:33:18Z schlu $
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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * QuickFAQ Component Category Model
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since		1.0
 */
class QuickfaqModelCategory extends JModel
{
	/**
	 * Category data
	 *
	 * @var object
	 */
	var $_category = null;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int category identifier
	 */
	function setId($id)
	{
		// Set category id and wipe data
		$this->_id	    	= $id;
		$this->_category	= null;
	}
	
	/**
	 * Overridden get method to get properties from the category
	 *
	 * @access	public
	 * @param	string	$property	The name of the property
	 * @param	mixed	$value		The value of the property to set
	 * @return 	mixed 				The value of the property
	 * @since	1.0
	 */
	function get($property, $default=null)
	{
		if ($this->_loadCategory()) {
			if(isset($this->_category->$property)) {
				return $this->_category->$property;
			}
		}
		return $default;
	}

	/**
	 * Method to get category data
	 *
	 * @access	public
	 * @return	array
	 * @since	1.0
	 */
	function &getCategory()
	{
		if ($this->_loadCategory())
		{

		}
		else  $this->_initCategory();

		return $this->_category;
	}

	/**
	 * Method to load category data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function _loadCategory()
	{
		// Lets load the category if it doesn't already exist
		if (empty($this->_category))
		{
			$query = 'SELECT *'
					. ' FROM #__quickfaq_categories'
					. ' WHERE id = '.$this->_id
					;
			$this->_db->setQuery($query);
			$this->_category = $this->_db->loadObject();

			return (boolean) $this->_category;
		}
		return true;
	}

	/**
	 * Method to initialise the category data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function _initCategory()
	{
		// Lets load the category if it doesn't already exist
		if (empty($this->_category))
		{
			$category = new stdClass();
			$category->id					= 0;
			$category->parent_id			= 0;
			$category->title				= null;
			$category->alias				= null;
			$category->text					= null;
			$category->meta_description		= null;
			$category->meta_keywords		= null;
			$category->published			= 1;
			$category->image				= JText::_('SELECTIMAGE');
			$category->access				= 0;
			$this->_category					= $category;
			return (boolean) $this->_category;
		}
		return true;
	}

	/**
	 * Method to checkin/unlock the category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$category = & JTable::getInstance('quickfaq_categories', '');
			return $category->checkin($this->_id);
		}
		return false;
	}

	/**
	 * Method to checkout/lock the category
	 *
	 * @access	public
	 * @param	int	$uid	User ID of the user checking the item out
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the category with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$category = & JTable::getInstance('quickfaq_categories', '');
			if(!$category->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}

	/**
	 * Tests if the category is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	1.0
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadCategory())
		{
			if ($uid) {
				return ($this->_category->checked_out && $this->_category->checked_out != $uid);
			} else {
				return $this->_category->checked_out;
			}
		} elseif ($this->_id < 1) {
			return false;
		} else {
			JError::raiseWarning( 0, 'UNABLE LOAD DATA');
			return false;
		}
	}

	/**
	 * Method to store the category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function store($data)
	{
		$category  =& $this->getTable('quickfaq_categories', '');

		// bind it to the table
		if (!$category->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		if (!$category->id) {
			$category->ordering = $category->getNextOrder();
		}
		
		// Make sure the data is valid
		if (!$category->check()) {
			$this->setError($category->getError());
			return false;
		}

		// Store it in the db
		if (!$category->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->_category	=& $category;
		
		return true;
	}
}
?>