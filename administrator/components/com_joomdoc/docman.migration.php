<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: docman.migration.php 1 2009-09-01 11:34:40Z j.trumpes $
 * @package JoomDOC
 * @copyright (C) 2009 Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/

class DMMigration
{
    /**
     * Migration data from old DOCman to DOCman 2
     *
     */
    private $categoriesIds;
    private $docsIds;
    private $groupsIds;
    private $licencesIds;
    private $logsIds;
    private $historyIds;
    
    function __construct ()
    {
        $this->searchOldDocman();
        $this->init();
    }
    
    function searchOldDocman ()
    {
        $oldDocmanABase = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_docman' . DS . 'docman.config.php';
        if (! file_exists($oldDocmanABase)) {
            $this->error(_DM_MGR_OLD_INSTALL_NO_EXISTS, false);
        }
        global $_DOCMAN;
        $newDmPath = $_DOCMAN->getCfg('dmpath');
        if (! $newDmPath) {
            $this->error(_DM_MGR_NEW_DMPATH_NOT_SET, false);
        }
        if (! file_exists($newDmPath)) {
            mkdir($newDmPath, 0777);
            if (! file_exists($newDmPath)) {
                $this->error(_DM_MGR_DIR_NEW_NO_EXISTS . $newDmPath, false);
            }
        } elseif (! is_writable($newDmPath)) {
            $this->error(_DM_MGR_DIR_IS_UNWRITEABLE . $newDmPath, false);
        }
        return true;
    }
    
