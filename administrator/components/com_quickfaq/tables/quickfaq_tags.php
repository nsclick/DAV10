<?php
/**
 * @version 1.0 $Id: quickfaq_tags.php 195 2009-01-30 06:33:12Z schlu $
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
class quickfaq_tags extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var string */
	var $name				= '';
	/** @var string */
	var $alias				= '';
	/** @var int */
	var $published			= null;
	/** @var int */
	var $checked_out 		= 0;
	/** @var date */
	var $checked_out_time	= '';

	function quickfaq_tags(& $db) {
		parent::__construct('#__quickfaq_tags', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{
		// Not typed in a name?
		if (trim( $this->name ) == '') {
			$this->_error = JText::_( 'ADD NAME' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		
		$alias = JFilterOutput::stringURLSafe($this->name);

		if(empty($this->alias) || $this->alias === $alias ) {
			$this->alias = $alias;
		}
		
		/** check for existing name */
		$query = 'SELECT id'
				.' FROM #__quickfaq_tags'
				.' WHERE name = '.$this->_db->Quote($this->name)
				;
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('TAG NAME ALREADY EXIST', $this->name));
			//$this->_error = JText::sprintf('TAG NAME ALREADY EXIST', $this->name);
			return false;
		}
	
		return true;
	}
}
?>