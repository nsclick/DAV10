<?php
/**
 * @version $Id: format.php 89 2006-11-16 10:34:48Z alex $
 * @package Joomla
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * Abstract Format for JRegistry
 *
 * @abstract
 * @author 		Samuel Moffatt <pasamio@gmail.com>
 * @package 	Joomla.Framework
 * @subpackage 	Registry
 * @since		1.5
 */
class JRegistryFormat
{
	/**
	 * Returns a reference to a Format object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @param 	string 	$format	 The format to load
	 * @return 	object 	Registry format handler
	 */
	function &getInstance($format)
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty ($instances[$format])) {
			$adapter = 'JRegistryFormat'.$format;
			require_once(  JREGISTRY_PATH .DS.'format'.DS.strtolower($format).'.php' );
			$instances[$format] = new $adapter ();
		}
		return $instances[$format];
	}


	/**
	 * Converts an XML formatted string into an object
	 *
	 * @abstract
	 * @access public
	 * @param string  XML Formatted String
	 * @return object Data Object
	 */
	function stringToObject( $data, $namespace='' ) {
		return true;
	}

	/**
	 * Converts an object into an formatted string
	 *
	 * @abstract
	 * @access public
	 * @param object $object Data Source Object
	 * @return string XML Formatted String
	 */
	function objectToString( &$object ) {

	}
}
