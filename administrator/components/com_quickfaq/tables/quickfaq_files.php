<?php
/**
 * @version 1.0 $Id: quickfaq_files.php 195 2009-01-30 06:33:12Z schlu $
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
class quickfaq_files extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var string */
	var $filename			= '';
	/** @var string */
	var $altname			= '';
	/** @var int */
	var $hits				= 0;
	/** @var date */
	var $uploaded			= '';
	/** @var int */
	var $uploaded_by		= '';
	/** @var int */
	var $checked_out 		= 0;
	/** @var date */
	var $checked_out_time	= '';

	function quickfaq_files(& $db) {
		parent::__construct('#__quickfaq_files', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{		
		return true;
	}
}
?>