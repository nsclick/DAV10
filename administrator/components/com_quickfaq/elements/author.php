<?php
/**
 * @version 1.0 $Id: author.php 136 2008-08-04 14:33:18Z schlu $
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Renders a author element
 *
 * @package 	Joomla
 * @subpackage	QuickFAQ
 * @since		1.0
 */
class JElementAuthor extends JElement
{
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Author';

	function fetchElement($name, $value, &$node, $control_name)
	{
		return JHTML::_('list.users', $control_name.'['.$name.']', $value);
	}
}