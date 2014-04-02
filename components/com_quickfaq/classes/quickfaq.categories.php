<?php
/**
 * @version 1.0 $Id: quickfaq.categories.php 195 2009-01-30 06:33:12Z schlu $
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

//TODO: Refactor this mess completely!!!
class quickfaq_cats
{
	/**
	 * Parent Categories
	 *
	 * @var array
	 */
	var $id = null;
	
	/**
	 * Parent Categories
	 *
	 * @var array
	 */
	var $parentcats = array();
	
	var $category = array();
	
	/**
	 * Constructor
	 *
	 * @param int $cid
	 * @return quickfaq_categories
	 */
	function quickfaq_cats($cid)
	{
		$this->id = $cid;
		$this->buildParentCats($this->id);
		$this->getParentCats();
	}
    
	function getParentCats()
	{
		$db	=& JFactory::getDBO();
		
		$this->parentcats = array_reverse($this->parentcats);
				
		foreach($this->parentcats as $cid) {
			
			$query = 'SELECT id, title,'
					.' CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(\':\', id, alias) ELSE id END as categoryslug'
					.' FROM #__quickfaq_categories'
					.' WHERE id ='. (int)$cid 
					.' AND published = 1'
					;
			
			$db->setQuery($query);
			$this->category[] 	= $db->loadObject();
		}
	}
	
	function buildParentCats($cid)
	{
		$db = JFactory::getDBO();
		
		$query = 'SELECT parent_id FROM #__quickfaq_categories WHERE id = '.(int)$cid;
		$db->setQuery( $query );

		if($cid != 0) {
			array_push($this->parentcats, $cid);
		}

		//if we still have results
		if(sizeof($db->loadResult()) != 0) {
			$this->buildParentCats($db->loadResult());
		}
	}
	
	function getParentlist()
	{
		return $this->category;
	}
	
	/**
    * Get the categorie tree
    *
    * @return array
    */
	function getCategoriesTree($published)
	{
		$db	=& JFactory::getDBO();
		
		if ($published) {
			$where = ' WHERE published = 1';
		} else {
			$where = '';
		}
		
		$query = 'SELECT *, id AS value, title AS text'
				.' FROM #__quickfaq_categories'
				.$where
				.' ORDER BY parent_id, ordering'
				;

		$db->setQuery($query);

		$rows = $db->loadObjectList();
		
		//set depth limit
		$levellimit = 10;
		
		//get children
    	$children = array();  	
    	foreach ($rows as $child) {
        	$parent = $child->parent_id;
       		$list = @$children[$parent] ? $children[$parent] : array();
        	array_push($list, $child);
        	$children[$parent] = $list;
    	}
    	//get list of the items
    	$list = quickfaq_cats::treerecurse(0, '', array(), $children, true, max(0, $levellimit-1));
    	
		return $list;
	}
	
	/**
    * Get the categorie tree
    * based on the joomla 1.0 treerecurse 
    *
    * @access public
    * @return array
    */
	function treerecurse( $id, $indent, $list, &$children, $title, $maxlevel=9999, $level=0, $type=1 )
	{
		if (@$children[$id] && $level <= $maxlevel) {
			foreach ($children[$id] as $v) {
				$id = $v->id;

				if ( $type ) {
					$pre    = '<sup>|_</sup>&nbsp;';
					$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else {
					$pre    = '- ';
					$spacer = '&nbsp;&nbsp;';
				}

				if ($title) {
					if ( $v->parent_id == 0 ) {
						$txt    = ''.$v->title;
					} else {
						$txt    = $pre.$v->title;
					}
				} else {
					if ( $v->parent_id == 0 ) {
						$txt    = '';
					} else {
						$txt    = $pre;
					}
				}
				$pt = $v->parent_id;
				$list[$id] = $v;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = count( @$children[$id] );

				$list = quickfaq_cats::treerecurse( $id, $indent . $spacer, $list, $children, $title, $maxlevel, $level+1, $type );
			}
		}
		return $list;
	}

	/**
	 * Build Categories select list
	 *
	 * @param array $list
	 * @param string $name
	 * @param array $selected
	 * @param bool $top
	 * @param string $class
	 * @return void
	 */
	function buildcatselect($list, $name, $selected, $top, $class = 'class="inputbox"')
	{
		$catlist 	= array();
		
		if($top) {
			$catlist[] 	= JHTML::_( 'select.option', '0', JText::_( 'TOPLEVEL' ) );
		}
		
		foreach ($list as $item) {
			$catlist[] = JHTML::_( 'select.option', $item->id, $item->treename);
		}
		return JHTML::_('select.genericlist', $catlist, $name, $class, 'value', 'text', $selected );
	}
}
?>