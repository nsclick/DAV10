<?php
/**
 * @version 1.0 $Id: tags.php 195 2009-01-30 06:33:12Z schlu $
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
 * @subpackage QuickFAQ
 * @since		1.0
 */
class QuickfaqModelTags extends JModel
{
	/**
	 * Data
	 *
	 * @var mixed
	 */
	var $_data = null;
	
	/**
	 * Tag
	 *
	 * @var mixed
	 */
	var $_tag = null;
	
	/**
	 * Items total
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

		//get the number of events from database
		$limit			= JRequest::getInt('limit', $params->get('limit'));
		$limitstart		= JRequest::getInt('limitstart');
		$id				= JRequest::getInt('id', 0);
		
		$this->setId((int)$id);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		// Get the filter request variables
		$this->setState('filter_order', JRequest::getCmd('filter_order', 'i.title'));
		$this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));
	}
	
	/**
	 * Method to set the tag id
	 *
	 * @access	public
	 * @param	int	tag ID number
	 */
	function setId($id)
	{
		// Set new category ID and wipe data
		$this->_id			= $id;
		$this->_data		= null;
	}

	/**
	 * Method to get Data
	 *
	 * @access public
	 * @return object
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{		
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
		}
		
		return $this->_data;
	}
	
	/**
	 * Total nr of Items
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
	 * Method to build the query
	 *
	 * @access public
	 * @return string
	 */
	function _buildQuery()
	{   	
        // Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildItemWhere();
		$orderby	= $this->_buildItemOrderBy();

        $query = 'SELECT i.id, i.title, i.*, (i.plus / (i.plus + i.minus) ) * 100 AS votes,'
         . ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,'
         . ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as categoryslug'
         . ' FROM #__quickfaq_items AS i'
         . ' INNER JOIN #__quickfaq_tags_item_relations AS t ON t.itemid = i.id'
         . ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.itemid = i.id'
         . ' LEFT JOIN #__quickfaq_categories AS c ON c.id = rel.catid'
         . $where
         . ' GROUP BY i.id'
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
	 * @return string
	 */
	function _buildItemWhere( )
	{
		global $mainframe;

		$user		= & JFactory::getUser();
		$gid		= (int) $user->get('aid');
		$params 	= & $mainframe->getParams('com_quickfaq');

		// First thing we need to do is to select only the requested items
		$where = ' WHERE t.tid = '.$this->_id;
		
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
	 * Method to load the Tag data
	 *
	 * @access public
	 * @return object
	 */
	function getTag()
	{
		//get categories
		$query = 'SELECT t.name, t.id,'
				. ' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as slug'
				. ' FROM #__quickfaq_tags AS t'
				. ' WHERE t.id = '.$this->_id
				. ' AND t.published = 1'
				;

		$this->_db->setQuery($query);
        $this->_tag = $this->_db->loadObject();
        
		return $this->_tag;
	}
}
?>