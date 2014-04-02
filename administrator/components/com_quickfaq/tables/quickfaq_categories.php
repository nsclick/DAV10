<?php
/**
 * @version 1.0 $Id: quickfaq_categories.php 195 2009-01-30 06:33:12Z schlu $
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

defined('_JEXEC') or die('Restricted access');

/**
 * QuickFAQ table class
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class quickfaq_categories extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** 
	 * @var int 
	 * 
	 *@todo: ORM? 
	 */
	var $parent_id			= 0;
	/** @var string */
	var $title 				= '';
	/** @var string */
	var $alias	 			= '';
	/** @var string */
	var $text	 			= '';
	/** @var string */
	var $meta_description 	= null;
	/** @var string */
	var $meta_keywords		= null;
	/** @var string */
	var $image 				= '';
	/** @var int */
	var $published			= null;
	/** @var int */
	var $checked_out 		= 0;
	/** @var date */
	var $checked_out_time	= '';
	/** @var int */
	var $access 			= 0;
	/** @var int */
	var $ordering 			= null;

	function quickfaq_categories(& $db) {
		parent::__construct('#__quickfaq_categories', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{
		// Not typed in a category name?
		if (trim( $this->title ) == '') {
			$this->_error = JText::_( 'ADD NAME CATEGORY' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}

		$alias = JFilterOutput::stringURLSafe($this->title);

		if(empty($this->alias) || $this->alias === $alias ) {
			$this->alias = $alias;
		}

		/** check for existing name with same parent category*/
		$query = 'SELECT id'
				.' FROM #__quickfaq_categories'
				.' WHERE title = '.$this->_db->Quote($this->title)
				.' AND parent_id = '.$this->parent_id
				;
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('CATEGORY NAME ALREADY EXIST', $this->title));
			return false;
		}

		return true;
	}
}
?>