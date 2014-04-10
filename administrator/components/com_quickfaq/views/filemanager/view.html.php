<?php
/**
 * @version 1.0 $Id: view.html.php 197 2009-01-31 21:34:36Z schlu $
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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Filemanager View
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewFilemanager extends JView
{
	/**
	 * Creates the Filemanagerview
	 *
	 * @since 1.0
	 */
	function display( $tpl = null )
	{
		global $mainframe, $option;
		
		//initialise variables
		$document	= & JFactory::getDocument();
		$db  		= & JFactory::getDBO();
		$params 	= & JComponentHelper::getParams('com_quickfaq');
		$lang 		= & JFactory::getLanguage();
		
		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.filemanager.filter_order', 	'filter_order', 	'f.filename', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.filemanager.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$filter 			= $mainframe->getUserStateFromRequest( $option.'.filemanager.filter', 'filter', '', 'int' );
		$filter_assigned	= $mainframe->getUserStateFromRequest( $option.'.filemanager.filter_assigned', 'filter_assigned', '*', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.filemanager.search', 			'search', 			'', 'string' );
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
		JSubMenuHelper::addEntry( JText::_( 'ARCHIVE' ), 'index.php?option=com_quickfaq&view=archive');
		JSubMenuHelper::addEntry( JText::_( 'FILEMANAGER' ), 'index.php?option=com_quickfaq&view=filemanager', true);
		JSubMenuHelper::addEntry( JText::_( 'STATISTICS' ), 'index.php?option=com_quickfaq&view=stats');
		
		//create the toolbar
		JToolBarHelper::title( JText::_( 'FILEMANAGER' ), 'qffilemanager' );
		JToolBarHelper::deleteList();
		
		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		//search
		$lists 				= array();
		$lists['search'] 	= $search;
		
		//search filter
		$filters = array();
		$filters[] = JHTML::_('select.option', '1', JText::_( 'FILENAME' ) );
		$filters[] = JHTML::_('select.option', '2', JText::_( 'DISPLAY NAME' ) );
		$lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter', 'size="1" class="inputbox"', 'value', 'text', $filter );

		//build arphaned/assigned filterlist
		$assigned 	= array();
		$assigned[] = JHTML::_('select.option',  '', '- '. JText::_( 'ALL FILES' ) .' -' );
		$assigned[] = JHTML::_('select.option',  'O', JText::_( 'ORPHANED' ) );
		$assigned[] = JHTML::_('select.option',  'A', JText::_( 'ASSIGNED' ) );

		$lists['assigned'] = JHTML::_('select.genericlist', $assigned, 'filter_assigned', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_assigned );

		//table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;
		
		//uploadstuff
		if ($params->get('enable_flash', 1)) {
			JHTML::_('behavior.uploader', 'file-upload', array('onAllComplete' => 'function(){ window.location.reload(); }') );
		}
		
		jimport('joomla.client.helper');
		$ftp = !JClientHelper::hasCredentials('ftp');
		
		//assign data to template
		$this->assign('require_ftp'		, $ftp);
		
		$this->assignRef('session'		, JFactory::getSession());
		$this->assignRef('params'		, $params);
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('direction'	, $lang);

		parent::display($tpl);

	}
}
?>