<?php
/**
 * @version 1.0 $Id: item.php 192 2009-01-09 01:02:45Z schlu $
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
class QuickfaqModelItem extends JModel
{
	/**
	 * Item data
	 *
	 * @var object
	 */
	var $_item = null;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();

		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$this->setId($cid[0]);
	}

	/**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int item identifier
	 */
	function setId($id)
	{
		// Set item id and wipe data
		$this->_id	    = $id;
		$this->_item	= null;
	}
	
	/**
	 * Overridden get method to get properties from the item
	 *
	 * @access	public
	 * @param	string	$property	The name of the property
	 * @param	mixed	$value		The value of the property to set
	 * @return 	mixed 				The value of the property
	 * @since	1.0
	 */
	function get($property, $default=null)
	{
		if ($this->_loadItem()) {
			if(isset($this->_item->$property)) {
				return $this->_item->$property;
			}
		}
		return $default;
	}

	/**
	 * Method to get item data
	 *
	 * @access	public
	 * @return	array
	 * @since	1.0
	 */
	function &getItem()
	{		
		if ($this->_loadItem())
		{		
			if (JString::strlen($this->_item->fulltext) > 1) {
				$this->_item->text = $this->_item->introtext . "<hr id=\"system-readmore\" />" . $this->_item->fulltext;
			} else {
				$this->_item->text = $this->_item->introtext;
			}
			
			$query = 'SELECT name'
					. ' FROM #__users'
					. ' WHERE id = '. (int) $this->_item->created_by
					;
			$this->_db->setQuery($query);
			$this->_item->creator = $this->_db->loadResult();

			//reduce unneeded query
			if ($this->_item->created_by == $this->_item->modified_by) {
				$this->_item->modifier = $this->_item->creator;
			} else {
				$query = 'SELECT name'
						. ' FROM #__users'
						. ' WHERE id = '. (int) $this->_item->modified_by
						;
				$this->_db->setQuery($query);
				$this->_item->modifier = $this->_db->loadResult();
			}
			
		}
		else  $this->_initItem();

		return $this->_item;
	}


	/**
	 * Method to load item data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function _loadItem()
	{
		// Lets load the item if it doesn't already exist
		if (empty($this->_item))
		{
			$query = 'SELECT *'
					. ' FROM #__quickfaq_items'
					. ' WHERE id = '.$this->_id
					;
			$this->_db->setQuery($query);
			$this->_item = $this->_db->loadObject();
			
			return (boolean) $this->_item;
		}
		return true;
	}

	/**
	 * Method to initialise the item data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function _initItem()
	{
		// Lets load the item if it doesn't already exist
		if (empty($this->_item))
		{
			$createdate = & JFactory::getDate();
			$nullDate	= $this->_db->getNullDate();
			
			$item = new stdClass();
			$item->id					= 0;
			$item->cid[]				= 0;
			$item->title				= null;
			$item->alias				= null;
			$item->text					= null;
			$item->plus					= 0;
			$item->minus				= 0;
			$item->hits					= 0;
			$item->version				= 0;
			$item->meta_description		= null;
			$item->meta_keywords		= null;
			$item->created				= $createdate->toUnix();
			$item->created_by			= null;
			$item->created_by_alias		= '';
			$item->modified				= $nullDate;
			$item->modified_by			= null;
			$item->attribs				= null;
			$item->metadata				= null;
			$item->state				= 1;
			$this->_item				= $item;
			return (boolean) $this->_item;
		}
		return true;
	}

	/**
	 * Method to checkin/unlock the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$item = & JTable::getInstance('quickfaq_items', '');
			return $item->checkin($this->_id);
		}
		return false;
	}

	/**
	 * Method to checkout/lock the item
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
			// Make sure we have a user id to checkout the group with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$item = & JTable::getInstance('quickfaq_items', '');
			return $item->checkout($uid, $this->_id);
		}
		return false;
	}

	/**
	 * Tests if the item is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	1.0
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadItem())
		{
			if ($uid) {
				return ($this->_item->checked_out && $this->_item->checked_out != $uid);
			} else {
				return $this->_item->checked_out;
			}
		} elseif ($this->_id < 1) {
			return false;
		} else {
			JError::raiseWarning( 0, 'UNABLE LOAD DATA');
			return false;
		}
	}

	/**
	 * Method to store the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function store($data)
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
				
		$item  =& $this->getTable('quickfaq_items', '');
		$user	=& JFactory::getUser();
		
		$details	= JRequest::getVar( 'details', array(), 'post', 'array');
		$tags 		= JRequest::getVar( 'tag', array(), 'post', 'array');
		$cats 		= JRequest::getVar( 'cid', array(), 'post', 'array');
		$files		= JRequest::getVar( 'fid', array(), 'post', 'array');
		$files 		= array_filter($files);
		
		//At least one category needs to be assigned
		if (!is_array( $cats ) || count( $cats ) < 1) {
			$this->setError('SELECT CATEGORY');
			return false;
		}
		
		// bind it to the table
		if (!$item->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$item->bind($details);

		// sanitise id field
		$item->id = (int) $item->id;
		
		$nullDate	= $this->_db->getNullDate();

		// Are we saving from an item edit?
		if ($item->id) {
			$item->modified 		= gmdate('Y-m-d H:i:s');
			$item->modified_by 	= $user->get('id');
		} else {
			$item->modified 		= $nullDate;
			$item->modified_by 	= '';

			//get time and userid
			$item->created 			= gmdate('Y-m-d H:i:s');
			$item->created_by		= $user->get('id');
		}
		
		// Get a state and parameter variables from the request
		$item->state	= JRequest::getVar( 'state', 0, '', 'int' );
		$params		= JRequest::getVar( 'params', null, 'post', 'array' );

		// Build parameter INI string
		if (is_array($params))
		{
			$txt = array ();
			foreach ($params as $k => $v) {
				$txt[] = "$k=$v";
			}
			$item->attribs = implode("\n", $txt);
		}

		// Get metadata string
		$metadata = JRequest::getVar( 'meta', null, 'post', 'array');
		if (is_array($params))
		{
			$txt = array();
			foreach ($metadata as $k => $v) {
				if ($k == 'description') {
					$item->meta_description = $v;
				} elseif ($k == 'keywords') {
					$item->meta_keywords = $v;
				} else {
					$txt[] = "$k=$v";
				}
			}
			$item->metadata = implode("\n", $txt);
		}
		
		quickfaq_html::saveContentPrep($item);
		
		if (!$item->id) {
			$item->ordering = $item->getNextOrder();
		}
		
		// Make sure the data is valid
		if (!$item->check()) {
			$this->setError($item->getError());
			return false;
		}
		
		$item->version++;

		// Store it in the db
		if (!$item->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$this->_item	=& $item;
		
		//store tag relation
		$query = 'DELETE FROM #__quickfaq_tags_item_relations WHERE itemid = '.$item->id;
		$this->_db->setQuery($query);
		$this->_db->query();
			
		foreach($tags as $tag)
		{
			$query = 'INSERT INTO #__quickfaq_tags_item_relations (`tid`, `itemid`) VALUES(' . $tag . ',' . $item->id . ')';
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		
		//store cat relation
		$query = 'DELETE FROM #__quickfaq_cats_item_relations WHERE itemid = '.$item->id;
		$this->_db->setQuery($query);
		$this->_db->query();
			
		foreach($cats as $cat)
		{
			$query = 'INSERT INTO #__quickfaq_cats_item_relations (`catid`, `itemid`) VALUES(' . $cat . ',' . $item->id . ')';
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		
		//store file relation
		$query = 'DELETE FROM #__quickfaq_files_item_relations WHERE itemid = '.$item->id;
		$this->_db->setQuery($query);
		$this->_db->query();
			
		foreach($files as $file)
		{
			$query = 'INSERT INTO #__quickfaq_files_item_relations (`fileid`, `itemid`) VALUES(' . $file . ',' . $item->id . ')';
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		return true;
	}

	/**
	 * Method to reset hits
	 * 
	 * @param int id
	 * @return int
	 * @since 1.0
	 */
	function resetHits($id)
	{
		$row  =& $this->getTable('quickfaq_items', '');
		$row->load($id);
		$row->hits = 0;
		$row->store();
		$row->checkin();
		return $row->id;
	}
	
	/**
	 * Method to reset votes
	 * 
	 * @param int id
	 * @return int
	 * @since 1.0
	 */
	function resetVotes($id)
	{
		$row  =& $this->getTable('quickfaq_items', '');
		$row->load($id);
		$row->plus = 0;
		$row->minus = 0;
		$row->store();
		$row->checkin();
		return $row->id;
	}
	
	/**
	 * Method to fetch tags
	 * 
	 * @return object
	 * @since 1.0
	 */
	function gettags()
	{
		$query = 'SELECT * FROM #__quickfaq_tags ORDER BY name';
		$this->_db->setQuery($query);
		$tags = $this->_db->loadObjectlist();
		return $tags;
	}
	
	/**
	 * Method to fetch used tags when performing an edit action
	 * 
	 * @param int id
	 * @return array
	 * @since 1.0
	 */
	function getusedtags($id)
	{
		$query = 'SELECT DISTINCT tid FROM #__quickfaq_tags_item_relations WHERE itemid = ' . (int)$id;
		$this->_db->setQuery($query);
		$used = $this->_db->loadResultArray();
		return $used;
	}
	
	/**
	 * Method to reset hits
	 * 
	 * @param int id
	 * @return object
	 * @since 1.0
	 */
	function getvotes($id)
	{
		$query = 'SELECT plus, minus FROM #__quickfaq_items WHERE id = '.(int)$id;
		$this->_db->setQuery($query);
		$votes = $this->_db->loadObjectlist();
		
		return $votes;
	}
	
	/**
	 * Method to get hits
	 * 
	 * @param int id
	 * @return int
	 * @since 1.0
	 */
	function gethits($id)
	{
		$query = 'SELECT hits FROM #__quickfaq_items WHERE id = '.(int)$id;
		$this->_db->setQuery($query);
		$hits = $this->_db->loadResult();
		
		return $hits;
	}
	
	/**
	 * Method to get used categories when performing an edit action
	 * 
	 * @return array
	 * @since 1.0
	 */
	function getCatsselected()
	{
		$query = 'SELECT DISTINCT catid FROM #__quickfaq_cats_item_relations WHERE itemid = ' . (int)$this->_id;
		$this->_db->setQuery($query);
		$used = $this->_db->loadResultArray();
		return $used;
	}
	
	/**
	 * Method to get files
	 * 
	 * @return object
	 * @since 1.0
	 */
	function getFiles()
	{
		$query = 'SELECT DISTINCT rel.fileid, f.filename'
				.' FROM #__quickfaq_files AS f'
				.' LEFT JOIN #__quickfaq_files_item_relations AS rel ON rel.fileid = f.id'
				.' WHERE rel.itemid = ' . (int)$this->_id
				;
		$this->_db->setQuery($query);
		$files = $this->_db->loadObjectList();
		return $files;
	}
}
?>