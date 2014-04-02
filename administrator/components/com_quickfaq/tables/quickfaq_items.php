<?php
/**
 * @version 1.0 $Id: quickfaq_items.php 195 2009-01-30 06:33:12Z schlu $
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
class quickfaq_items extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var string */
	var $title 				= '';
	/** @var string */
	var $alias	 			= '';
	/** @var string */
	var $introtext	 		= '';
	/** @var string */
	var $fulltext	 		= '';
	/** @var int */
	var $plus				= 0;
	/** @var int */
	var $minus				= 0;
	/** @var int */
	var $hits				= 0;
	/** @var int */
	var $version			= 0;
	/** @var string */
	var $meta_description 	= null;
	/** @var string */
	var $meta_keywords		= null;
	/** @var string */
	var $metadata			= null;
	/** @var date */
	var $created			= '';
	/** @var int */
	var $created_by			= '';
	/** @var string */
	var $created_by_alias	= '';
	/** @var date */
	var $modified			= '';
	/** @var int */
	var $modified_by		= '';
	/** @var int */
	var $state				= null;
	/** @var string */
	var $attribs	 		= null;
	/** @var int */
	var $checked_out 		= 0;
	/** @var date */
	var $checked_out_time	= '';
	/** @var int */
	var $ordering 			= null;

	function quickfaq_items(& $db) {
		parent::__construct('#__quickfaq_items', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{
		// Not typed in a title?
		if (trim( $this->title ) == '') {
			$this->_error = JText::_( 'ADD TITLE' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}

		$alias = JFilterOutput::stringURLSafe($this->title);

		if(empty($this->alias) || $this->alias === $alias ) {
			$this->alias = $alias;
		}
		
		return true;
	}
}
?>