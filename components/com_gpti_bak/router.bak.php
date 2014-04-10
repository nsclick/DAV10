<?php
/**
 * @version		$Id: router.php 2010-06-07 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*     			www.do.cl    	  	  */
	/*   		 contacto@do.cl  		  */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// sin acceso directo
defined ( '_JEXEC' ) or die ( 'Restricted access' );

class DoRouterHelper {
    function getDoc($id) {

        static $docs;

        if(!isset($docs)) {
        	$docs = array();
        }

    	if(!isset($docs[$id])) {
            $docs[$id] = false;
            $db = & JFactory::getDBO();
    		//$docs[$id] = new mosDMDocument($db);
            //$docs[$id]->load($id);
        }

        return $docs[$id];
    }
}


function GPTIBuildRoute(&$query) {
    jimport('joomla.filter.output');


    $segments = array();

    // check for c=...
    
	if(isset($query['c'])) {
        $segments[] = $query['c'];
    }
    // check for task=...
    if(isset($query['task'])) {
        $segments[] = $query['task'];
    }
	
	switch( $query['c'].$query['task'] )
	{
		case 'requerimientosver' :
			// check for dia=...
			if(!isset($query['id'])) {
				break;
			}
			$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
			$REQ->get( (int)$query['id'] );

			$segments[] = $REQ->REQ_ID.':'.JFilterOutput::stringURLSafe($REQ->REQ_NOMBRE);
			
		break;
		/*
		case 'productos' :
		case 'cotizacionescarro_agregar' :
			// check for dia=...
			if(!isset($query['id'])) {
				break;
			}
			$row		=& JTable::getInstance('productos', 'DO');
			$row->load( $query['id'] );
			$segments[] = $row->id.':'.JFilterOutput::stringURLSafe($row->nombre);
		break;
		case 'cotizacioneseditar' :
			// check for dia=...
			if(!isset($query['id'])) {
				break;
			}
			$cotizaciones		=& JTable::getInstance('cotizaciones', 'DO');
			$row				= $cotizaciones->cargar( $query['id'] );
			$segments[] = $row->id.':'.JFilterOutput::stringURLSafe($row->cliente);
		break;
		*/
		//case 'cotizaciones' :
			
		//break;
	}

	unset($query['c']);
    unset($query['task']);
    unset($query['id']);
	
    return $segments;
}

function GPTIParseRoute($segments){
    $vars = array();

    //Get the active menu item
    $menu =& JSite::getMenu();
    $item =& $menu->getActive();

    // Count route segments
    if(!($count = count($segments))) {
        return $vars;
    }

    if( isset($segments[0]) ) {
        $vars['c'] = $segments[0];
		
		switch( $vars['c'] ) :
			/*
			case 'requerimientos'	:
				$vars['id']		= ereg(":",$segments[1]) ? substr($segments[1],0,strpos($segments[1],":")) : substr($segments[1],0,strpos($segments[1],"-"));
			break;
			
			case 'productos'	:
				$vars['id']		= ereg(":",$segments[1]) ? substr($segments[1],0,strpos($segments[1],":")) : substr($segments[1],0,strpos($segments[1],"-"));
			break;
			*/
			case 'requerimientos' :
				if( count( $segments ) > 2 ) :
					$vars['task']	= $segments[1];
					$vars['id']		= ereg(":",$segments[2]) ? substr($segments[2],0,strpos($segments[2],":")) : substr($segments[2],0,strpos($segments[2],"-"));
				else :
					$vars['task']	= $segments[1];
				endif;
			break;
			
		endswitch;
		/*
		switch( $vars['task'] )
		{
			case 'dia'	:
				$vars['dia']	= str_replace( ":", "-", $segments[1] );
			break;
			case 'ver'	:
				$vars['id']		= $segments[1];
			break;
		}

        if(in_array($segments[0], array('cat_view', 'upload'))) {
            $vars['gid'] = (int) $segments[$count-1];
    	} else {
            $vars['gid'] = isset($segments[1]) ? (int) $segments[1] : 0;
        }
		*/
    }

    return $vars;
}