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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Favourites View
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewFavourites extends JView
{
	/**
	 * Creates the item page
	 *
	 * @since 1.0
	 */
	function display( $tpl = null )
	{
		global $mainframe;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu    	= $menus->getActive();
		$params 	= & $mainframe->getParams('com_quickfaq');
		$uri 		= & JFactory::getURI();
		$lang 		= & JFactory::getLanguage();

		$limitstart		= JRequest::getInt('limitstart');
		$limit			= JRequest::getInt('limit', $params->get('item_num'));

		//add css file
		$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq_rtl.css');
		}
		
		$document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #quickfaq dd { height: 1%; }</style><![endif]-->');

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );		
			
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_('MY FAVOURITES'));
			}
			
		} else {
			$params->set('page_title',	JText::_('MY FAVOURITES'));
		}
		
		//pathway
		$pathway 	= & $mainframe->getPathWay();
		$pathway->addItem( $params->get('page_title'), JRoute::_('index.php?view=favourites'));
		
		$document->setTitle($params->get('page_title'));
		$document->setMetadata( 'keywords' , $params->get('page_title') );
		
		$items 	= & $this->get('Data');
        $total 	= & $this->get('Total');
        
        //ordering
		$filter_order		= JRequest::getCmd('filter_order', 'i.title');
		$filter_order_Dir	= JRequest::getCmd('filter_order_Dir', 'ASC');
		$filter				= JRequest::getString('filter');
		
		$lists						= array();
		$lists['filter_order']		= $filter_order;
		$lists['filter_order_Dir'] 	= $filter_order_Dir;
		$lists['filter']			= $filter;
		
				
		// Create the pagination object
		jimport('joomla.html.pagination');
		
		$pageNav 	= new JPagination($total, $limitstart, $limit);
		$page 		= $total - $limit;
		
		$this->assign('action', 					$uri->toString());

		$this->assignRef('items' , 					$items);
		$this->assignRef('params' , 				$params);
		$this->assignRef('page' , 					$page);
		$this->assignRef('pageNav' , 				$pageNav);
		$this->assignRef('lists' ,	 				$lists);

		parent::display($tpl);

	}
}
?>