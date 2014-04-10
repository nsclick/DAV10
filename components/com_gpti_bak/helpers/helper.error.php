<?php
/**
 * @version		$Id: helper.error.php 2011-05-20 Sebastián García Truan $
 * @package		Joomla
 * @subpackage	GPTI
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2011 Diseño Objetivo. Todos los derechos reservados.
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
defined( '_JEXEC' ) or die( 'El acceso directo a este archivo no está permitido.' );
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

class GPTIHelperError
{
	function Raise( $errormsj=null )
	{
		JRequest::setVar('tmpl', 'error');
		echo $errormsj;
		$session		=& JFactory::getSession();
		$session->set('GPTI_error', $errormsj);
	}	
}
?>