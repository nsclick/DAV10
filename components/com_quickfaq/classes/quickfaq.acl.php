<?php
/**
 * @version 1.0 $Id: quickfaq.acl.php 195 2009-01-30 06:33:12Z schlu $
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

/**************************************************************************
 * All permissions are granted and denied within QuickFAQ
 * with the following commands
 * 
 * Comment out or add permissions as you desire
 * 
 *************************************************************************/

$auth =& JFactory::getACL();

//Uhhhhhhhhhhhhh, where is the hierarchial inheritance?

//who can add a faq item?
$auth->addACL('com_quickfaq', 'add', 'users', 'super administrator');
$auth->addACL('com_quickfaq', 'add', 'users', 'administrator');
$auth->addACL('com_quickfaq', 'add', 'users', 'manager');
$auth->addACL('com_quickfaq', 'add', 'users', 'editor');
$auth->addACL('com_quickfaq', 'add', 'users', 'author');
$auth->addACL('com_quickfaq', 'add', 'users', 'registered');

//Who can edit a faq item?
$auth->addACL('com_quickfaq', 'edit', 'users', 'super administrator');
$auth->addACL('com_quickfaq', 'edit', 'users', 'administrator');
$auth->addACL('com_quickfaq', 'edit', 'users', 'manager');
$auth->addACL('com_quickfaq', 'edit', 'users', 'editor');
//use the one from com_content as workaround?
$auth->addACL( 'com_content', 'edit', 'users', 'author', 'content', 'own' );

//Who can change the state of a faq item?
//Note: Users who can change the state of an item will see unpublished, non approved,
//in progress and open question faq items
//Archived faq items are only accessible via the administration
$auth->addACL('com_quickfaq', 'state', 'users', 'super administrator');
$auth->addACL('com_quickfaq', 'state', 'users', 'administrator');
$auth->addACL('com_quickfaq', 'state', 'users', 'manager');
$auth->addACL('com_quickfaq', 'state', 'users', 'editor');

//Who can add new tags?
$auth->addACL('com_quickfaq', 'newtags', 'users', 'super administrator');
$auth->addACL('com_quickfaq', 'newtags', 'users', 'administrator');
$auth->addACL('com_quickfaq', 'newtags', 'users', 'manager');
$auth->addACL('com_quickfaq', 'newtags', 'users', 'editor');

//Who can upload files?
$auth->addACL('com_quickfaq', 'fileupload', 'users', 'super administrator');
$auth->addACL('com_quickfaq', 'fileupload', 'users', 'administrator');
$auth->addACL('com_quickfaq', 'fileupload', 'users', 'manager');
$auth->addACL('com_quickfaq', 'fileupload', 'users', 'editor');
?>