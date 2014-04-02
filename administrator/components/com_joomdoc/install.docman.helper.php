<?php

/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: install.docman.helper.php 651 2008-03-20 20:33:15Z mjaz $
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

if (! defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

require_once (dirname(__FILE__) . DS . 'docman.class.php');

$_DOCMAN = new dmMainFrame();
$_DMUSER = $_DOCMAN->getUser();

require_once ($_DOCMAN->getPath('classes', 'compat'));

define('_DM_INSTALLER_ICONPATH', JURI::root() . 'administrator/components/com_joomdoc/images/');

/**
 * Helper functions for the installer
 * @static
 */
class DMInstallHelper
{
    function checkWritable ()
    {
        $absolute_path = JPATH_ROOT;
        
        $paths = array(DS , DS . 'administrator' . DS . 'modules' . DS , DS . 'plugins' . DS);
        
        clearstatcache();
        $msgs = array();
        foreach ($paths as $path) {
            if (! is_writable($absolute_path . $path)) {
                $msgs[] = '<font color="red">Unwriteable: &lt;joomla root&gt;' . $path . '</font><br />';
            }
        }
        
        if (count($msgs)) {
            echo '<br /><p style="font-size:200%">';
            echo implode("\n", $msgs);
            echo '</p>';
            return false;
        }
        
        return true;
    }
    
    function getDefaultFiles ()
    {
        return array('.htaccess' , 'index.html');
    }
    
    function getComponentId ()
    {
        static $id;
        
        if (! $id) {
            $database = &JFactory::getDBO();
            $database->setQuery("SELECT id FROM #__components WHERE name= 'JoomDOC'");
            $id = $database->loadResult();
        }
        return $id;
    }
    
            
        
    function fileOperations ()
    {
        $mosConfig_absolute_path = JPATH_ROOT;
        $root = $mosConfig_absolute_path;
        $site = $root . DS . 'components' . DS . 'com_joomdoc';
        $admin = $root . DS . 'administrator' . DS . 'components' . DS . 'com_joomdoc';
        $dmdoc = $root . DS . 'joomdocs';
        
        @mkdir($dmdoc, 0755);
        @rename($admin . DS . 'htaccess.txt', $dmdoc . DS . '.htaccess');
        @copy($site . DS . 'index.html', $dmdoc . DS . 'index.html');
        
        @chmod($site, 0755);
        @chmod($admin . DS . 'classes' . DS . 'DOCMAN_download.class.php', 0755);
        @chmod($admin . DS . 'classes' . DS . 'DOCMAN_utils.php', 0755);
    
    }
    
    function showLogo ()
    {
        ?>
<h1><a href="index2.php?option=com_joomdoc" style="margin-left: 23px;">JoomDOC</a></h1>
<?php
    }
    
    function cpanel ()
    {
        $live_site = JURI::root();
        
        ?>
<p style="margin-left: 30px; width: 400px;">Thank you for installing
JoomDOC. You can migrate your data from DOCman 1.0.x installation.
Migration you can start from Controll Panel after save component
configuration.</p>
<div style="clear: both;">
<div style="float: left; margin: 30px"><a
	href="index2.php?option=com_joomdoc" style="text-decoration: none;"> <img
	border="0" align="top" alt=""
	src="<?php
        echo $live_site?>/administrator/components/com_joomdoc/images/dm_cpanel_48.png" />
<br />
<span>Controll Panel</span> </a></div>
<div style="float: left; margin: 30px"><a
	href="index2.php?option=com_joomdoc&amp;task=sampledata"
	style="text-decoration: none;"> <img border="0" align="top" alt=""
	src="<?php
        echo $live_site?>/administrator/components/com_joomdoc/images/dm_newdocument_48.png" />
<br />
<span>Add Sample Data</span> </a></div>
</div>
<?php
    
    }
    
    /**
     * Count items in tables
     */
    function cntDbRecords ()
    {
        $database = &JFactory::getDBO();
        $cnt = array();
        $tables = DMInstallHelper::getTablesList();
        
        foreach ($tables as $table) {
            $database->setQuery("SELECT COUNT(*) FROM `$table`");
            $cnt[] = (int) $database->loadResult();
        }
        
        // count categories
        $database->setQuery("SELECT COUNT(*) FROM `#__categories` WHERE `section` = 'com_joomdoc'");
        $cnt[] = (int) $database->loadResult();
        
        return array_sum($cnt);
    }
    
    function removeTables ()
    {
        $database = &JFactory::getDBO();
        $tables = DMInstallHelper::getTablesList();
        
        foreach ($tables as $table) {
            $database->setQuery("DROP TABLE `$table`");
            $database->query();
        }
    }
    
    function getTablesList ()
    {
        return array('#__joomdoc' , '#__joomdoc_groups' , '#__joomdoc_history' , '#__joomdoc_licenses' , '#__joomdoc_log');
    }
    
    /**
     * Count the number of files in /joomdocs
     */
    function cntFiles ()
    {
        global $_DOCMAN;
        if (! is_object($_DOCMAN)) {
            $_DOCMAN = new dmMainFrame();
        }
        if (! is_object($_DOCMAN)) {
            $_DOCMAN = new dmMainFrame();
        }
        $files = DMInstallHelper::getDefaultFiles();
        $dir = DOCMAN_Compat::mosReadDirectory($_DOCMAN->getCfg('dmpath'));
        return count(array_diff($dir, $files));
    }
    
    function removeDmdocuments ()
    {
        global $_DOCMAN;
        if (! is_object($_DOCMAN)) {
            $_DOCMAN = new dmMainFrame();
        }
        
        $dmpath = $_DOCMAN->getCfg('dmpath');
        
        $files = DMInstallHelper::getDefaultFiles();
        
        foreach ($files as $file) {
            @unlink($dmpath . DS . $file);
        }
        @rmdir($dmpath);
    }
    
    /**
     * Create index.html files
     */
    function createIndex ($path)
    {
        // create index.html in the path
        DMInstallHelper::_createIndexFile($path);
        
        if (! file_exists($path)) {
            return false;
        }
        // create index.html in subdirs
        $handle = opendir($path);
        while ($file = readdir($handle)) {
            if ($file != '.' and $file != '..') {
                $dir = $path . DS . $file;
                if (is_dir($dir)) {
                    DMInstallHelper::createIndex($dir);
                }
            }
        }
    }
    
    function _createIndexFile ($dir)
    {
        @$handle = fopen($dir . DS . 'index.html', 'w');
        @fwrite($handle, 'Restricted access');
    }
    
        
        
    /**
     * Upgrade tables from 1.3rc2/1.4beta2 style to 1.4rc1 style
     */
    function upgradeTables ()
    {
        $database = &JFactory::getDBO();
        $queries = array();
        
        $database->setQuery("SHOW INDEX FROM #__joomdoc");
        $database->query();
        $num_keys = $database->getNumRows();
        switch ($num_keys) {
            case 1: // there's only a primary index, add some more
                $queries[] = "ALTER TABLE `#__joomdoc` ADD INDEX `pub_appr_own_cat_name`  (`published`, `approved`, `dmowner`, `catid`, `dmname`(64))";
                $queries[] = "ALTER TABLE `#__joomdoc` ADD INDEX `appr_pub_own_cat_date`  (`approved`, `published`, `dmowner`, `catid`, `dmdate_published`)";
                $queries[] = "ALTER TABLE `#__joomdoc` ADD INDEX `own_pub_appr_cat_count` (`dmowner`, `published`, `approved`, `catid`, `dmcounter`)";
            // pass through (more can be added later on)
            default:
                break;
        }
        
        foreach ($queries as $query) {
            $database->setQuery($query);
            if (! $database->query()) {
                echo 'Error upgrading tables';
            }
        }
    }
}
