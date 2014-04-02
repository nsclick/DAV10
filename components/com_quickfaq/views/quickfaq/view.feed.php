<?php
/**
 * @version 1.0 $Id: view.feed.php 195 2009-01-30 06:33:12Z schlu $
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
 * HTML View class for the QuickFAQ View (RSS)
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewQuickfaq extends JView
{
	/**
	 * Creates the RSS for the View
	 *
	 * @since 1.0
	 */
	function display()
	{
		global $mainframe;
		
		$doc 		= & JFactory::getDocument();
		$params 	= & $mainframe->getParams();
		$doc->link 	= JRoute::_('index.php?option=com_quickfaq&view=quickfaq');
		
		JRequest::setVar('limit', $mainframe->getCfg('feed_limit'));
		$rows 		= & $this->get('Feed');
				
		foreach ( $rows as $row )
		{
			// strip html from feed item title
			$title = $this->escape( $row->title );
			$title = html_entity_decode( $title );

			$category = $this->escape( $row->cattitle );
			
			// url link to article
			// & used instead of &amp; as this is converted by feed creator
			$link = JRoute::_('index.php?option=com_quickfaq&view=items&cid='. $row->catslug .'&id='.$row->slug );

			// strip html from feed item description text
			$description	= ($params->get('feed_summary', 0) ? $row->introtext.$row->fulltext : $row->introtext);
		//	$author			= $row->created_by_alias ? $row->created_by_alias : $row->author;
			@$date 			= ( $row->created ? date( 'r', strtotime($row->created) ) : '' );

			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= $title;
			$item->link 		= $link;
			$item->description 	= $description;
			$item->date			= $date;
			$item->category   	= $category;

			// loads item info into rss array
			$doc->addItem( $item );
		}
	}
}
?>