<?php
/**
 * @version 1.0 $Id: router.php 199 2009-01-31 21:50:15Z schlu $
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

function QuickFAQBuildRoute(&$query)
{
	$segments = array();

	if(isset($query['view']))
	{
		if(empty($query['Itemid'])) {
			$segments[] = $query['view'];
		}
		
		if($query['view'] == 'tags') {
			$segments[] = $query['view'];
		}
		
		if($query['view'] == 'favourites') {
			$segments[] = $query['view'];
		}

		unset($query['view']);
	};

	if(isset($query['cid']))
	{
		$segments[] = $query['cid'];
		unset($query['cid']);
	};

	if(isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	};

	return $segments;
}

function QuickFAQParseRoute($segments)
{
	$vars = array();

	// Count route segments
	$count = count($segments);

	if($segments[0] == 'tags') {
		$vars['view'] = 'tags';
		$vars['id'] = $segments[$count-1];
		return $vars;
	}
	
	if($segments[0] == 'favourites') {
		$vars['view'] = 'favourites';
		return $vars;
	}
	
	if ($count == 1) {
		$vars['cid'] = $segments[$count-1];
		$vars['view'] = 'category';
	}
 
	if ($count == 0) {
		$vars['view'] = 'quickfaq';
	}

	if ($count == 2) {
		$vars['cid'] = $segments[$count-2];
		$vars['view'] = 'items';
		$vars['id']    = $segments[$count-1];
	}
	
	return $vars;
}
?>