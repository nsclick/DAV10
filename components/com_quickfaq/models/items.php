<?php
/**
 * @version 1.0 $Id: items.php 195 2009-01-30 06:33:12Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2008 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENCE.php
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

jimport('joomla.application.component.model');

/**
 * QuickFAQ Component Item Model
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since		1.0
 */
class QuickfaqModelItems extends JModel
{
	/**
	 * Details data in details array
	 *
	 * @var array
	 */
	var $_item = null;


	/**
	 * tags in array
	 *
	 * @var array
	 */
	var $_tags = null;

	/**
	 * id
	 *
	 * @var array
	 */
	var $_id = null;
	
	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	}

	/**
	 * Method to set the faq id
	 *
	 * @access	public
	 * @param	int	faq ID number
	 */

	function setId($id)
	{
		// Set new faq ID
		$this->_id			= $id;
		$this->_item		= null;
	}

	/**
	 * Overridden get method to get properties from the item
	 *
	 * @access	public
	 * @param	string	$property	The name of the property
	 * @param	mixed	$value		The value of the property to set
	 * @return 	mixed 				The value of the property
	 * @since	1.5
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
	 * Overridden set method to pass properties on to the item
	 *
	 * @access	public
	 * @param	string	$property	The name of the property
	 * @param	mixed	$value		The value of the property to set
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function set( $property, $value=null )
	{
		if ($this->_loadItem()) {
			$this->_item->$property = $value;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Method to get data for the itemview
	 *
	 * @access public
	 * @return array
	 * @since 1.0
	 */
	function &getItem( )
	{
		/*
		* Load the Item data
		*/
		if ($this->_loadItem())
		{
			$user	= & JFactory::getUser();

			// Is the category published?
			if (!$this->_item->catpublished && $this->_item->catid)
			{
				JError::raiseError( 404, JText::_("CATEGORY NOT PUBLISHED") );
			}

			// Do we have access to the category?
			if (($this->_item->cataccess > $user->get('aid')) && $this->_item->catid)
			{
				JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
			}

			//reduce unneeded query
			if ($this->_item->created_by_alias) {
				$this->_item->creator = $this->_item->created_by_alias;
			} else {
				$query = 'SELECT name'
				. ' FROM #__users'
				. ' WHERE id = '. (int) $this->_item->created_by
				;
				$this->_db->setQuery($query);
				$this->_item->creator = $this->_db->loadResult();
			}

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

			if ($this->_item->modified == $this->_db->getNulldate()) {
				$this->_item->modified = null;
			}

			//check session if uservisit already recorded
			$session 	=& JFactory::getSession();
			$hitcheck = false;
			if ($session->has('hit', 'quickfaq')) {
				$hitcheck 	= $session->get('hit', 0, 'quickfaq');
				$hitcheck 	= in_array($this->_item->id, $hitcheck);
			}
			if (!$hitcheck) {
				//record hit
				$this->hit();

				$stamp = array();
				$stamp[] = $this->_item->id;
				$session->set('hit', $stamp, 'quickfaq');
			}
			//we show the introtext and fulltext (chr(13) = carriage return)
			$this->_item->text = $this->_item->introtext . chr(13).chr(13) . $this->_item->fulltext;
		}
		else
		{
			$user =& JFactory::getUser();
			$item =& JTable::getInstance('quickfaq_items', '');
			if ($user->authorize('com_quickfaq', 'state'))	{
				$item->state = 1;
			}
			$item->id					= 0;
			$item->author				= null;
			$item->created_by			= $user->get('id');
			$item->text					= '';
			$item->title				= null;
			$item->meta_description		= '';
			$item->meta_keywords		= '';
			$this->_item				= $item;
		}

		return $this->_item;
	}

	/**
	 * Method to load required data
	 *
	 * @access	private
	 * @return	array
	 * @since	1.0
	 */
	function _loadItem()
	{
		if($this->_id == '0') {
			return false;
		}

		if (empty($this->_item))
		{
			$query = 'SELECT i.*, (i.plus / (i.plus + i.minus) ) * 100 AS votes, c.access AS cataccess, c.id AS catid, c.published AS catpublished,'
			. ' u.name AS author, u.usertype,'
			. ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,'
			. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as categoryslug'
			. ' FROM #__quickfaq_items AS i'
			. ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.itemid = i.id'
			. ' LEFT JOIN #__quickfaq_categories AS c ON c.id = rel.catid'
			. ' LEFT JOIN #__users AS u ON u.id = i.created_by'
			. ' WHERE i.id = '.$this->_id
			;
			$this->_db->setQuery($query);
			$this->_item = $this->_db->loadObject();
			return (boolean) $this->_item;
		}
		return true;
	}
	
	/**
	 * Method to get the tags
	 *
	 * @access	public
	 * @return	object
	 * @since	1.0
	 */
	function getTags()
	{
		$query = 'SELECT DISTINCT t.name,'
		. ' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as slug'
		. ' FROM #__quickfaq_tags AS t'
		. ' LEFT JOIN #__quickfaq_tags_item_relations AS i ON i.tid = t.id'
		. ' WHERE i.itemid = ' . (int) $this->_id
		. ' AND t.published = 1'
		. ' ORDER BY t.name'
		;

		$this->_db->setQuery( $query );

		$this->_tags = $this->_db->loadObjectList();

		return $this->_tags;
	}

	/**
	 * Method to get the categories
	 *
	 * @access	public
	 * @return	object
	 * @since	1.0
	 */
	function getCategories()
	{
		$query = 'SELECT DISTINCT c.id, c.title,'
		. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug'
		. ' FROM #__quickfaq_categories AS c'
		. ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.catid = c.id'
		. ' WHERE rel.itemid = '.$this->_id
		;

		$this->_db->setQuery( $query );

		$this->_cats = $this->_db->loadObjectList();

		return $this->_cats;
	}

	/**
	 * Method to increment the hit counter for the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function hit()
	{
		if ($this->_id)
		{
			$item = & JTable::getInstance('quickfaq_items', '');
			$item->hit($this->_id);
			return true;
		}
		return false;
	}

	function getAlltags()
	{
		$query = 'SELECT * FROM #__quickfaq_tags ORDER BY name';
		$this->_db->setQuery($query);
		$tags = $this->_db->loadObjectlist();
		return $tags;
	}

	function getUsedtags()
	{
		$query = 'SELECT tid FROM #__quickfaq_tags_item_relations WHERE itemid = ' . (int)$this->_id;
		$this->_db->setQuery($query);
		$used = $this->_db->loadResultArray();
		return $used;
	}

	/**
	 * Tests if item is checked out
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
			JError::raiseWarning( 0, 'Unable to Load Data');
			return false;
		}
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
			// Make sure we have a user id to checkout the item with
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
	 * Method to store the item
	 *
	 * @access	public
	 * @since	1.0
	 */
	function store($data)
	{
		$item  		=& JTable::getInstance('quickfaq_items', '');
		$user     	=& JFactory::getUser();
		
		// Bind the form fields to the table
		if (!$item->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// sanitise id field
		$item->id = (int) $item->id;

		$isNew = ($item->id < 1);

		if ($isNew)
		{
			$item->created 		= gmdate('Y-m-d H:i:s');
			$item->created_by 	= $user->get('id');
		}
		else
		{
			$item->modified 	= gmdate('Y-m-d H:i:s');
			$item->modified_by 	= $user->get('id');

			$query = 'SELECT hits, minus, plus, created, created_by, version' .
			' FROM #__quickfaq_items' .
			' WHERE id = '.(int) $item->id;

			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();

			$item->plus = $result->plus;
			$item->minus = $result->minus;
			$item->hits = $result->hits;
			
			$item->created = $result->created;
			$item->created_by = $result->created_by;
			
			$item->version = $result->version;
			$item->version++;

			if (!$user->authorize('com_quickfaq', 'state'))	{
				$item->state = $result->state;
			}
		}

		// Publishing state hardening for Authors
		if (!$user->authorize('com_quickfaq', 'state'))
		{
			if ($isNew)
			{
				// For new items
				$item->state = -2;
			}
			else
			{
				$query = 'SELECT state' .
				' FROM #__quickfaq_items' .
				' WHERE id = '.(int) $item->id;

				$this->_db->setQuery($query);
				$result = $this->_db->loadResult();

				$item->state = $result;
			}
		}
		
		quickfaq_html::saveContentPrep($item);

		// Make sure the data is valid
		if (!$item->check()) {
			$this->setError($item->getError());
			return false;
		}

		// Store the article table to the database
		if (!$item->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if ($isNew)
		{
			$this->_id = $item->_db->insertId();
		}

		$item->ordering = $item->getNextOrder();

		//store tags
		$tags 		= JRequest::getVar( 'tag', array(), 'post', 'array');

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
		$cats 		= JRequest::getVar( 'cid', array(), 'post', 'array');

		$query = 'DELETE FROM #__quickfaq_cats_item_relations WHERE itemid = '.$item->id;
		$this->_db->setQuery($query);
		$this->_db->query();

		foreach($cats as $cat)
		{
			$query = 'INSERT INTO #__quickfaq_cats_item_relations (`catid`, `itemid`) VALUES(' . $cat . ',' . $item->id . ')';
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		$this->_item	=& $item;

		return $this->_item->id;
	}

	/**
	 * Method to store a vote
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function storevote($id, $vote)
	{
		if ($vote == 1) {
			$target = 'plus';
		} elseif ($vote == 0) {
			$target = 'minus';
		} else {
			return false;
		}

		$query = 'UPDATE #__quickfaq_items'
		.' SET '.$target.' = ( '.$target.' + 1 )'
		.' WHERE id = '.(int)$id
		;
		$this->_db->setQuery($query);
		$this->_db->query();

		return true;
	}

	/**
	 * Method to get the categories an item is assigned to
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function getCatsselected()
	{
		$query = 'SELECT DISTINCT catid FROM #__quickfaq_cats_item_relations WHERE itemid = ' . (int)$this->_id;
		$this->_db->setQuery($query);
		$used = $this->_db->loadResultArray();
		return $used;
	}

	/**
	 * Method to store the tag
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function storetag($data)
	{
		$row  =& $this->getTable('quickfaq_tags', '');

		// bind it to the table
		if (!$row->bind($data)) {
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}

		// Make sure the data is valid
		if (!$row->check()) {
			$this->setError($row->getError());
			return false;
		}

		// Store it in the db
		if (!$row->store()) {
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}

		return $row->id;
	}

	/**
	 * Method to add a tag
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function addtag($name)
	{
		$obj = new stdClass();
		$obj->name	 	= $name;
		$obj->published	= 1;

		$this->storetag($obj);

		//	$this->_db->insertObject('#__quickfaq_tags', $obj);

		return true;
	}

	/**
	 * Method to get the nr of favourites of anitem
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function getFavourites()
	{
		$query = 'SELECT COUNT(id) AS favs FROM #__quickfaq_favourites WHERE itemid = '.(int)$this->_id;
		$this->_db->setQuery($query);
		$favs = $this->_db->loadResult();
		return $favs;
	}

	/**
	 * Method to get the nr of favourites of an user
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function getFavoured()
	{
		$user = JFactory::getUser();

		$query = 'SELECT COUNT(id) AS fav FROM #__quickfaq_favourites WHERE itemid = '.(int)$this->_id.' AND userid= '.(int)$user->id;
		$this->_db->setQuery($query);
		$fav = $this->_db->loadResult();
		return $fav;
	}
	
	/**
	 * Method to get the Files assigned to this item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function getFiles()
	{
		$query = 'SELECT DISTINCT rel.fileid, f.filename, f.altname'
				.' FROM #__quickfaq_files AS f'
				.' LEFT JOIN #__quickfaq_files_item_relations AS rel ON rel.fileid = f.id'
				.' WHERE rel.itemid = ' . (int)$this->_id
				;
		$this->_db->setQuery($query);
		$files = $this->_db->loadObjectList();
		
		$files = quickfaq_images::BuildIcons($files);
		
		return $files;
	}
	
	/**
	 * Method to remove a favourite
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function removefav()
	{
		$user = JFactory::getUser();

		$query = 'DELETE FROM #__quickfaq_favourites WHERE itemid = '.(int)$this->_id.' AND userid = '.(int)$user->id;
		$this->_db->setQuery($query);
		$remfav = $this->_db->query();
		return $remfav;
	}
	
	/**
	 * Method to add a favourite
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function addfav()
	{
		$user = JFactory::getUser();

		$obj = new stdClass();
		$obj->itemid 	= $this->_id;
		$obj->userid	= $user->id;

		$addfav = $this->_db->insertObject('#__quickfaq_favourites', $obj);
		return $addfav;
	}
	
	/**
	 * Method to change the state of an item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function setitemstate($id, $state = 1)
	{
		$user 	=& JFactory::getUser();
		
		if ( $id )
		{

			$query = 'UPDATE #__quickfaq_items'
				. ' SET state = ' . (int)$state
				. ' WHERE id = '.(int)$id
				. ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id'). ' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}
?>