<?php
/**
 * @version		$Id: view.html.php 15181 2010-03-04 23:06:32Z ian $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once (JPATH_COMPONENT.DS.'view.php');

/**
 * HTML View class for the Content component
 *
 * @static
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class ContentViewSection extends ContentView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $Itemid;

		// Initialize some variables
		$user		=& JFactory::getUser();
		$document	=& JFactory::getDocument();
		$db			=& JFactory::getDBO();
		$dispatcher	=& JDispatcher::getInstance();
		
		// Get the page/component configuration
		$params = &$mainframe->getParams();

		// Request variables
		$limit		= JRequest::getVar('limit', $params->get('display_num'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		//parameters
		$intro		= $params->def('num_intro_articles', 	4);
		$leading	= $params->def('num_leading_articles', 	1);
		$links		= $params->def('num_links', 			4);

		$limit	= $intro + $leading + $links;
		JRequest::setVar('limit', (int) $limit);

		// Get some data from the model
		$items		= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$categories	= & $this->get( 'Categories' );
		$section	= & $this->get( 'Section' );
		
		JPluginHelper::importPlugin('content');
		
		/*if( count( $items ) ) :
		echo "1<br />";
			foreach( $items as $i => $item ) :
			echo "2 -> $item->id<br />";*/
			
			//echo "<pre>"; print_r( $items ); echo "</pre>";
			
				if ( count( $categories ) ) : 
					foreach( $categories as $c => $category ) :
						$query	= 'SELECT a.id, a.title, a.alias, a.title_alias, a.introtext, a.fulltext, a.sectionid, a.state, a.catid, a.created, a.created_by, a.created_by_alias, a.modified, a.modified_by,' .
								' a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, a.attribs, a.hits, a.images, a.urls, a.ordering, a.metakey, a.metadesc, a.access,' .
								' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug,'.
								' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
								' CHAR_LENGTH( a.`fulltext` ) AS readmore, u.name AS author, u.usertype, cc.title AS category, g.name AS groups, u.email as author_email'.$voting['select'] .
								' FROM #__content AS a' .
								' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
								' LEFT JOIN #__sections AS s ON s.id = a.sectionid' .
								' LEFT JOIN #__users AS u ON u.id = a.created_by' .
								' LEFT JOIN #__groups AS g ON a.access = g.id'.
								' WHERE a.state = 1 AND a.catid = '. (int) $category->id.
								' ORDER BY a.ordering ASC, a.title ASC'
								; //echo "$query<br /><br />";
						$db->setQuery( $query );
						$categories[$c]->articles	= $db->loadObjectList();
						if( count( $categories[$c]->articles ) ) :
							foreach( $categories[$c]->articles as $a => $article ) :
								$categories[$c]->articles[$a]->link	= JRoute::_("index.php?option=com_content&Itemid=$Itemid&view=article&id=$article->slug&catid=$article->catslug");
								//$categories[$c]->articles[$a]->text = $article->introtext . chr(13).chr(13) . $article->fulltext;
								$article->text		= $article->introtext . chr(13).chr(13) . $article->fulltext;
								$results			= $dispatcher->trigger('onPrepareContent', array ( $article, $params, 0));
								$categories[$c]->articles[$a]->text = $article->text;
							endforeach;
						endif;
					endforeach;
				endif;
			/*endforeach;
		endif;*/
		//exit;
		
		// Create a user access object for the user
		$access					= new stdClass();
		$access->canEdit		= $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn		= $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish		= $user->authorize('com_content', 'publish', 'content', 'all');

		//add alternate feed link
		if($params->get('show_feed_link', 1) == 1)
		{
			$link	= '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}
		
			/*
			 *
			 */
			// parametros - keywords
			$sparams = new JParameter( $section->params, JPATH_COMPONENT_ADMINISTRATOR.DS.'parametros.xml', 'params' );
			if( $sparams->get('plantilla','') ) :
				$tpl	= $sparams->get('plantilla');
				switch( $tpl ) :
					case 'quienessomos' :
						if ( count( $categories ) ) : 
							foreach( $categories as $c => $category ) :
								if( count( $category->articles ) ) :
									foreach( $category->articles as $a => $article ) :
									//<img src="images/stories/quienes_somos/organigrama/mario_rivas.jpg" border="0" alt="Mario Rivas Salinas" title="Mario Rivas Salinas" style="float: left;" />
										$article->introtext	= !$article->introtext && $article->fulltext ? $article->fulltext : $article->introtext;
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
											
											$img			= ereg_replace("(<img)","<img align=\"right\" hspace=\"5\"",$img);
											
											$categories[$c]->articles[$a]->img			= $img;
											$categories[$c]->articles[$a]->introtext	= ereg_replace($patronImg,"",$article->introtext);
										endif;
									endforeach;
								endif;
							endforeach;
						endif;
					break;
					default :
		
						/*$js		= JPATH_COMPONENT.DS.'assets'.DS.'js'.DS.'articulos.js.php';
						if( file_exists( $js ) ) :
							ob_start();
							require_once( $js );
							$javascript	= ob_get_contents();
							ob_end_clean();
							
							$document->addScriptDeclaration($javascript);
						endif;*/

					break;
				endswitch;
			else :
			/*
				$js		= JPATH_COMPONENT.DS.'assets'.DS.'js'.DS.'articulos.js.php';
				if( file_exists( $js ) ) :
					ob_start();
					require_once( $js );
					$javascript	= ob_get_contents();
					ob_end_clean();
					
					$document->addScriptDeclaration($javascript);
				endif;
			*/	
			endif;
		

		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$section->title);
			}
		} else {
			$params->set('page_title',	$section->title);
		}
		$document->setTitle( $params->get( 'page_title' ) );

		// Prepare section description
		$section->description = JHTML::_('content.prepare', $section->description);

		for($i = 0; $i < count($categories); $i++)
		{
			$category =& $categories[$i];
			$category->link = JRoute::_(ContentHelperRoute::getCategoryRoute($category->slug, $category->section).'&layout=default');

			// Prepare category description
			$category->description = JHTML::_('content.prepare', $category->description);
		}

		if ($total == 0) {
			$params->set('show_categories', false);
		}

		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit );

		$this->assign('total',			$total);

		$this->assignRef('items',		$items);
		$this->assignRef('section',		$section);
		$this->assignRef('categories',	$categories);
		$this->assignRef('params',		$params);
		$this->assignRef('user',		$user);
		$this->assignRef('access',		$access);
		$this->assignRef('pagination',	$pagination);

		parent::display($tpl);
	}

	function &getItem( $index = 0, &$params)
	{
		global $mainframe;

		// Initialize some variables
		$user		=& JFactory::getUser();
		$dispatcher	=& JDispatcher::getInstance();

		$SiteName	= $mainframe->getCfg('sitename');

		$task		= JRequest::getCmd('task');

		$linkOn		= null;
		$linkText	= null;

		$item =& $this->items[$index];
		$item->text = $item->introtext;

		// Get the page/component configuration and article parameters
		$item->params = clone($params);
		$aparams = new JParameter($item->attribs);

		// Merge article parameters into the page configuration
		$item->params->merge($aparams);

		// Process the content preparation plugins
		JPluginHelper::importPlugin('content');
		$results = $dispatcher->trigger('onPrepareContent', array (& $item, & $item->params, 0));

		// Build the link and text of the readmore button
		if (($item->params->get('show_readmore') && @ $item->readmore) || $item->params->get('link_titles'))
		{
			// checks if the item is a public or registered/special item
			if ($item->access <= $user->get('aid', 0))
			{
				//$item->readmore_link = JRoute::_("index.php?view=article&id=".$item->slug);
				$item->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid));
				$item->readmore_register = false;
			}
			else
			{
				$item->readmore_link = JRoute::_("index.php?option=com_user&view=login");
				$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid),false);
				$fullURL = new JURI($item->readmore_link);
				$fullURL->setVar('return', base64_encode($returnURL));
				$item->readmore_link = $fullURL->toString();
				$item->readmore_register = true;
			}
		}

		$item->event = new stdClass();
		$results = $dispatcher->trigger('onAfterDisplayTitle', array (& $item, & $item->params,0));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $item, & $item->params, 0));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterDisplayContent', array (& $item, & $item->params, 0));
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		return $item;
	}
}
