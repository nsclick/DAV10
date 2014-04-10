<?php
/**
 * @version 1.0 $Id: stats.php 136 2008-08-04 14:33:18Z schlu $
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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * QuickFAQ Component stats Model
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since		1.0
 */
class QuickfaqModelStats extends JModel
{

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Method to get general stats
	 *
	 * @access public
	 * @return array
	 */
	function getGeneralstats()
	{
		$_items = array();

		/*
		* Get total nr of items
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__quickfaq_items'
					;

		$this->_db->SetQuery($query);
  		$_items[] = $this->_db->loadResult();
  		
  		/*
		* Get nr of all categories
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__quickfaq_categories'
					;

		$this->_db->SetQuery($query);
  		$_items[] = $this->_db->loadResult();
  		
  		/*
		* Get nr of all tags
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__quickfaq_tags'
					;

		$this->_db->SetQuery($query);
  		$_items[] = $this->_db->loadResult();

  		/*
		* Get nr of all files
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__quickfaq_files'
					;

		$this->_db->SetQuery($query);
  		$_items[] = $this->_db->loadResult();
  		
		return $_items;
	}


	/**
	 * Method to get popular data
	 *
	 * @access public
	 * @return array
	 */
	function getPopular()
	{
		$query = 'SELECT id, title, hits, minus, plus, (plus / (plus + minus) ) * 100 AS votes'
				. ' FROM #__quickfaq_items'
				. ' ORDER BY hits DESC'
				. ' LIMIT 5'
				;

		$this->_db->SetQuery($query);
  		$hits = $this->_db->loadObjectList();
  		
  		return $hits;
	}
	
	/**
	 * Method to get rating data
	 *
	 * @access public
	 * @return array
	 */
	function getRating()
	{
		$query = 'SELECT minus, plus, (plus / (plus + minus) ) * 100 AS votes, title, id'
				. ' FROM #__quickfaq_items'
				. ' WHERE (plus / (plus + minus) ) * 100 > 0'
				. ' ORDER BY votes DESC'
				. ' LIMIT 5'
				;

		$this->_db->SetQuery($query);
  		$votes = $this->_db->loadObjectList();
  		
  		return $votes;
	}
	
	/**
	 * Method to get rating data
	 *
	 * @access public
	 * @return array
	 */
	function getWorstRating()
	{
		$query = 'SELECT minus, plus, (plus / (plus + minus) ) * 100 AS votes, title, id'
				. ' FROM #__quickfaq_items'
				. ' WHERE (plus / (plus + minus) ) * 100 > 0'
				. ' ORDER BY votes ASC'
				. ' LIMIT 5'
				;

		$this->_db->SetQuery($query);
  		$worstvotes = $this->_db->loadObjectList();
  		
  		return $worstvotes;
	}
	
	/**
	 * Method to get creators data
	 *
	 * @access public
	 * @return array
	 */
	function getCreators()
	{
		$query = 'SELECT COUNT(*) AS counter, i.created_by AS id, u.name, u.username'
				. ' FROM #__quickfaq_items AS i'
				. ' LEFT JOIN #__users AS u ON u.id = i.created_by'
				. ' GROUP BY u.name'
				. ' ORDER BY counter DESC'
				. ' LIMIT 5'
				;

		$this->_db->SetQuery($query);
  		$usercreate = $this->_db->loadObjectList();
  		
  		return $usercreate;
	}
	
	/**
	 * Method to get editors data
	 *
	 * @access public
	 * @return array
	 */
	function getEditors()
	{
		$query = 'SELECT COUNT(*) AS counter, i.modified_by AS id, u.name, u.username'
				. ' FROM #__quickfaq_items AS i'
				. ' LEFT JOIN #__users AS u ON u.id = i.modified_by'
				. ' WHERE i.modified_by > 0'
				. ' GROUP BY u.name'
				. ' ORDER BY counter DESC'
				. ' LIMIT 5'
				;

		$this->_db->SetQuery($query);
  		$usereditor = $this->_db->loadObjectList();
  		
  		return $usereditor;
	}
	
	/**
	 * Method to get favourites data
	 *
	 * @access public
	 * @return array
	 */
	function getFavoured()
	{
		$query = 'SELECT i.title, i.id, COUNT(f.itemid) AS favnr'
				. ' FROM #__quickfaq_items AS i'
				. ' LEFT JOIN #__quickfaq_favourites AS f ON f.itemid = i.id'
				. ' GROUP BY f.itemid'
				. ' ORDER BY favnr DESC'
				. ' LIMIT 5'
				;

		$this->_db->SetQuery($query);
  		$favnr = $this->_db->loadObjectList();
  		
  		return $favnr;
	}
	
	/**
	 * Method to get favourites data
	 * TODO: Clean up this mess
	 *
	 * @access public
	 * @return array
	 */
	function getStatestats()
	{  		
  		//get states
		$query = 'SELECT state'
				. ' FROM #__quickfaq_items AS i'
				;

		$this->_db->SetQuery($query);
  		$states = $this->_db->loadObjectList();
  		
  		$total = count($states);
  		
  		//initialize vars
  		$collect = array();
  		$collect['published'] = 0;
  		$collect['unpublished'] = 0;
  		$collect['archived'] = 0;
  		$collect['pending'] = 0;
  		$collect['open'] = 0;
  		$collect['progress'] = 0;
  		
  		//count each states
  		foreach ($states AS $state) {
  			if ($state->state == 1) {
  				$collect['published']++;
  			} elseif($state->state == 0) {
  				$collect['unpublished']++;
  			} elseif($state->state == -1) {
  				$collect['archived']++;
  			} elseif($state->state == -2) {
  				$collect['pending']++;
  			} elseif($state->state == -3) {
  				$collect['open']++;
  			} elseif($state->state == -4) {
  				$collect['progress']++;
  			}
  		}
  		
  		//get percentage and label
  		$val = array();
  		$lab = array();
  		$i = 0;
  		foreach ($collect as $key => $proz) {
  			
  			if ($proz == 0) {
  				unset($collect[$key]);
  				continue;
  			}
  			$val[] = round($proz / $total * 100);
  			
  			if ( $key == 'published' ) {
				$lab[] = JText::_( 'Published' ).' '.$val[$i].' %';
			} else if ( $key == 'unpublished' ) {
				$lab[] = JText::_( 'Unpublished' ).' '.$val[$i].' %';
			} else if ( $key == 'archived' ) {
				$lab[] = JText::_( 'Archived' ).' '.$val[$i].' %';
			} else if ( $key == 'pending' ) {
				$lab[] = JText::_( 'PENDING' ).' '.$val[$i].' %';
			} else if ( $key == 'open' ) {
				$lab[] = JText::_( 'OPEN QUESTION' ).' '.$val[$i].' %';
			} else if ( $key == 'progress' ) {
				$lab[] = JText::_( 'IN PROGRESS' ).' '.$val[$i].' %';
			}
			$i++;
  		}
  		
  		$collect['values'] = implode( ',', $val );
  		$collect['labels'] = implode('|', $lab);
  		  		
  		return $collect;
	}
	
	/**
	 * Method to get votes data
	 *
	 * @access public
	 * @return array
	 */
	function getVotesstats()
	{
  		/*
		* Get all votes
		*
		*/
  		$query = 'SELECT plus, minus'
				. ' FROM #__quickfaq_items'
				;

		$this->_db->SetQuery($query);
  		$votes = $this->_db->loadObjectList();
  		
  		$total = count($votes);
  		
		//initialize vars
  		$collect = array();
  		$collect['020'] = 0;
  		$collect['040'] = 0;
  		$collect['060'] = 0;
  		$collect['080'] = 0;
  		$collect['100'] = 0;
  		$collect['novotes'] = 0;
  		$collect['negative'] = 0;
  		
  		//count
  		foreach ($votes AS $vote) {
  			
  			if($vote->minus == 0 && $vote->plus == 0) {
  				$collect['novotes']++;
  				continue;
  			}
  			if($vote->minus != 0 && $vote->plus == 0) {
  				$collect['negative']++;
 				continue;
  			}
  			
  			$percentage = round(($vote->plus / ($vote->plus + $vote->minus) ) * 100);
  			
  			if ($percentage > 0 && $percentage < 20) {
  				$collect['020']++;
  			} elseif($percentage >= 20 && $percentage < 40) {
  				$collect['040']++;
  			} elseif($percentage >= 40 && $percentage < 60) {
  				$collect['060']++;
  			} elseif($percentage >= 60 && $percentage < 80) {
  				$collect['080']++;
  			} elseif($percentage >= 80 && $percentage <= 100) {
  				$collect['100']++;
  			}
  		}
  		
  		//get votes and label
  		$val = array();
  		$lab = array();
  		$i = 0;
  		foreach ($collect as $key => $value) {
  			
  			if ($value == 0) {
  				unset($collect[$key]);
  				continue;
  			}
  			$val[]	= $value;
  			$proz	= round($value / $total * 100);
  			
  			if ( $key == '020' ) {
				$lab[] = JText::_( 'VOTES BEETWEEN 020' ).' '.$proz.' % ('.$val[$i].')';
			} else if ( $key == '040' ) {
				$lab[] = JText::_( 'VOTES BEETWEEN 040' ).' '.$proz.' % ('.$val[$i].')';
			} else if ( $key == '060' ) {
				$lab[] = JText::_( 'VOTES BEETWEEN 060' ).' '.$proz.' % ('.$val[$i].')';
			} else if ( $key == '080' ) {
				$lab[] = JText::_( 'VOTES BEETWEEN 080' ).' '.$proz.' % ('.$val[$i].')';
			} else if ( $key == '100' ) {
				$lab[] = JText::_( 'VOTES BEETWEEN 100' ).' '.$proz.' % ('.$val[$i].')';
			} else if ( $key == 'novotes' ) {
				$lab[] = JText::_( 'NOVOTES' ).' '.$proz.' % ('.$val[$i].')';
			} else if ( $key == 'negative' ) {
				$lab[] = JText::_( 'VOTES NEGATIVE' ).' '.$proz.' % ('.$val[$i].')';
			}
			
			$i++;
  		}
  		
  		$collect['values'] = implode( ',', $val );
  		$collect['labels'] = implode( '|', $lab );
  		  		
  		return $collect;
	}
}
?>