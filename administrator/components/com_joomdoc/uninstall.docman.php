<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: uninstall.docman.php 561 2008-01-17 11:34:40Z mjaz $
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
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__) . DS . 'install.docman.helper.php');
require_once (dirname(__FILE__) . DS . 'helpers' . DS . 'factory.php');
require_once (dirname(__FILE__) . DS . 'alibraries' . DS . 'installer.php');

function com_uninstall ()
{
    
    AInstaller::uninstall();
    
    // if there's no more data, we remove the tables
    if (DMInstallHelper::cntDbRecords() == 0) {
        DMInstallHelper::removeTables();
    }
    
    // delete the joomdocs folder if it's empty
    if (DMInstallHelper::cntFiles() == 0) {
        DMInstallHelper::removeDmdocuments();
    }
}