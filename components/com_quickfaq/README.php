/**
 * @version 1.0 $Id: README.php 199 2009-01-31 21:50:15Z schlu $
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
 
#####################################################################################

1. About QuickFAQ
2. Feature List
3. Installation
4. Initial configuration
5. Configuring Access permissions

@see: http://joomlacode.org/gf/project/quickfaq/wiki/
#####################################################################################

1. About QuickFAQ

QuickFAQ is an easy to use but powerfull FAQ management system.

2. Feature List (the most important ones)

+ Unlimited Subcategories
+ Assign FAQ Items to multiple Categories
+ Create Tags/Labels to flag FAQ Items
+ Up/down voting of FAQ Items
+ Favoure FAQ Items to maintain a personal bookmark list
+ Detailed Statistics
+ Document uploader/manager
+ PDF creation of FAQ Items
+ RSS/ATOM Feeds
+ JComments integration

3. Installation

+ Navigate to the Joomla Installer and select this package for installation.
+ Navigate to the Joomla Installer and install the QuickFAQ Search Plugin if needed.
+ Navigate to the Joomla Installer and install a QuickFAQ translation if needed.

4. Initial configuration

+ Enable the QuickFAQ Search Plugin if installed
+ Create the folder, files will moved to during upload. 
Default: YOUR_JOOMLA_ROOT/components/com_quickfaq/upload 
or define another existing destination directory in the configuration.
+ Adjust the configuration parameters to your needs.
+ Create your Categories structure before typing in any FAQ Items.
+ QuickFAQ needs at least one menuitem (type component) to work properly.
Create a menuitem for QuickFAQ in the menumanager, pointing for example to the QuickFAQ view.

5. Configuring Access permissions

All access permissions are defined in YOUR_JOOMLA_ROOT/components/com_quickfaq/classes/quickfaq.acl.php

Currently you can grant/restrict permissions for the following actions:
+ Add a Faq Item
+ Edit a Faq Item
+ Change the state of a FAQ Item
+ Add new Tags

Just remove or add the definitions for a Joomla user group
(registered, author, editor, manager, administrator, super administrator).

Example:
The default minimum required userrank in QuickFAQ to add new Tags is "Editor".
Asume you want to allow "Authors" to add new Tags.

Add to the following snippet:
---------------------------------------------------------------------------
//Who can add new tags?
$auth->addACL('com_quickfaq', 'newtags', 'users', 'super administrator');
$auth->addACL('com_quickfaq', 'newtags', 'users', 'administrator');
$auth->addACL('com_quickfaq', 'newtags', 'users', 'manager');
$auth->addACL('com_quickfaq', 'newtags', 'users', 'editor');
---------------------------------------------------------------------------

only this line:
---------------------------------------------------------------------------
$auth->addACL('com_quickfaq', 'newtags', 'users', 'author');
---------------------------------------------------------------------------

Keep in mind, that even it seems obvious there is no hierarchial inheritance.
That means, if you grant a registered user add permissions that super administrators
will not be able to add FAQ items till you define them also explicit for this group!
Keep also in mind that it doesn't make sense to grant a registered user edit permission but no add permission.