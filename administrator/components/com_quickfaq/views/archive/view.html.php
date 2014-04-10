<?php
/**
 * @version 1.0 $Id: view.html.php 197 2009-01-31 21:34:36Z schlu $
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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * View class for the QuickFAQ Archive screen
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewArchive extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		//initialise variables
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		$lang 		= & JFactory::getLanguage();
		
		JHTML::_('behavior.tooltip');

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.archive.filter_order', 		'filter_order', 	'i.ordering', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.archive.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.archive.search', 			'search', 			'', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend_rtl.css');
    	}
		
		//Create Submenu
		JSubMenuHelper::addEntry( JText::_( 'HOME' ), 'index.php?option=com_quickfaq');
		JSubMenuHelper::addEntry( JText::_( 'ITEMS' ), 'index.php?option=com_quickfaq&view=items');
		JSubMenuHelper::addEntry( JText::_( 'CATEGORIES' ), 'index.php?option=com_quickfaq&view=categories');
		JSubMenuHelper::addEntry( JText::_( 'TAGS' ), 'index.php?option=com_quickfaq&view=tags');
		JSubMenuHelper::addEntry( JText::_( 'ARCHIVE' ), 'index.php?option=com_quickfaq&view=archive', true);
		JSubMenuHelper::addEntry( JText::_( 'FILEMANAGER' ), 'index.php?option=com_quickfaq&view=filemanager');
		JSubMenuHelper::addEntry( JText::_( 'STATISTICS' ), 'index.php?option=com_quickfaq&view=stats');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'FAQ ARCHIVE' ), 'qfarchive' );
		JToolBarHelper::unarchiveList();
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList();

		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );
		
		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$ordering = ($lists['order'] == 'i.ordering');

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('ordering'		, $ordering);
		$this->assignRef('user'			, $user);
		$this->assignRef('direction'	, $lang);

		parent::display($tpl);
	}
}
?>