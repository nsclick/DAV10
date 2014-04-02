<?php
/**
 * @version 1.0 $Id: quickfaq.php 195 2009-01-30 06:33:12Z schlu $
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
class QuickfaqModelQuickfaq extends JModel
{
	/**
	 * Data
	 *
	 * @var object
	 */
	var $_data = null;
	
	/**
	 * total
	 *
	 * @var int
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
		
		//set limits
		$limit 			= $params->def('catlimit', 5);
		$limitstart		= JRequest::getInt('limitstart');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}
	
	/**
	 * Method to get Data
	 *
	 * @access public
	 * @return object
	 */
	function getData()
	{
		// Lets load the categories if it doesn't already exist
		if (empty($this->_data))
		{
			//get data
			$this->_data = $this->_getList( $this->_buildQuery(), $this->getState('limitstart'), $this->getState('limit') );
			
			//add childs of each category
			$k = 0;
			$count = count($this->_data);
			for($i = 0; $i < $count; $i++)
			{
				$category 			=& $this->_data[$i];
				$category->subcats	= $this->_getsubs( $category->id );

				$k = 1 - $k;
			}
		}

		return $this->_data;
	}
	
	/**
	 * Method to build the Categories query
	 *
	 * @access private
	 * @return string
	 */
	function _buildQuery()
	{
		$user 		= &JFactory::getUser();
		$gid		= (int) $user->get('aid');
		$ordering	= 'c.ordering ASC';

		//build where clause
		$where = ' WHERE cc.published = 1';
		$where .= ' AND cc.parent_id = 0';
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
				. ' AND c.parent_id = 0'
				. ' AND c.access <= '.$gid
				. ' ORDER BY '.$ordering
				;

		return $query;
	}

	/**
	 * Method to build the Categories query without subselect
	 * That's enough to get the total value.
	 *
	 * @access private
	 * @return string
	 */
	function _buildQueryTotal()
	{
		$user 		= &JFactory::getUser();
		$gid		= (int) $user->get('aid');
		
		$query = 'SELECT c.id'
				. ' FROM #__quickfaq_categories AS c'
				. ' WHERE c.published = 1'
				. ' AND c.parent_id = 0'
				. ' AND c.access <= '.$gid
				;

		return $query;
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
			$query = $this->_buildQueryTotal();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	
	/**
	 * Method to fetch the subcategories
	 *
	 * @access private
	 * @return object
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
	 * Get the feed data
	 *
	 * @access public
	 * @return object
	 */
	function getFeed()
	{
		$user 		= &JFactory::getUser();
		$gid		= (int) $user->get('aid');
		$limit 		= JRequest::getVar('limit', 10);

		$query = 'SELECT DISTINCT i.*, c.title AS cattitle,'
		. ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,'
		. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug'
		. ' FROM #__quickfaq_items AS i'
		. ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.itemid = i.id'
		. ' LEFT JOIN #__quickfaq_categories AS c ON c.id = rel.catid'
		. ' WHERE c.published = 1'
		. ' AND c.access <= '.$gid
		. ' AND i.state IN (1, -4)'
		. ' LIMIT '. $limit
		;
		
		$this->_db->setQuery($query);
		$feed = $this->_db->loadObjectList();

		return $feed;
	}
}
?>