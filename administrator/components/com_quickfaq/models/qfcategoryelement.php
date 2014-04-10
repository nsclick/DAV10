<?php
/**
 * @version 1.0 $Id: qfcategoryelement.php 136 2008-08-04 14:33:18Z schlu $
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
 * Quickfaq Component Categoryelement Model
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since		1.0
 */
class QuickfaqModelQfcategoryelement extends JModel
{
	/**
	 * Category data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Category total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Category id
	 *
	 * @var int
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

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);

	}

	/**
	 * Method to set the category identifier
	 *
	 * @access	public
	 * @param	int Category identifier
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	 = $id;
		$this->_data = null;
	}

	/**
	 * Method to get categories item data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		global $mainframe;
		
		static $items;

		if (isset($items)) {
			return $items;
		}
		
		$limit				= $mainframe->getUserStateFromRequest( 'com_quickfaq.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart 		= $mainframe->getUserStateFromRequest( 'com_quickfaq.limitstart', 'limitstart', 0, 'int' );
		$filter_order		= $mainframe->getUserStateFromRequest( 'com_quickfaq.categories.filter_order', 		'filter_order', 	'c.ordering', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_quickfaq.categories.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( 'com_quickfaq.categories.filter_state', 'filter_state', '', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( 'com_quickfaq.categories.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );
		
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', c.ordering';
		
		$where = array();
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'c.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'c.published = 0';
			}
		}
		
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
		//select the records
		//note, since this is a tree we have to do the limits code-side
		if ($search) {			
			
			
			$query = 'SELECT c.id'
					. ' FROM #__quickfaq_categories AS c'
					. ' WHERE LOWER(c.title) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false )
					. $where
					;
			$this->_db->setQuery( $query );
			$search_rows = $this->_db->loadResultArray();					
		}
		
		$query = 'SELECT c.*, u.name AS editor, g.name AS groupname'
					. ' FROM #__quickfaq_categories AS c'
					. ' LEFT JOIN #__groups AS g ON g.id = c.access'
					. ' LEFT JOIN #__users AS u ON u.id = c.checked_out'
					. $where
					. $orderby
					;
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();
				
		//establish the hierarchy of the categories
		$children = array();
		
    	//set depth limit
   		$levellimit = 10;
		
    	foreach ($rows as $child) {
        	$parent = $child->parent_id;
       		$list 	= @$children[$parent] ? $children[$parent] : array();
        	array_push($list, $child);
        	$children[$parent] = $list;
    	}
    	
    	//get list of the items
    	$list = quickfaq_cats::treerecurse(0, '', array(), $children, false, max(0, $levellimit-1));

    	//eventually only pick out the searched items.
		if ($search) {
			$list1 = array();

			foreach ($search_rows as $sid )
			{
				foreach ($list as $item)
				{
					if ($item->id == $sid) {
						$list1[] = $item;
					}
				}
			}
			// replace full list with found items
			$list = $list1;
		}
		
    	$total = count( $list );

		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination( $total, $limitstart, $limit );

		// slice out elements based on limits
		$list = array_slice( $list, $this->_pagination->limitstart, $this->_pagination->limit );
		
		return $list;
	}

	/**
	 * Method to get a pagination object for the categories
	 *
	 * @access public
	 * @return object
	 */
	function getPagination()
	{
		if ($this->_pagination == null) {
			$this->getData();
		}
		return $this->_pagination;
	}
}
?>