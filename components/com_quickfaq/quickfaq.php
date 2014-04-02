<?php
/**
 * @version 1.0 $Id: quickfaq.php 199 2009-01-31 21:50:15Z schlu $
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

//include the needed classes
require_once (JPATH_COMPONENT.DS.'classes'.DS.'quickfaq.helper.php');
require_once (JPATH_COMPONENT.DS.'classes'.DS.'quickfaq.acl.php');
require_once (JPATH_COMPONENT.DS.'classes'.DS.'quickfaq.categories.php');

//Set filepath
$params =& JComponentHelper::getParams('com_quickfaq');
define('COM_QUICKFAQ_FILEPATH',    JPATH_ROOT.DS.$params->get($path, 'components/com_quickfaq/upload'));

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

// Require the controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$classname  = 'QuickfaqController';
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar('task', null, 'default', 'cmd') );

// Redirect if set by the controller
$controller->redirect();
?>