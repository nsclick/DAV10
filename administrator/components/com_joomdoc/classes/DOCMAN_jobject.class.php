<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: DOCMAN_jobject.class.php 561 2008-01-17 11:34:40Z mjaz $
 * @package JoomDOC
 * @copyright (C) 2003-2008 The DOCman Development Team
 *            Improved to JoomDOC by Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );


if (defined('_DOCMAN_jobject')) {
    return true;
} else {
    define('_DOCMAN_jobject', 1);
}

// this file contains the JBrowser class from Joomla! 1.5


if( !class_exists('JObject') ) {

    /**
     * Object class, allowing __construct in PHP4.
     *
     * @author      Johan Janssens <johan.janssens@joomla.org>
     * @package     Joomla.Framework
     * @subpackage  Base
     * @since       1.5
     */
    class JObject
    {
        /**
         * A hack to support __construct() on PHP 4
         * Hint: descendant classes have no PHP4 class_name() constructors,
         * so this constructor gets called first and calls the top-layer __construct()
         * which (if present) should call parent::__construct()
         *
         * @return Object
         */
        function JObject()
        {
            $args = func_get_args();
            call_user_func_array(array(&$this, '__construct'), $args);
        }

        /**
         * Class constructor, overridden in descendant classes.
         *
         * @access  protected
         */
        function __construct() {}

        /**
         * Modifies a property of the object, creating it if it does not already exist.
         *
         * @param string $property The name of the property
         * @param mixed  $value The value of the property to set
         */
        function set( $property, $value=null ) {
            $this->$property = $value;
        }

        /**
         * Returns a property of the object or the default value if the property is not set.
         *
         * @param string $property The name of the property
         * @param mixed  $default The default value
         * @return mixed The value of the property
         * @see get(), getPublicProperties()
         */
        function get($property, $default=null)
        {
            if(isset($this->$property)) {
                return $this->$property;
            }
            return $default;
        }

        /**
         * Returns an array of public properties
         *
         * @param   boolean $assoc If true, returns an associative key=>value array
         * @return  array
         * @see get(), toString()
         */
        function getPublicProperties( $assoc = false )
        {
            $vars = array(array(),array());
            foreach (get_object_vars( $this ) as $key => $val)
            {
                if (substr( $key, 0, 1 ) != '_')
                {
                    $vars[0][] = $key;
                    $vars[1][$key] = $val;
                }
            }
            return $vars[$assoc ? 1 : 0];
        }

        /**
         * Object-to-string conversion.
         * Each class can override it as necessary.
         *
         * @return string This name of this class
         * @see get(), getPublicProperties()
         */
        function toString()
        {
            return get_class($this);
        }
    }

}