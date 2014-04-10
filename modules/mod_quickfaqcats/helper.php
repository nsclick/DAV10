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

class modQuickFaqCatsHelper
{
	function getDatos( &$params )
	{
		$datos					= new stdClass;
		$datos->valido			= true;
		$db						=& JFactory::getDBO();
		$user					=& JFactory::getUser();
		$doc					=& JFactory::getDocument();
		$option					= JRequest::getCmd('option');
		$view					= JRequest::getCmd('view');
		$id						= JRequest::getInt('id',0,'request');
		
		if( $view == 'items' || $view == 'category' ) :
			$datos->valido		= false;
			return $datos;
		endif;
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $params->get('menu') );
		$datos->link			= JRoute::_( $menu->link."&Itemid=$menu->id" );
		
		// Query of categories within section
		$query = 'SELECT a.id, a.alias, a.title AS cattitle' .
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
				$category->slug		= $category->id.':'.$category->alias;
				$query = 'SELECT i.id, i.title, i.alias, i.introtext'
				//. ' CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\', t.id, t.alias) ELSE t.id END as slug'
				. ' FROM #__quickfaq_items AS i'
				. ' LEFT JOIN #__quickfaq_cats_item_relations AS r ON i.id = r.itemid'
				. ' WHERE r.catid = ' . (int) $category->id
				. ' AND i.state = 1'
				. ' ORDER BY i.ordering'
				;
				$db->setQuery( $query );
				$datos->categorias[$c]->articles	= $db->loadObjectList();
				if( count( $datos->categorias[$c]->articles ) ) :
					foreach( $datos->categorias[$c]->articles as $a => $article ) :
						$patronImg = "(<img[^<>]*/>)";
						if ( ereg( $patronImg, $article->introtext, $regs ) ) :
							$img 			= $regs[1];
							$patronStyle 	= "( style=\"[^<>]*\")";
							$img 			= ereg_replace($patronStyle,"",$img);
							$patronAlign 	= "( align=\"[^<>]*\")";
							$img 			= ereg_replace($patronAlign,"",$img);
							$patronHspace 	= "( hspace=\"[^<>]*\")";
							$img			= ereg_replace($patronHspace,"",$img);
							$patronVspace 	= "( vspace=\"[^<>]*\")";
							$img			= ereg_replace($patronVspace,"",$img);
							$datos->categorias[$c]->articles[$a]->img			= $img;
							$datos->categorias[$c]->articles[$a]->introtext	= ereg_replace($patronImg,"",$article->introtext);
						endif;
						$datos->categorias[$c]->articles[$a]->link	= JRoute::_("index.php?option=com_quickfaq&Itemid=$menu->id&view=items&cid=$category->slug&id=$article->id:$article->alias");
					endforeach;
				endif;
			endforeach;
		endif;
		
		$js		= JPATH_BASE.DS.'modules'.DS.'mod_quickfaqcats'.DS.'mod_quickfaqcats.js.php';
		if( file_exists( $js ) ) :
			ob_start();
			require_once( $js );
			$javascript	= ob_get_contents();
			ob_end_clean();
			
			JHTML::_('behavior.mootools');
			$doc->addScriptDeclaration($javascript);
		endif;
				
		return $datos;
	}	
}
