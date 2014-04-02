<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: DOCMAN_button.class.php 561 2008-01-17 11:34:40Z mjaz $
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


if (defined('_DOCMAN_button')) {
    return true;
} else {
    define('_DOCMAN_button', 1);
}

if( defined('_DM_J15') ) {
    // do nothing
} else {
    require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'DOCMAN_jobject.class.php');
}

require_once($_DOCMAN->getPath('classes', 'params'));

/**
 * @abstract
 */
class DOCMAN_Button extends JObject {
    /**
     * @abstract string
     */
	var $name;

    /**
     * @abstract string
     */
    var $text;

    /**
     * @abstract string
     */
    var $link;

    /**
     * @abstract DMmosParameters Object
     */
    var $params;

    /**
     * @constructor
     */
    function __construct($name, $text, $link = '#', $params = null) {
    	$this->name = $name;
        $this->text = $text;
        $this->link = $link;
        if(!is_object($params)) {
        	$this->params = new DMmosParameters('');
        } else {
        	$this->params = & $params;
        }
    }
}