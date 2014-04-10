<?php
/**
 * @version 1.0 $Id: admin.quickfaq.php 195 2009-01-30 06:33:12Z schlu $
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

require_once (JPATH_COMPONENT_SITE.DS.'classes'.DS.'quickfaq.helper.php');
require_once (JPATH_COMPONENT_SITE.DS.'classes'.DS.'quickfaq.categories.php');

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

//Set filepath
$params =& JComponentHelper::getParams('com_quickfaq');
define('COM_QUICKFAQ_FILEPATH',    JPATH_ROOT.DS.$params->get($path, 'components/com_quickfaq/upload'));

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if( $controller = JRequest::getWord('controller') ) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

//Create the controller
$classname  = 'QuickfaqController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getWord('task'));
$controller->redirect();
?>