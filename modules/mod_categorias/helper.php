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

class modCategoriasHelper
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
		
		if( $option != 'com_content' || $view != 'section' ) :
			$datos->valido		= false;
			return $datos;
		endif;
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $params->get('menu') );
		$datos->link			= JRoute::_( $menu->link."&Itemid=$menu->id" );
		
		// Query of categories within section
		$query = 'SELECT a.id, a.name AS catname, a.title AS cattitle' .
			' FROM #__categories AS a' .
			//' INNER JOIN #__content AS b ON b.catid = a.id' .
			//' AND b.state = 1' .
			//' AND ( b.publish_up = '.$db->Quote($nullDate).' OR b.publish_up <= '.$db->Quote($now).' )' .
			//' AND ( b.publish_down = '.$db->Quote($nullDate).' OR b.publish_down >= '.$db->Quote($now).' )';
			' WHERE a.section = ' . $db->Quote($id) .
			' AND a.published = 1' .
			' AND a.access <= '.(int) $user->get('aid') .
			' ORDER BY a.ordering';
		$db->setQuery($query);
		$datos->categorias = $db->loadObjectList();
		if ( count( $datos->categorias ) ) : 
			foreach( $datos->categorias as $c => $category ) :
				$query	= 'SELECT a.id, a.title, a.alias, a.title_alias, a.introtext, a.fulltext, a.sectionid, a.state, a.catid, a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,' .
						' a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.attribs, a.hits, a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access,' .
						' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug,'.
						' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
						' CHAR_LENGTH( a.`fulltext` ) AS readmore, u.name AS author, u.usertype, cc.title AS category, g.name AS groups, u.email as author_email'.
						' FROM #__content AS a' .
						' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
						' LEFT JOIN #__sections AS s ON s.id = a.sectionid' .
						' LEFT JOIN #__users AS u ON u.id = a.created_by' .
						' LEFT JOIN #__groups AS g ON a.access = g.id'.
						' WHERE a.state = 1 AND a.catid = '. (int) $category->id.
						' ORDER BY a.ordering ASC, a.title ASC'
						; //echo "$query<br /><br />";
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
						$datos->categorias[$c]->articles[$a]->link	= JRoute::_("index.php?option=com_content&Itemid=$Itemid&view=article&id=$article->slug&catid=$article->catslug");
					endforeach;
				endif;
			endforeach;
		endif;
		
		$js		= JPATH_BASE.DS.'modules'.DS.'mod_categorias'.DS.'mod_categorias.js.php';
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
