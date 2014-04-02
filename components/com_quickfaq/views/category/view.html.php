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
 * HTML View class for the QuickFAQ View
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewCategory extends JView
{
	/**
	 * Creates the Forms for the View
	 *
	 * @since 1.0
	 */
	function display( $tpl = null )
	{
		global $mainframe, $Itemid;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu    	= $menus->getActive();
		$params 	= & $mainframe->getParams('com_quickfaq');
		$uri 		= & JFactory::getURI();
		$lang 		= & JFactory::getLanguage();

		// Request variables
		$limitstart		= JRequest::getInt('limitstart');
		
		//add css file
		$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq_rtl.css');
		}
	
		$document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #quickfaq dd { height: 1%; }</style><![endif]-->');

		//pathway
		$pathway 	= & $mainframe->getPathWay();

		// Get data from the model		
		$category 	= & $this->get('Category');
		$items 		= & $this->get('Data');
		$limit		= $mainframe->getUserStateFromRequest('com_quickfaq.'.$this->getLayout().'.limit', 'limit', $params->def('limit', 0), 'int');
		$total 		= & $this->get('Total');
		
		$cats		= new quickfaq_cats((int)$category->id);
		$parents	= $cats->getParentlist();
		$categories	= & $this->get('Childs');
		
		if( count( $items ) ) :
			foreach( $items as $i => $item ) :
				$items[$i]->link		= JRoute::_("index.php?option=com_quickfaq&Itemid=$Itemid&view=items&cid=$category->slug&id=$item->id:$item->alias");
			endforeach;
		endif;
		
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );		
			
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$category->title);
			}
			
		} else {
			$params->set('page_title',	$category->title);
		}

		foreach($parents as $parent) {
			$pathway->addItem( $this->escape($parent->title), JRoute::_('index.php?view=category&cid='.$parent->categoryslug));
		}

		/*
		* Handle the metadata for a categoy
		*/
		$document->setTitle( $params->get( 'page_title' ) );
		
		if ($category->meta_description) {
			$document->setDescription( $category->meta_description );
		}
		if ($category->meta_keywords) {
				$document->setMetadata('keywords', $category->meta_keywords);
		}
		if ($mainframe->getCfg('MetaTitle') == '1') {
				$mainframe->addMetaTag('title', $category->title);
		}
		
		if ($params->get('show_feed_link', 1) == 1) {
			//add alternate feed link
			$link	= '&format=feed';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}

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

		$this->assign('action', 					$uri->toString());
		
		$this->assignRef('params' , 				$params);
		$this->assignRef('categories' , 			$categories);
		$this->assignRef('items' , 					$items);
		$this->assignRef('category' , 				$category);
		$this->assignRef('pageNav' , 				$pageNav);
		$this->assignRef('lists' ,	 				$lists);

		parent::display($tpl);

	}
}
?>