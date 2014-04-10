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
 * HTML View class for the Items View
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewTags extends JView
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
		$menu		= & JSite::getMenu();
		$uri 		= & JFactory::getURI();
		$lang 		= & JFactory::getLanguage();
		$item    	= $menu->getActive();
		$params 	= & $mainframe->getParams('com_quickfaq');
		
		$tid = JRequest::getInt('id', 0);
		$limitstart		= JRequest::getInt('limitstart');
		$filter			= JRequest::getString('filter');
		$limit			= JRequest::getInt('limit', $params->get('item_num'));

		//add css file
		$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq_rtl.css');
		}
		
		$document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #quickfaq dd { height: 1%; }</style><![endif]-->');

		//params
		$params->def( 'page_title', $item->name);

		//pathway
		$pathway 	= & $mainframe->getPathWay();
		$pathway->setItemName( 1, $item->name );

		//Set Page title
		if (!$item->name) {
			$document->setTitle($params->get('page_title'));
			$document->setMetadata( 'keywords' , $params->get('page_title') );
		}
		
		$items 	= & $this->get('Data');
        $tag	= & $this->get('Tag');
        $total 	= & $this->get('Total');
        
        //set 404 if category doesn't exist or access isn't permitted
		if ( empty($tag) ) {
			return JError::raiseError( 404, JText::sprintf( 'Tag #%d not found', $tid ) );
		}
        
        //ordering
		$filter_order		= JRequest::getCmd('filter_order', 'i.title');
		$filter_order_Dir	= JRequest::getCmd('filter_order_Dir', 'ASC');
		
		$lists						= array();
		$lists['filter_order']		= $filter_order;
		$lists['filter_order_Dir'] 	= $filter_order_Dir;
		$lists['filter']			= $filter;
		
				
		// Create the pagination object
		jimport('joomla.html.pagination');
		
		$pageNav 	= new JPagination($total, $limitstart, $limit);
		
		$this->assign('action', 					$uri->toString());

		$this->assignRef('items' , 					$items);
		$this->assignRef('tag' , 					$tag);
		$this->assignRef('params' , 				$params);
		$this->assignRef('pageNav' , 				$pageNav);
		$this->assignRef('lists' ,	 				$lists);

		parent::display($tpl);

	}
}
?>