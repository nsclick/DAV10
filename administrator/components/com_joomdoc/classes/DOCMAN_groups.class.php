<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: DOCMAN_groups.class.php 631 2008-02-25 19:13:33Z mjaz $
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

if (defined('_DOCMAN_GROUPS')) {
    return true;
} else {
    define('_DOCMAN_GROUPS', 1);
}

class DOCMAN_groups {

    /**
     * Provides a list of all groups
     *
     * @deprecated
     */
    function & getList() {
        static $groups;

        if( !isset( $groups )) {
            $database = &JFactory::getDBO();
            $database->setQuery("SELECT groups_id, groups_name "
             . "\n  FROM #__joomdoc_groups "
             . "\n ORDER BY groups_name ASC");
            $groups = $database->loadObjectList();
        }

        return $groups;
    }

    /**
     * Get a group object, caches results
     */
    function & get($id)
    {
        static $groups;

        if( !isset( $groups )) {
            $groups = array();
        }

        if( !isset( $groups[$id] )) {
            $database = &JFactory::getDBO();
            $groups[$id] = new mosDMGroups($database);
            $groups[$id]->load($id);
        }

        return $groups[$id];
    }
}