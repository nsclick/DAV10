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
class QuickfaqViewQuickfaq extends JView
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
		$db		 	= & JFactory::getDBO();
		$menus		= & JSite::getMenu();
		$lang 		= & JFactory::getLanguage();
		$menu		= $menus->getActive();
		$dispatcher	=& JDispatcher::getInstance();
		
		// Get the page/component configuration
		$params = $mainframe->getParams('com_quickfaq');
		
		//add css file
		$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq_rtl.css');
		}
		
		$document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #quickfaq dd { height: 1%; }</style><![endif]-->');

		$limitstart	= JRequest::getInt('limitstart');
		$limit 		= $params->def('catlimit', 0);
		$total		= $this->get('Total');
		$categories	= & $this->get('Data');
		
		JPluginHelper::importPlugin('content');
		
		if( count( $categories ) ) :
			foreach( $categories as $ci => $category ) :
				$query = 'SELECT i.id, i.title, i.alias, i.introtext, i.fulltext'
				//. ' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as slug'
				. ' FROM #__quickfaq_items AS i'
				. ' LEFT JOIN #__quickfaq_cats_item_relations AS r ON i.id = r.itemid'
				. ' WHERE r.catid = ' . (int) $category->id
				. ' AND i.state = 1'
				. ' ORDER BY i.ordering'
				;
				$db->setQuery( $query );
				$items = $db->loadObjectList();
				if( count( $items ) ) :
					foreach( $items as $ic => $item ) :
					// 'index.php?view=items&cid='. $this->category->slug.'&id='. $item->slug
						$items[$ic]->link	= JRoute::_("index.php?option=com_quickfaq&Itemid=$Itemid&view=items&cid=$category->slug&id=$item->id:$item->alias");
						$item->text			= $item->introtext . chr(13).chr(13) . $item->fulltext;
						$results			= $dispatcher->trigger('onPrepareContent', array ( $item, $params, 0));
						$items[$ic]->text 	= $item->text;
					endforeach;
				endif;
				$categories[$ci]->items 	= $items;
			endforeach;
		endif;
		
		/*$js		= JPATH_COMPONENT.DS.'assets'.DS.'js'.DS.'items.js.php';
		if( file_exists( $js ) ) :
			ob_start();
			require_once( $js );
			$javascript	= ob_get_contents();
			ob_end_clean();
			
			$document->addScriptDeclaration($javascript);
		endif;*/

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );		
			
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$menu->name);
			}
			
		} else {
			$params->set('page_title',	JText::_('FAQ'));
		}

		/*
		* Handle the metadata for the categories list
		*/
		$document->setTitle($params->get('page_title'));
		$document->setMetadata( 'keywords' , $params->get('page_title') );

		if ($mainframe->getCfg('MetaTitle') == '1') {
				$mainframe->addMetaTag('title', $params->get('page_title'));
		}
				
		if ($params->get('show_feed_link', 1) == 1) {
			//add alternate feed link
			$link	= '&format=feed';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}

		// Create the pagination object
		jimport('joomla.html.pagination');

		$pageNav 	= new JPagination($total, $limitstart, $limit);
		
		$this->assignRef('params' , 				$params);
		$this->assignRef('categories' , 			$categories);
		$this->assignRef('pageNav' , 				$pageNav);

		parent::display($tpl);
	}
}
?>