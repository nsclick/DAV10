<?php
/**
 * @version		$Id: do.php 2010-07-28 sgarcia $
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
	

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

//$mainframe->registerEvent( 'onAfterInitialise', 'onSistemaDO' );
$mainframe->registerEvent( 'onAfterRoute', 'onEntradaDO' );
$mainframe->registerEvent( 'onAfterRender', 'onSalidaDO' );
	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The row object.  Note $article->text is also available
	 * @param 	object		The object params
	 * @param 	int			The 'page' number
	 */
	function onEntradaDO()
	{
		global $mainframe, $Itemid;

		$db				=& JFactory::getDBO();
		$session		=& JFactory::getSession();
		$plugin			=& JPluginHelper::getPlugin('system', 'do');
		$pluginParams	= new JParameter( $plugin->params );
		$doc			=& JFactory::getDocument();

		// check whether plugin has been unpublished
		if ( !$pluginParams->get( 'enabled', 1 ) || $mainframe->isAdmin() ) :
			return;
		endif;
		
		// js
		//JHTML::_('do.js');
		//JHTML::_('behavior.mootools');
		//JHTML::_('do.jQuery');
		
		$query = 'SELECT m.id' .
				' FROM #__menu AS m' .
				' WHERE m.published = 1' .
				' AND m.home = 1' .
				' ORDER BY m.id ASC' .
				' LIMIT 0, 1';
		$db->setQuery( $query );
		$menuid		= $db->loadResult();
		
		if( $menuid != $Itemid ) :
			/*$query = 'SELECT m.id' .
					' FROM #__menu AS m' .
					' WHERE m.published = 1' .
					' AND m.alias = \'autoconsulta\'' .
					' ORDER BY m.id ASC' .
					' LIMIT 0, 1';
			$db->setQuery( $query );
			$autoconsultamenuid		= $db->loadResult();
			if( $autoconsultamenuid == $Itemid ) :
				JRequest::setVar('tmpl','autoconsulta');
			else*/if( JRequest::getVar('tmpl','') == '' && JRequest::getCmd('option') != 'com_gpti' ) :
				JRequest::setVar('tmpl','contenido');
			endif;
		endif;
		
		return;
	}
	
	function onSalidaDO()
	{
		$session		=& JFactory::getSession();
		$DO_oci8_link	=& $session->get( 'DO_oci8_link', null );
		if( $DO_oci8_link && is_resource( $DO_oci8_link ) ) :
			oci_close( $DO_oci8_link );
		endif;
	}