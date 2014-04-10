<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: installer.php 608 2008-02-18 13:31:26Z mjaz $
 * @package JoomDOC
 * @copyright (C) 2009 ARTIO s.r.o
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
define('AINSTALLER_INSTALL', 1);
define('AINSTALLER_UNINSTALL', 2);

require_once (JPATH_ROOT . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table' . DS . 'module.php');
require_once (JPATH_ROOT . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table' . DS . 'plugin.php');
require_once (JPATH_ROOT . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table.php');

class AInstaller
{

    function install ()
    {
        if ($data = AInstaller::browsePackages(AINSTALLER_INSTALL)) {
            AInstaller::setMsg('Install', $data);
        }
    }

    function uninstall ()
    {
        if ($data = AInstaller::browsePackages(AINSTALLER_UNINSTALL)) {
            AInstaller::setMsg('Uninstall', $data);
        }
    }

    function setMsg ($operation, $datas)
    {
        global $mainframe;
        foreach ($datas as $data) {
            if (is_array($data)) {
                if ($data['outcome']) {
                    $outcome = JText::_('Success');
                    $msgType = 'message';
                } else {
                    $outcome = JText::_('Unsuccess');
                    $msgType = 'error';
                }
                $mainframe->enqueueMessage(JText::_($operation) . ' ' . ucfirst($data['extType']) . ' ' . $data['title'] . ' ' . JText::_($outcome), $msgType);
            }
        }
    }

    function browsePackages ($type)
    {
        $root = dirname(__FILE__) . DS . '..' . DS . 'extensions';
        $packages = JFolder::folders($root, '.', false, true);
        $outcome = array();
        foreach ($packages as $package) {
            switch ($type) {
                case AINSTALLER_INSTALL:
                    $outcome[] = AInstaller::installPackage($package);
                    break;
                case AINSTALLER_UNINSTALL:
                    $outcome[] = AInstaller::uninstallPackage($package);
                    break;
            }
        }
        return $outcome;
    }

    function installPackage ($package)
    {
        $installer = new JInstaller();
        if ($installer->install($package)) {
            $extension = AInstaller::loadExtension($package);
            if (is_object($extension)) {
                $extType = $extension->extType;
                unset($extension->extType);
                $extension->store();
                $manifest = AInstaller::getManifest($package);
                if ($manifest !== false) {
                    $files = JFolder::files($package, '.', false, true);
                    $folders = JFolder::folders($package, '.', false, true);
                    foreach ($files as $file) {
                        if ($file != $manifest->source) {
                            JFile::delete($file);
                        }
                    }
                    foreach ($folders as $folder) {
                        JFolder::delete($folder);
                    }
                
                } else {
                    JFolder::delete($package);
                }
                $title = AInstaller::getExtensionTitle($extension);
                return array('title' => $title , 'extType' => $extType , 'outcome' => true);
            }
        }
        return false;
    }

    function uninstallPackage ($package)
    {
        $extension = AInstaller::loadExtension($package);
        if (is_object($extension) && $extension->id) {
            $installer = new JInstaller();
            $success = $installer->uninstall($extension->extType, $extension->id, $extension->client_id);
            $title = AInstaller::getExtensionTitle($extension);
            return array('title' => $title , 'extType' => $extension->extType , 'outcome' => $success);
        }
        return false;
    }

    function loadExtension ($package)
    {
        $manifest = AInstaller::getManifest($package);
        $db = JFactory::getDBO();
        if ($manifest !== false) {
            $root = $manifest->parser->document;
            $element = $root->getElementByPath('files');
            if (is_object($element)) {
                $files = & $element->children();
                $type = $root->attributes('type');
                foreach ($files as $file) {
                    $name = $file->attributes($type);
                    if ($name) {
                        $classname = 'AInstaller' . ucfirst($type);
                        if (class_exists($classname)) {
                            $model = new $classname();
                            $extension = $model->getTable();
                            $query = $model->getQuery($name, $root);
                            $db->setQuery($query);
                            $id = (int) $db->loadResult();
                            if ($id) {
                                $extension->load($id);
                                if (isset($extension->iscore) && (int) $extension->iscore != 0) {
                                    $extension->iscore = 0;
                                    $extension->store();
                                }
                            }
                            $setting = $root->getElementByPath('setting');
                            if (is_object($setting)) {
                                $atributes = $setting->attributes();
                                foreach ($atributes as $name => $value) {
                                    $extension->$name = $value;
                                }
                            }
                            $extension->extType = $type;
                            return $extension;
                        }
                    }
                }
            }
        }
        return null;
    }

    function getExtensionTitle ($extension)
    {
        if (isset($extension->title)) {
            return $extension->title;
        } elseif (isset($extension->name)) {
            return $extension->name;
        }
    }

    function getManifest ($package)
    {
        $sources = JFolder::files($package, '.xml$', false, true);
        $source = reset($sources);
        if ($source !== false) {
            $manifest = new stdClass();
            $manifest->source = $source;
            $manifest->parser = JFactory::getXMLParser('Simple');
            if ($manifest->parser->loadFile($source)) {
                return $manifest;
            }
        }
        return false;
    }
}

/*
interface AInstallerExtension
{
    
    function getTable ();

    function getQuery ($name, $root);
}
*/
class AInstallerModule /* implements AInstallerExtension*/
{

    function getTable ()
    {
        $db = &JFactory::getDBO();
        return new JTableModule($db);
    }

    function getQuery ($name, $root)
    {
        $db = &JFactory::getDBO();
        return 'SELECT id FROM #__modules WHERE module LIKE ' . $db->Quote($name) . ' LIMIT 1';
    }
}

class AInstallerPlugin /* implements AInstallerExtension*/
{

    function getTable ()
    {
        $db = &JFactory::getDBO();
        return new JTablePlugin($db);
    }

    function getQuery ($name, $root)
    {
        $db = &JFactory::getDBO();
        $group = $root->attributes('group');
        return 'SELECT id FROM #__plugins WHERE element LIKE ' . $db->Quote($name) . ' AND folder LIKE ' . $db->Quote($group) . ' LIMIT 1';
    }
}

?>