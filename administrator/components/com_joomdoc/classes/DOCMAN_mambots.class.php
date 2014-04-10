<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: DOCMAN_mambots.class.php 561 2008-01-17 11:34:40Z mjaz $
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

if (defined('_DOCMAN_MAMBOT')) {
    return;
} else {
    define('_DOCMAN_MAMBOT', 1);
}

/**
* DOCMAN Mambots Class
*
* @desc class purpose is to handle Mambot interactions
*/

class DOCMAN_mambot {
    var $_parms;
    var $_ds;
    var $_error;
    var $_errmsg;
    var $_group;
    var $_trigger;
    var $_pub;
    var $_return;

    function DOCMAN_mambot($trigger = null, $group = 'joomdoc', $pub = false)
    {
        $this->_ds = array();
        $this->_group = $group;
        $this->_trigger = $trigger;
        $this->_pub = $pub;
        $this->_error = null;

        $this->_parms = array();
    }
    // Set all values to array
    function setParmArray(&$name)
    {
        foreach($name as $key => $value) {
            $this->_parms[ $key ] = &$name[$key];
        }
    }
    function setParm($name, &$getFirst)
    {
        if (is_array($name)) {
            $this->_parms += $name ;
        } else {
            if ($getFirst == null) {
                $this->_parms[ $name ] = null ;
            } else {
                $this->_parms[ $name ] = &$getFirst ;
            }
        }
    }

    function copyParm($name, $getFirst = null)
    {
        if (is_array($name)) {
            $this->_parms += $name ;
        } else {
            if ($getFirst == null) {
                $this->_parms[ $name ] = null ;
            } else {
                $this->_parms[ $name ] = $getFirst ;
            }
        }
    }

    function &getParm($name = null)
    {
        if (is_null($name)) {
            return $this->_parms;
        }
        return $this->_parms[ $name ];
    }

    function getError()
    {
        return (is_null($this->_error) ? 0: $this->_error);
    }
    function getErrorMsg()
    {
        return (is_null($this->_errmsg) ? 0: $this->_errmsg);
    }

    /**
    *
    * @desc Get the first occurance of a key
    * @param string $name the name to look for
    * @return Single value
    */
    function getFirst($name)
    {
        if (is_array($this->_return)) {
            foreach($this->_return as $row) {
                if (is_array($row) &&
                        array_key_exists($name , $row)) {
                    return $row[$name];
                }
            }
        }
        return null;
    }

    /**
    *
    * @desc This returns all the strings from all mambots
    * 		that returned a value of 'name'
    * @param string $name the name to look for
    *                 int the category id
    * @return array An array of ALL the matching entrys
    */

    function getAll($name)
    {
        if (is_array($this->_return)) {
            $all = array();
            foreach($this->_return as $row) {
                if (is_array($row) &&
                        array_key_exists($name , $row)) {
                    $all[] = $row[$name];
                }
            }
            return count($all)? $all: null;
        }
        return null;
    }

    /**
    *
    * @desc This performs the MAMBOT call and interfaces
    * 		what we want with what Mambo does
    * @param string $trigger the trigger to call.
    * @param boolean $pub - whether to call unpublished routines
    * @return boolean True or false if an error occured
    */

    function trigger($trigger = null , $pub = false) {

        global $_MAMBOTS, $mainframe;

        $trigger = $trigger ? $trigger : $this->_trigger;
        if ($trigger == null || ! $this->_group) {
            $this->_error = 1;
            $this->_errmsg = _DML_INTERRORMAMBOT;
            return false;
        }

        $task =  isset($_GET['task']) ? $_GET['task'] : 'unknow';

        // Set required parms
        $this->_parms += array('content_src' => 'joomdoc',
            'task' => $task,
            'mambo_ds' => &$this->_ds);

        if(defined('_DM_J15')) {
            JPluginHelper::importPlugin('joomdoc');
            $this->_return = $mainframe->triggerEvent( $trigger, array($this->_parms), $pub);
        } else {
            $_MAMBOTS->loadBotGroup($this->_group);
            $this->_return = $_MAMBOTS->trigger($trigger, array($this->_parms), $pub);
        }

        $this->_error = $this->getFirst('_error');
        $errmsg = $this->getAll('_errmsg');
        if ($errmsg) {
            $this->_errmsg = implode('\n' , $errmsg);
        }
        return $this->getError();
    }

    function & getReturn() {
    	return $this->_return;
    }
}

