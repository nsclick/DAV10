<?php
/**
 * @version 1.0 $Id: category.php 195 2009-01-30 06:33:12Z schlu $
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * QuickFAQ Component Model
 *
 * @package Joomla
 * @subpackage Quickfaq
 * @since		1.0
 */
class QuickfaqModelCategory extends JModel
{
	/**
	 * Category id
	 *
	 * @var int
	 */
	var $_id = null;
	
	/**
	 * Categories items Data
	 *
	 * @var mixed
	 */
	var $_data = null;

	/**
	 * Childs
	 *
	 * @var mixed
	 */
	var $_childs = null;
	
	/**
	 * Category data
	 *
	 * @var object
	 */
	var $_category = null;

	/**
	 * Categories total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();

		global $mainframe;

		// Get the paramaters of the active menu item
		$params = & $mainframe->getParams('com_quickfaq');
		
		$cid			= JRequest::getInt('cid', 0);

		//get the number of entries from session
		$limit		= $mainframe->getUserStateFromRequest('com_quickfaq.category.limit', 'limit', $params->def('limit', 0), 'int');
		
		$limitstart		= JRequest::getInt('limitstart');
		
		$this->setId((int)$cid);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		// Get the filter request variables
		$this->setState('filter_order', JRequest::getCmd('filter_order', 'i.title'));
		$this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));
	}

	/**
	 * Method to set the category id
	 *
	 * @access	public
	 * @param	int	category ID number
	 */
	function setId($cid)
	{
		// Set new category ID and wipe data
		$this->_id			= $cid;
		//$this->_data		= null;
	}

	/**
	 * Method to get Data
	 *
	 * @access public
	 * @return mixed
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery($this->_id);
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_data;
	}

	/**
	 * Total nr of Categories
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the total nr if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Builds the query
	 *
	 * @access public
	 * @return string
	 */
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildItemWhere();
		$orderby	= $this->_buildItemOrderBy();

		$query = 'SELECT DISTINCT i.*, (i.plus / (i.plus + i.minus) ) * 100 AS votes,'
		. ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug'
		. ' FROM #__quickfaq_items AS i'
		. ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.itemid = i.id'
		. ' LEFT JOIN #__quickfaq_categories AS c ON c.id = '. $this->_id
		. $where
		. $orderby
		;

		return $query;
	}

	/**
	 * Build the order clause
	 *
	 * @access private
	 * @return string
	 */
	function _buildItemOrderBy()
	{
		$filter_order		= $this->getState('filter_order');
		$filter_order_dir	= $this->getState('filter_order_dir');

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_dir.', i.title';

		return $orderby;
	}

	/**
	 * Method to build the WHERE clause
	 *
	 * @access private
	 * @return array
	 */
	function _buildItemWhere( )
	{
		global $mainframe;

		$user		= & JFactory::getUser();
		$gid		= (int) $user->get('aid');

		// Get the paramaters of the active menu item
		$params 	= & $mainframe->getParams('com_quickfaq');

		// First thing we need to do is to select only the requested items
		$where = ' WHERE rel.catid = '.$this->_id;

		// Second is to only select items the user has access to
		$states = '1, -4';
		if ($user->authorize('com_quickfaq', 'state')) {
			$states .= ', 0 , -2, -3';
		}
		$where .= ' AND i.state IN ('.$states.')';

		// Second is to only select items assigned to category the user has access to
		$where .= ' AND c.access <= '.$gid;

		/*
		* If we have a filter, and this is enabled... lets tack the AND clause
		* for the filter onto the WHERE clause of the item query.
		*/
		if ($params->get('filter'))
		{
			$filter 		= JRequest::getString('filter', '', 'request');

			if ($filter)
			{
				// clean filter variables
				$filter			= $this->_db->getEscaped( trim(JString::strtolower( $filter ) ) );

				$where .= ' AND LOWER( i.title ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $filter, true ).'%', false );
			}
		}
		return $where;
	}

	/**
	 * Method to build the Categories query
	 *
	 * @access private
	 * @return array
	 */
	function _buildChildsquery()
	{
		$user 		= &JFactory::getUser();
		$gid		= (int) $user->get('aid');
		$ordering	= 'ordering ASC';

		$where = ' WHERE cc.published = 1';
		$where .= ' AND cc.parent_id = '. $this->_id;
		$where .= ' AND cc.access <= '.$gid;
		$states = '1, -4';
		if ($user->authorize('com_quickfaq', 'state')) {
			$states .= ', 0 , -2, -3';
		}
		$where .= ' AND i.state IN ('.$states.')';
		$where .= ' AND c.id = cc.id';
		
		$query = 'SELECT c.*,'
				. ' CASE WHEN CHAR_LENGTH( c.alias ) THEN CONCAT_WS( \':\', c.id, c.alias ) ELSE c.id END AS slug,'
					. ' ('
					. ' SELECT COUNT( DISTINCT i.id )'
					. ' FROM #__quickfaq_items AS i'
					. ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.itemid = i.id'
					. ' LEFT JOIN #__quickfaq_categories AS cc ON cc.id = rel.catid'
					. $where
					. ' GROUP BY cc.id'
					. ')' 
					. ' AS assignedfaqs'
				. ' FROM #__quickfaq_categories AS c'
				. ' WHERE c.published = 1'
				. ' AND c.parent_id = '. $this->_id
				. ' AND c.access <= '.$gid
				. ' ORDER BY '.$ordering
				;
		
		return $query;
	}
	/**
	 * Method to get the childs of a category
	 *
	 * @access private
	 * @return array
	 */
	function getChilds()
	{
		$query = $this->_buildChildsquery();
		$this->_childs = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

		$k = 0;
		$count = count($this->_childs);
		for($i = 0; $i < $count; $i++)
		{
			$category =& $this->_childs[$i];

			$category->subcats		= $this->_getsubs( $category->id );

			$k = 1 - $k;
		}

		return $this->_childs;
	}

	/**
	 * Method to build the Categories query
	 * todo: see above and merge
	 *
	 * @access private
	 * @return array
	 */
	function _getsubs($id)
	{
		$user 		= &JFactory::getUser();
		$gid		= (int) $user->get('aid');
		$ordering	= 'ordering ASC';

		$query = 'SELECT *,'
				. ' CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(\':\', id, alias) ELSE id END as slug'
				. ' FROM #__quickfaq_categories'
				. ' WHERE published = 1'
				. ' AND parent_id = '. (int)$id
				. ' AND access <= '.$gid
				. ' ORDER BY '.$ordering
				;

		$this->_db->setQuery($query);
		$this->_subs = $this->_db->loadObjectList();

		return $this->_subs;
	}

	/**
	 * Method to load the Category
	 *
	 * @access public
	 * @return array
	 */
	function getCategory()
	{
		//initialize some vars
		$user		= & JFactory::getUser();
		$gid		= (int) $user->get('aid');

		//get categories
		$query = 'SELECT c.*,'
		. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug'
		. ' FROM #__quickfaq_categories AS c'
		. ' WHERE c.id = '.$this->_id
		. ' AND c.published = 1'
		. ' AND c.access <= '.$gid
		;

		$this->_db->setQuery($query);
		$this->_category = $this->_db->loadObject();
		
		//Make sure the category is published
		if (!$this->_category->published)
		{
			JError::raiseError(404, JText::sprintf( 'CATEGORY #%d NOT FOUND', $this->_id ));
			return false;
		}
		
		//check whether category access level allows access
		//additional check
		if ($this->_category->access > $gid)
		{
			JError::raiseError(403, JText::_("ALERTNOTAUTH"));
			return false;
		}

		return $this->_category;
	}
}
?>