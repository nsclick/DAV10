<?php
/**
* @version		$Id: helper.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_banners'.DS.'helpers'.DS.'banner.php');

class modBannersHelper
{
	function getList(&$params)
	{
		$model		= modBannersHelper::getModel();
		$session	= & JFactory::getSession();
		$m			= ( $session->get( 'pltll' ) == '_mayorista' ) ? 1 : 0 ;
		// Model Variables
		$vars['cid']		= (int) $params->get( 'cid' );
		$vars['catid']		= (int) $params->get( 'catid' );
		$vars['limit']		= (int) $params->get( 'count', 1 );
		$vars['ordering']	= $params->get( 'ordering' );

		if ($params->get( 'tag_search' ))
		{
			$document		=& JFactory::getDocument();
			$keywords		=  $document->getMetaData( 'keywords' );

			$vars['tag_search'] = BannerHelper::getKeywords( $keywords );
		}
		
		if( $params->get('template') != 2 )
		{
			if( $params->get('template') == $m )
			{
				$banners = $model->getList( $vars );
			}
		}
		
		$model->impress( $banners );
		return $banners;		
	}

	function getModel()
	{
		if (!class_exists( 'BannersModelBanner' ))
		{
			// Build the path to the model based upon a supplied base path
			$path = JPATH_SITE.DS.'components'.DS.'com_banners'.DS.'models'.DS.'banner.php';
			$false = false;

			// If the model file exists include it and try to instantiate the object
			if (file_exists( $path )) {
				require_once( $path );
				if (!class_exists( 'BannersModelBanner' )) {
					JError::raiseWarning( 0, 'Model class BannersModelBanner not found in file.' );
					return $false;
				}
			} else {
				JError::raiseWarning( 0, 'Model BannersModelBanner not supported. File not found.' );
				return $false;
			}
		}

		$model = new BannersModelBanner();
		return $model;
	}

	function renderBanner( $params, &$item )
	{
		$link = JRoute::_( 'index.php?option=com_banners&task=click&bid='. $item->bid );
		$baseurl = JURI::base();

		$html = '';
		if ( trim($item->custombannercode) )
		{
			// template replacements
			$html = str_replace( '{CLICKURL}', $link, $item->custombannercode );
			$html = str_replace( '{NAME}', $item->name, $html );
		}
		else if (BannerHelper::isImage( $item->imageurl ))
		{
			$image 	= '<img src="'.$baseurl.'images/banners/'.$item->imageurl.'" border="0" alt="'.JText::_('Banner').'" />';
			if ($item->clickurl)
			{
				switch ($params->get( 'target', 1 ))
				{
					// cases are slightly different
					case 1:
						// open in a new window
						$a = '<a href="'. $link .'" target="_blank">';
						break;

					case 2:
						// open in a popup window
						$a = "<a href=\"javascript:void window.open('". $link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\">";
						break;

					default:	// formerly case 2
						// open in parent window
						$a = '<a href="'. $link .'">';
						break;
					}

				$html = $a . $image . '</a>';
			}
			else
			{
				$html = $image;
			}
		}
		else if (BannerHelper::isFlash( $item->imageurl ))
		{
			//echo $item->params;
			$banner_params = new JParameter( $item->params );
			$width = $banner_params->get( 'width');
			$height = $banner_params->get( 'height');
			
				$meta = "_blank";
				$pat = "://";
				if (!eregi( $pat, $item->clickurl )) {
					$meta = "_self";
				}
	
				$imageurl = $baseurl."images/banners/". ereg_replace(".swf","",$item->imageurl);
				$html =	"<script type='text/javascript'>AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,28,0','width','$width','height','$height','title','".$item->name."','src','$imageurl','flashvars','&amp;urlsitio=".JURI::base()."&amp;urlbase=".JURI::base()."&amp;bid=".$item->bid."&amp;bname=".$item->name."&amp;btitle=".$item->name."&amp;meta=$meta&amp;urlimagenes=images/banners/images/&amp;imagenes=1.jpg|2.jpg|3.jpg|4.jpg','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','wmode','transparent','movie','$imageurl' );</script>";
		}

		return $html;
	}
}
