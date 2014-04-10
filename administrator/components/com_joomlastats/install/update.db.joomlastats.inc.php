<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

 
if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}




function js_UpdateJSDatabaseOnInstall( $JSUtil, $updateFromJSVersion, $JSConfDef ) {

	//in 2.3.0 we do not support update from version older than 2.2.3!!!     -do not remove below code, it help us to see full path of changes
	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.2.0', '<') == true) {
		$quer = array();
		
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "RENAME TABLE #__TFS_bots TO #__jstats_bots, #__TFS_browsers TO #__jstats_browsers, #__TFS_configuration TO #__jstats_configuration, #__TFS_ipaddresses TO #__jstats_ipaddresses, #__TFS_iptocountry TO #__jstats_iptocountry, #__TFS_keywords TO #__jstats_keywords, #__TFS_page_request TO #__jstats_page_request, #__TFS_page_request_c TO #__jstats_page_request_c, #__TFS_pages TO #__jstats_pages, #__TFS_referrer TO #__jstats_referrer, #__TFS_search_engines TO #__jstats_search_engines, #__TFS_systems TO #__jstats_systems, #__TFS_topleveldomains TO #__jstats_topleveldomains, #__TFS_visits TO #__jstats_visits";
	
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "RENAME TABLE #__tfs_bots TO #__jstats_bots, #__tfs_browsers TO #__jstats_browsers, #__tfs_configuration TO #__jstats_configuration, #__tfs_ipaddresses TO #__jstats_ipaddresses, #__tfs_iptocountry TO #__jstats_iptocountry, #__tfs_keywords TO #__jstats_keywords, #__tfs_page_request TO #__jstats_page_request, #__tfs_page_request_c TO #__jstats_page_request_c, #__tfs_pages TO #__jstats_pages, #__tfs_referrer TO #__jstats_referrer, #__tfs_search_engines TO #__jstats_search_engines, #__tfs_systems TO #__jstats_systems, #__tfs_topleveldomains TO #__jstats_topleveldomains, #__tfs_visits TO #__jstats_visits";
	
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "ALTER IGNORE TABLE #__jstats_pages ADD `page_title` VARCHAR( 255 )";
		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}
		

	//in 2.3.0 we do not support update from version older than 2.2.3!!!     -do not remove below code, it help us to see full path of changes
	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.2.0', '<') == true) {
		$quer = array();
		
		// we added the primairy key description later, because then we could keep the old configuration (in the past the config was reset on every update).
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "ALTER TABLE `#__jstats_configuration` ADD PRIMARY KEY (description)";
		
		// this index should realy speed up things...
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "CREATE INDEX visits_id ON `#__jstats_page_request` (`ip_id`)";
		
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "ALTER IGNORE TABLE `#__jstats_page_request` ADD INDEX `index_ip` (ip_id)";
		
		// added user awareness
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "ALTER IGNORE TABLE `#__jstats_visits` ADD userid INT NOT NULL AFTER ip_id";

		// before release 2.1.9 additional userid indexes where created unwanted, remove them.
		//below update could be applayed earlier than in version '2.2.0' (in version '2.2.0' it was applayed for sure)
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_2`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_3`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_4`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_5`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_6`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_7`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_8`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_9`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_10`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_11`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_12`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_13`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_14`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_15`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_16`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_17`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_18`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_19`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_20`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_21`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_22`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_23`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_24`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_25`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_26`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_27`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_28`";
		$quer[] = "ALTER TABLE `#__jstats_visits` DROP INDEX `userid_29`";
		$quer[] = "ALTER IGNORE TABLE `#__jstats_visits` ADD INDEX `userid` (userid)";
		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}

	$wereColumnsScreenAndWhoisCreated = true;//this is update install process optimization
	//@todo '2.3.0.130' is not checked (I can do this right now) - it should be checked in which version this was added
	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.3.0.130 dev', '<') == true) {
		$quer = array();
		
		// new since 2.3.0: we do not use anymore 'hourdiff' and 'language': delete them
		$quer[] = 'DELETE FROM `#__jstats_configuration` WHERE `description` = \'hourdiff\'';
		$quer[] = 'DELETE FROM `#__jstats_configuration` WHERE `description` = \'language\'';

		// new since 2.3.0: new field
		if ($wereColumnsScreenAndWhoisCreated == false) {
			//I know this code will be never performed in the future, but in the past it was - it should not be commented nor removed!
			$quer[] = "ALTER TABLE `#__jstats_ipaddresses` ADD `whois` TINYINT( 1 ) NOT NULL";
			$quer[] = 'ALTER TABLE `#__jstats_ipaddresses` ADD screen varchar(12) NOT NULL COMMENT \'screen resolution\'';
		} else {
			$wereColumnsScreenAndWhoisCreated = false;
		}

		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}
	
	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.3.0.167 dev', '<') == true) {
		$quer = array();
		
		// new since 2.3.0: we do not use anymore 'purgetime' nor 'last_purge' : delete them
		$quer[] = 'DELETE FROM `#__jstats_configuration` WHERE `description` = \'purgetime\'';
		$quer[] = 'DELETE FROM `#__jstats_configuration` WHERE `description` = \'last_purge\'';

		//rename show_bu to show_summarized
		$quer[] = 'UPDATE `#__jstats_configuration` SET `description` = \'show_summarized\' WHERE `description` = \'show_bu\'';

		//remove duplicated indexes				
		$quer[] = "ALTER TABLE `#__jstats_ipaddresses` DROP INDEX `id`";
		$quer[] = "ALTER TABLE `#__jstats_pages` DROP INDEX `page_id`";
		$quer[] = "ALTER TABLE `#__jstats_page_request` DROP INDEX `visits_id`";
		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}
	
	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.3.0.189 dev', '<') == true) {
		$quer = array();
		
		// below query is harmless //I do not know if it is necessary (unable to check this) but I want to be SURE that all data are also in column time //duplicated columns (year, month, day, hour) will be deleted in near future!
		$quer[] = "UPDATE `#__jstats_visits` SET `time` = CONCAT(`year`, '-', `month`, '-', `day`, ' ', `hour`, ':00:00') WHERE `time` = '0000-00-00 00:00:00'";
		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}
	
	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.3.0.201 dev', '<') == true) {
		$quer = array();
		
		//sys_img is not used - change it to sys_type. Add column image
		$quer[] = "ALTER TABLE `#__jstats_systems` CHANGE COLUMN `sys_img` `sys_type` tinyint(1) NOT NULL default '0'";
		$quer[] = 'ALTER TABLE `#__jstats_systems` ADD `sys_img` varchar(12) NOT NULL default \'noimage\'';
		
		//browser_img is not used - change it to browser_type. Add column image
		$quer[] = "ALTER TABLE `#__jstats_browsers` CHANGE COLUMN `browser_img` `browser_type` tinyint(1) NOT NULL default '0'";
		$quer[] = 'ALTER TABLE `#__jstats_browsers` ADD `browser_img` varchar(12) NOT NULL default \'noimage\'';
		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}
	
	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.3.0.216 dev', '<') == true) {
		$quer = array();

		//rename show_summarized to include_summarized
		$quer[] = 'UPDATE `#__jstats_configuration` SET `description` = \'include_summarized\' WHERE `description` = \'show_summarized\'';

		//insert new parameter show_summarized. We must set it to 'false' (we do not know if include_summarized is set to true or to false)
		$quer[] = "INSERT IGNORE INTO #__jstats_configuration (description, value) VALUES ".
				  "('show_summarized', 'false') ";
		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}

	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.3.0.231 dev', '<') == true) {
		$quer = array();

		$quer[] = 'DROP TABLE `#__jstats_browsers`';

		//remove auto_increment option
		$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_browsers` ('
		  		. ' `browser_id` mediumint(9) NOT NULL,'
		  		. ' `browser_string` varchar(50) NOT NULL default \'\','
		  		. ' `browser_fullname` varchar(50) NOT NULL default \'\','
		        . ' `browser_type` tinyint(1) NOT NULL default \'0\','
		        . ' `browser_img` varchar(12) NOT NULL default \'noimage\','
		  		. ' PRIMARY KEY  (`browser_id`),'
		  		. ' UNIQUE KEY `browser_string` (`browser_string`)'
		  		. ' ) TYPE=MyISAM';

		$quer[] = 'DROP TABLE `#__jstats_systems`';

		//remove auto_increment option
		$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_systems` ('
		        . ' `sys_id` mediumint(9) NOT NULL,'
		        . ' `sys_string` varchar(25) NOT NULL default \'\','
		        . ' `sys_fullname` varchar(25) NOT NULL default \'\','
		        . ' `sys_type` tinyint(1) NOT NULL default \'0\','
		        . ' `sys_img` varchar(12) NOT NULL default \'noimage\','
		        . ' PRIMARY KEY (`sys_id`)'
		        . ' ) TYPE=MyISAM';

		$quer[] = 'DROP TABLE `#__jstats_topleveldomains`';

		//extend size od tld column, remove auto_increment option
		$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_topleveldomains` ('
		        . ' `tld_id` mediumint(9) NOT NULL,'
		        . ' `tld` varchar(9) NOT NULL default \'\','
		        . ' `fullname` varchar(255) NOT NULL default \'\','
		        . ' PRIMARY KEY (`tld_id`),'
		        . ' KEY `tld` (`tld`)'
		        . ' ) TYPE=MyISAM';

		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}

	if ($JSUtil->JSVersionCompare( $updateFromJSVersion, '2.3.0.232 dev', '<') == true) {
		if ($wereColumnsScreenAndWhoisCreated == true) {
			$quer = array();
	
			//see task "Remove 'screen' and 'whois' column" for details
			$quer[] = 'ALTER IGNORE TABLE `#__jstats_ipaddresses` DROP COLUMN `screen`';
			$quer[] = 'ALTER IGNORE TABLE `#__jstats_ipaddresses` DROP COLUMN `whois`';

			// transfer what we have
			$JSUtil->populateSQL( $quer );
		}
	}
	
	{//update version and notification about update
		$quer = array();
	
		$quer[] = "UPDATE #__jstats_configuration SET value = '".$JSConfDef->JSVersion."' WHERE description = 'version'";
		
		$date_str = date("Y-m-d_H:i:s");
		$quer[] = "INSERT IGNORE INTO #__jstats_configuration (description, value) VALUES ".
				  "('db_update_".$date_str."_to_version', '".$JSConfDef->JSVersion."') ";
		
		// transfer what we have
		$JSUtil->populateSQL( $quer );
	}
}