    /**
     * Init aplication
     *
     */
    private function init ()
    {
        $this->categoriesIds = array();
        $this->docsIds = array();
        $this->groupsIds = array();
        $this->licencesIds = array();
        $this->logsIds = array();
        $this->historyIds = array();
    }
    /**
     * Clean migrating after error
     *
     */
    private function clean ()
    {
        $this->cleanCategories();
        $this->cleanDocs();
        $this->cleanGroups();
        $this->cleanLicenses();
        $this->cleanLog();
    }
    /**
     * Do migration
     *
     */
    function migration ()
    {
        $this->tablesExists(true);
        $this->migrateLicences();
        $this->migrateGroups();
        $this->migrateCategories();
        $this->migrateFiles();
        $this->back(_DM_MGR_SUCCESS);
    }
    /**
     * Migrate categories with all documents
     *
     * @param array $licencesIds key is old id of licence, value is new id after migration 
     * @param array $groupsIds key is old id of group, value is new id after migration
     */
    private function migrateCategories ()
    {
        $db = &JFactory::getDBO();
        $categories = $this->getCategories(); //with section com_docman
        foreach ($categories as $category) {
            $row = new mosDMCategory($db);
            if (! $row->bind($category)) {
                $this->error($row->getError);
            }
            $oldCatid = $row->id;
            $row->id = 0;
            $row->section = 'com_joomdoc'; //migrate for DOCman 2
            if (! $row->store()) {
                $this->error($row->getError);
            }
            $newCatid = $row->id;
            $this->categoriesIds[$oldCatid] = $newCatid;
            $this->migrateDocs($oldCatid, $newCatid);
        }
        
        foreach ($this->categoriesIds as $old => $new) { //update parent ids
            $query = "UPDATE `#__categories` SET
				      `parent_id` = '$new'
				      WHERE `parent_id` = '$old'
				      AND `section` = 'com_joomdoc'";
            $db->setQuery($query);
            $db->query();
        }
    }
    /**
     * Migrate documents from #__docman to #__joomdoc with all history and logs rows
     *
     * @param int $oldCatid old category id
     * @param int $newCatid new category id after migration
     * @param array $licencesIds key is old id of licence, value is new id after migration 
     * @param array $groupsIds key is old id of group, value is new id after migration
     */
    private function migrateDocs ($oldCatid, $newCatid)
    {
        $db = &JFactory::getDBO();
        $docs = $this->getDocs($oldCatid); //from #__docman with old category
        foreach ($docs as $doc) {
            $row = new mosDMDocument($db);
            if (! $row->bind($doc)) {
                $this->error($row->getError);
            }
            $oldDocId = $row->id;
            $row->id = 0;
            $row->catid = $newCatid; //migrated into migrate category
            if (isset($this->licencesIds[$row->dmlicense_id])) { //change id of migrate license
                $row->dmlicense_id = $this->licencesIds[$row->dmlicense_id];
            }
            if (isset($this->groupsIds[$row->dmowner])) { //change id of migrate owners group
                $row->dmowner = $this->groupsIds[$row->dmowner];
            }
            if (isset($this->groupsIds[$row->dmmantainedby])) { //change id of migrate maintainer group
                $row->dmmantainedby = $this->groupsIds[$row->dmmantainedby];
            }
            if (! $row->store()) { //into #__joomdoc
                $this->error($row->getError);
            }
            $newDocId = $row->id;
            $this->docsIds[$oldDocId] = $newDocId;
            $this->migrateHistory($newDocId, $oldDocId);
            $this->migrateLogs($newDocId, $oldDocId);
        }
    }
    /**
     * Migrate licences from #__docman_licenses table to #__joomdoc_licenses
     *
     * @return array key is old id of licence, value is new id after migration
     */
    private function migrateLicences ()
    {
        $db = &JFactory::getDBO();
        $licences = $this->getLicenses(); //from #__docman_licenses 
        foreach ($licences as $licence) {
            $row = new mosDMLicenses($db);
            if (! $row->bind($licence)) {
                $this->error($row->getError);
            }
            $oldId = $row->id;
            $row->id = 0;
            if (! $row->store()) { //into #__joomdoc_licenses
                $this->error($row->getError);
            }
            $this->licencesIds[$oldId] = $row->id; //save old and new id to change in documents
        }
    }
    /**
     * Migrate groups from #__docman_groups table to #__joomdoc_groups 
     *
     * @return array key is old id of group, value is new id after migration
     */
    private function migrateGroups ()
    {
        $db = &JFactory::getDBO();
        $groups = $this->getGroups(); //from #__docman_groups 
        foreach ($groups as $group) {
            $row = new mosDMGroups($db);
            if (! $row->bind($group)) {
                $this->error($row->getError);
            }
            $oldId = $row->getId();
            $row->groups_id = 0;
            if (! $row->store()) { //into #__joomdoc_groups
                $this->error($row->getError);
            }
            $this->groupsIds[$oldId] = $row->getId(); //save old and new id to change in documents
        }
    }
    /**
     * Migrate Logs from #__docman_log to #__joomdoc_log
     *
     * @param int $newId new id of document after migration
     * @param int $oldId old id before migration
     */
    private function migrateLogs ($newId, $oldId)
    {
        $db = &JFactory::getDBO();
        $logs = $this->getLog($oldId); //from #__docman_log
        foreach ($logs as $log) {
            $row = new mosDMLog($db);
            if (! $row->bind($log)) {
                $this->error($row->getError);
            }
            $oldLogId = $row->id;
            $row->id = 0;
            $row->log_docid = $newId;
            if (! $row->store()) { //into #__joomdoc_log
                $this->error($row->getError);
            }
            $this->logsIds[$oldLogId] = $row->id;
        }
    }
    /**
     * Migrate History from #__docman_history to #__joomdoc_history
     *
     * @param int $newId new id of document after migration
     * @param int $oldId old id before migration
     */
    private function migrateHistory ($newId, $oldId)
    {
        $db = &JFactory::getDBO();
        $histories = $this->getHistory($oldId); //from #__docman_history 
        foreach ($histories as $history) {
            $row = new mosDMHistory($db);
            if (! $row->bind($history)) {
                $this->error($row->getError);
            }
            $oldHistoryId = $row->id;
            $row->id = 0;
            $row->doc_id = $newId;
            if (! $row->store()) { //into #__joomdoc_history
                $this->error($row->getError);
            }
            $this->historyIds[$oldHistoryId] = $row->id;
        }
    }
    /**
     * Check if all tables needs for migration exists
     *
     * @param boolean $msg show error if any table no exist
     * @return boolean true - all tables exists
     */
    private function tablesExists ($msg = false)
    {
        $db = JFactory::getDBO();
        //DOCman tables
        $tables = array('categories' , 'docman' , 'docman_groups' , 'docman_history' , 'docman_licenses' , 'docman_log');
        //DB config prefix
        $prefix = $db->_table_prefix;
        //list of noexists tables    	
        $noExists = array();
        foreach ($tables as $table) {
            $realName = $prefix . $table; // #__docman => jos_docman
            $query = "SHOW TABLES LIKE '$realName'";
            $db->setQuery($query);
            $exists = $db->loadResultArray();
            if (! count($exists)) {
                $noExists[] = $realName;
            }
        }
        if ($msg && count($noExists)) {
            $this->error(_DM_MGR_TABLES_NO_EXISTS . implode(', ', $noExists));
        }
        return (! count($noExists));
    }
    /**
     * Get all old DOCman categories
     *
     * @return array
     */
    private function getCategories ()
    {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM `#__categories` WHERE `section` = 'com_docman'";
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        if (is_null($categories)) {
            $this->error(_DM_MGR_ERR_LOAD_OLD_CATEGORIES);
        }
        return $categories;
    }
    /**
     * Clean all migrated categories after error
     *
     */
    private function cleanCategories ()
    {
        $db = &JFactory::getDBO();
        $query = "DELETE FROM `#__categories` WHERE `id` IN (" . implode(',', $this->categoriesIds) . ")";
        $db->setQuery($query);
        $db->query();
    }
    /**
     * Get all old DOCman documnets with specified category
     *
     * @param int $catid category id
     * @return array
     */
    private function getDocs ($catid)
    {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM `#__docman` WHERE `catid` = '$catid'";
        $db->setQuery($query);
        $docs = $db->loadObjectList();
        if (is_null($docs)) {
            $this->error(_DM_MGR_ERR_LOAD_OLD_DOCUMENTS);
        }
        return $docs;
    }
    /**
     * Clean all migrated documents after error
     *
     */
    private function cleanDocs ()
    {
        $db = &JFactory::getDBO();
        $query = "DELETE FROM `#__joomdoc` WHERE `id` IN (" . implode(',', $this->docsIds) . ")";
        $db->setQuery($query);
        $db->query();
    }
    /**
     * Get all old DOCman histories with specified document id 
     *
     * @param int $docid document id
     * @return array
     */
    private function getHistory ($docid)
    {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM `#__docman_history` WHERE `doc_id` = '$docid'";
        $db->setQuery($query);
        $history = $db->loadObjectList();
        if (is_null($history)) {
            $this->error(_DM_MGR_ERR_LOAD_OLD_HISTORY);
        }
        return $history;
    }
    /**
     * Clean all migrated history after error
     *
     */
    private function cleanHistory ()
    {
        $db = &JFactory::getDBO();
        $query = "DELETE FROM `#__joomdoc_history` WHERE `id` IN (" . implode(',', $this->historyIds) . ")";
        $db->setQuery($query);
        $db->query();
    }
    /**
     * Get all old DOCman logs with specified document id 
     *
     * @param int $docid document id
     * @return array
     */
    private function getLog ($docid)
    {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM `#__docman_log` WHERE `log_docid` = '$docid'";
        $db->setQuery($query);
        $logs = $db->loadObjectList();
        if (is_null($logs)) {
            $this->error(_DM_MGR_ERR_LOAD_OLD_LOGS);
        }
        return $logs;
    }
    /**
     * Clean all migrated logs after error
     *
     */
    private function cleanLog ()
    {
        $db = &JFactory::getDBO();
        $query = "DELETE FROM `#__joomdoc_log` WHERE `id` IN (" . implode(',', $this->logsIds) . ")";
        $db->setQuery($query);
        $db->query();
    }
    /**
     * Get all old DOCman licences
     *
     * @return array
     */
    private function getLicenses ()
    {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM `#__docman_licenses`";
        $db->setQuery($query);
        $licences = $db->loadObjectList();
        if (is_null($licences)) {
            $this->error(_DM_MGR_ERR_LOAD_OLD_LICENCES);
        }
        return $licences;
    }
    /**
     * Clean all migrated licenses after error
     *
     */
    private function cleanLicenses ()
    {
        $db = &JFactory::getDBO();
        $query = "DELETE FROM `#__joomdoc_licenses` WHERE `id` IN (" . implode(',', $this->licencesIds) . ")";
        $db->setQuery($query);
        $db->query();
    }
    /**
     * Get all old DOCman groups
     *
     * @return array
     */
    private function getGroups ()
    {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM `#__docman_groups`";
        $db->setQuery($query);
        $groups = $db->loadObjectList();
        if (is_null($groups)) {
            $this->error(_DM_MGR_ERR_LOAD_OLD_GROUPS);
        }
        return $groups;
    }
    /**
     * Migrate files 
     *
     */
    private function migrateFiles ()
    {
        $noCopy = array(); //list of no migrates files
        //configuration of old instalaltion of DOCman
        $oldConfig = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_docman' . DS . 'docman.config.php';
        if (! file_exists($oldConfig)) { //no exists - probably DOCman was unistall
            $this->error(_DM_MGR_UNABLE_FIND_OLD_CONF_FILE . $oldConfig, false);
        }
        if (! is_readable($oldConfig)) { //configuration no readable
            $this->error(_DM_MGR_UNABLE_READ_OLD_CONF . $oldConfig, false);
        }
        //content of old configuration 
        $content = file_get_contents($oldConfig);
        //find config param $dmpath
        $regex = '#var( *)\$dmpath( *)=( *)\'([^\']*)\'#iU';
        $mathes = array();
        //$dmpath no find
        if (! preg_match($regex, $content, $mathes)) {
            $this->error(_DM_MGR_UNABLE_FIND_OLD_CONF . $oldConfig, false);
        }
        $oldDmPath = $mathes[4];
        //dir with old files no exists
        if (! file_exists($oldDmPath)) {
            $this->error(_DM_MGR_DIR_NO_EXISTS . $oldDmPath, false);
        }
        //dir is no readable
        if (! is_readable($oldDmPath)) {
            chmod($oldDmPath, 755);
            if (! is_readable($oldDmPath)) {
                $this->error(_DM_MGR_DIR_NO_READABLE . $oldDmPath, false);
            }
        }
        global $_DOCMAN;
        //$dmpath from new instalation of DOCman 2
        $newDmPath = $_DOCMAN->getCfg('dmpath');
        //new dir no exists
        if (! file_exists($newDmPath)) {
            mkdir($newDmPath, 0755);
            if (! file_exists($newDmPath)) {
                $this->error(_DM_MGR_DIR_NEW_NO_EXISTS . $newDmPath, false);
            }
        }
        //new dir is unwriteable
        if (! is_writable($newDmPath)) {
            chmod($newDmPath, 755);
            if (! is_writable($newDmPath)) {
                $this->error(_DM_MGR_DIR_IS_UNWRITEABLE . $newDmPath, false);
            }
        }
        //open old files dir
        $dir = opendir($oldDmPath);
        //get all files to copy
        while (($file = readdir($dir))) {
            if (is_file($oldDmPath . DS . $file)) { //no copy links, directories and no file links
                $source = $oldDmPath . DS . $file; //old destination
                $target = $newDmPath . DS . $file; //new destination
                if (! copy($source, $target)) { //unable copy - maybe permision denied
                    $noCopy[] = $source;
                }
            }
        }
        if (count($noCopy)) {
            $this->error(_DM_MGR_UNABLE_COPY . implode(', ', $noCopy), false);
        }
    
    }
    /**
     * Clean all migrated groups after error
     *
     */
    private function cleanGroups ()
    {
        $db = &JFactory::getDBO();
        $query = "DELETE FROM `#__joomdoc_groups` WHERE `groups_id` IN (" . implode(',', $this->groupsIds) . ")";
        $db->setQuery($query);
        $db->query();
    }
    /**
     * Back into DOCman cpanel
     *
     */
    private function back ($msg = null)
    {
        $mainframe = &JFactory::getApplication();
        $url = 'index2.php?option=com_joomdoc';
        if ($msg) {
            $mainframe->redirect($url, $msg);
        } else {
            $mainframe->redirect($url);
        }
    }
    /**
     * Print error msg and clean
     *
     * @param string $msg
     * @param boolean $clean
     */
    private function error ($msg = '', $clean = true)
    {
        
        if (! $clean) {
            JError::raiseNotice('SOME_ERROR_CODE', $msg);
        } else {
            $msg = _DM_MGR_FATAL_ERROR . ' ' . $msg;
            JError::raiseError('SOME_ERROR_CODE', $msg);
            $this->clean(); //clean migrating
        }
        $this->back(); //back into docman cpanel
    }
    
/*      
     DELETE FROM jos_categories WHERE section = 'com_joomdoc';
     DELETE FROM jos_joomdoc;
	 DELETE FROM jos_joomdoc_groups;
     DELETE FROM jos_joomdoc_history;
     DELETE FROM jos_joomdoc_licenses;
     DELETE FROM jos_joomdoc_log;
     */
}
?>