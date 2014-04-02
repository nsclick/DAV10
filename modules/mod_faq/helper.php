<?php
/**
 * @version		$Id: helper.php 2010-07-26 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// No se puede acceder directamente
defined('_JEXEC') or die('Restricted access');

class modFaqHelper
{
	function getDatos( &$params )
	{
		$user					=& JFactory::getUser();
		$db						=& JFactory::getDBO();
		$doc					=& JFactory::getDocument();

		$datos					= new stdClass;
		//$datos->titulo			= $params->get('titulo','');
		$datos->titulo			= "Preguntas frecuentes";
		$datos->descripcion		= nl2br($params->get('descripcion',''));
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $params->get('menu') );
		$datos->link			= JRoute::_( $menu->link."&Itemid=$menu->id" );
		
		$menuforo 				= JTable::getInstance('Menu');
		$menuforo->load( $params->get('menuforo') );
		$datos->linkforo			= JRoute::_( $menuforo->link."&Itemid=$menuforo->id" );
		
		/*
		$query = 'SELECT a.id, a.alias, a.title' .
			' FROM #__quickfaq_categories AS a' .
			//' INNER JOIN #__content AS b ON b.catid = a.id' .
			//' AND b.state = 1' .
			//' AND ( b.publish_up = '.$db->Quote($nullDate).' OR b.publish_up <= '.$db->Quote($now).' )' .
			//' AND ( b.publish_down = '.$db->Quote($nullDate).' OR b.publish_down >= '.$db->Quote($now).' )';
			//' WHERE a.section = ' . $db->Quote($id) .
			' WHERE a.published = 1' .
			' AND a.access <= '.(int) $user->get('aid') .
			' ORDER BY a.ordering';
		$db->setQuery($query);
		$datos->categorias = $db->loadObjectList();
		if ( count( $datos->categorias ) ) : 
			foreach( $datos->categorias as $c => $category ) :
				$datos->categorias[$c]->link	= JRoute::_("index.php?option=com_quickfaq&Itemid=$menu->id&view=category&id=$category->id:$category->alias");
			endforeach;
		endif;
		*/
		
		$query = 'SELECT i.*, (i.plus / (i.plus + i.minus) ) * 100 AS votes, c.access AS cataccess, c.id AS catid, c.published AS catpublished, c.title as cattitle,'
				. ' u.name AS author, u.usertype,'
				. ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(\':\', i.id, i.alias) ELSE i.id END as slug,'
				. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as categoryslug'
				. ' FROM #__quickfaq_items AS i'
				. ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.itemid = i.id'
				. ' LEFT JOIN #__quickfaq_categories AS c ON c.id = rel.catid'
				. ' LEFT JOIN #__users AS u ON u.id = i.created_by'
				. ' WHERE i.state = 1'
				. ' AND c.access <= '.(int) $user->get('aid')
				//. ' '
				;
		$db->setQuery($query);
		$datos->items = $db->loadObjectList();
		if ( count( $datos->items ) ) : 
			foreach( $datos->items as $i => $item ) :
				$datos->items[$i]->link		= JRoute::_("index.php?option=com_quickfaq&Itemid=$menu->id&view=items&cid=$item->categoryslug&id=$item->slug");
			endforeach;
		endif;
		
		shuffle( $datos->items );
		
		// se agregan los scripts necesarios
		//$doc->addScript( 'modules/mod_faq/mod_faq.js' );
		
		return $datos;
	}	
}
