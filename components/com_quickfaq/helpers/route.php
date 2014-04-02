<?php
/**
 * @version 1.0 $Id: route.php 195 2009-01-30 06:33:12Z schlu $
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

// Component Helper
jimport('joomla.application.component.helper');

/**
 * QuickFAQ Component Route Helper
 *
 * @static
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class QuickfaqHelperRoute
{
	/**
	 * @param	int	The route of the faq item
	 */
	function getItemRoute($id, $catid = 0)
	{
		$needles = array(
			'item'  => (int) $id,
			'category' => (int) $catid
		);

		//Create the link
		$link = 'index.php?option=com_quickfaq&view=items';

		if($catid) {
			$link .= '&cid='.$catid;
		}
		
		$link .= '&id='. $id;

		if($item = QuickfaqHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	function getCategoryRoute($catid)
	{
		$needles = array(
			'category' => (int) $catid
		);

		//Create the link
		$link = 'index.php?option=com_quickfaq&view=category&cid='.$catid;

		if($item = QuickfaqHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}
	
	function getTagRoute($id)
	{
		$needles = array(
			'tags' => (int) $id
		);

		//Create the link
		$link = 'index.php?option=com_quickfaq&view=tags&id='.$id;

		if($item = QuickfaqHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	function _findItem($needles)
	{
		$component =& JComponentHelper::getComponent('com_quickfaq');

		$menus	= &JApplication::getMenu('site', array());
		$items	= $menus->getItems('componentid', $component->id);
		$user 	= & JFactory::getUser();
		$access = (int)$user->get('aid');

		$match = null;
		$count = 0;		
		
		foreach($needles as $needle => $id)
		{			
			$count++;
			
			foreach($items as $item)
			{
				//fetch menuitems generic of that item
				if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
					$match = $item;
					break;
				}
				
				//ensure that only the second part of the array will go through, otherwise $id will seen as $cid
				if($count == 2) {
					//fetch menuitems which might be linked to a category of that item
					if ((@$item->query['view'] == 'category') && (@$item->query['cid'] == $id)) {
						$match = $item;
						break;
					}
				}

			}
			
			if(isset($match)) {
				break;
			}
		}
		
		//no menuitem exists -> return first possible match
		if(empty($match))
		{
			foreach($items as $item)
			{
				if (@$item->published == 1 && @$item->access <= $access && @$item->query['view'] != 'favourites' && @$item->query['layout'] != 'form') {
					$match = $item;
					break;
				}
			}
		}

		return $match;
	}
}
?>
