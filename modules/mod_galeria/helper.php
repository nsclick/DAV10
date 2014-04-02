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

class modGaleriaHelper
{
	function getDatos( &$params )
	{
		$db						=& JFactory::getDBO();
		$user					=& JFactory::getUser();
		
		$datos					= new stdClass;
		$datos->titulo			= $params->get('titulo','');
		$datos->subtitulo		= $params->get('subtitulo','');
		$datos->seccion			= $params->get('sectionid',0);
		$datos->categoria		= $params->get('catid',0);
		
		$query	= 'SELECT a.id, a.title, a.alias, a.title_alias, a.introtext, a.fulltext, a.sectionid, a.state, a.catid, a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,' .
				' a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.attribs, a.hits, a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access,' .
				' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug,'.
				' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
				' cc.alias as cat_alias, cc.description AS cat_description,'.
				' CHAR_LENGTH( a.`fulltext` ) AS readmore, u.name AS author, u.usertype, cc.title AS category, g.name AS groups, u.email as author_email'.
				' FROM #__content AS a' .
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' LEFT JOIN #__sections AS s ON s.id = a.sectionid' .
				' LEFT JOIN #__users AS u ON u.id = a.created_by' .
				' LEFT JOIN #__groups AS g ON a.access = g.id'.
				' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id'.
				' WHERE a.state = 1 AND a.sectionid = '. (int) $datos->seccion.
				' AND a.catid = '. (int) $datos->categoria.
				' AND f.content_id = a.id'.
				' ORDER BY a.ordering ASC, a.title ASC'
				;// echo "$query<br /><br />";
		$db->setQuery( $query, 0, 1 );
		$row	= $db->loadObject();
		
		require_once( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php' );
		$row->link 				= ContentHelperRoute::getArticleRouteDO($row->id, $row->catid, $row->sectionid, $row->alias, $row->cat_alias);
		
		$patronImg = "(<img[^<>]*/>)";
		if ( ereg( $patronImg, $row->introtext, $regs ) ) :
			$img 			= $regs[1];
			$patronStyle 	= "( style=\"[^<>]*\")";
			$img 			= ereg_replace($patronStyle,"",$img);
			$patronAlign 	= "( align=\"[^<>]*\")";
			$img 			= ereg_replace($patronAlign,"",$img);
			$patronHspace 	= "( hspace=\"[^<>]*\")";
			$img			= ereg_replace($patronHspace,"",$img);
			$patronVspace 	= "( vspace=\"[^<>]*\")";
			$img			= ereg_replace($patronVspace,"",$img);
			$row->img		= $img;
			$row->introtext	= ereg_replace($patronImg,"",$row->introtext);
		endif;
		
		$dispatcher			=& JDispatcher::getInstance();
		JPluginHelper::importPlugin('content');
		$row->text		= $row->fulltext;
		$row->fulltext		= substr( $row->fulltext, 0, 50 ).'...';
		
		$results		= $dispatcher->trigger('onPrepareContentGaleria', array (& $row, $params, 0));
		
		$datos->row			= $row;
		
		$datos->catlink		= ContentHelperRoute::getCategoryRouteDO($row->catid, $row->sectionid, $row->cat_alias);
		
		return $datos;
	}	
}
