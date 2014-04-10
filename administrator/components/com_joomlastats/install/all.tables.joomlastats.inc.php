<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}



/* ############### create tables and insert configuration ############# */



$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_bots` ('
        . ' `bot_id` mediumint(9) NOT NULL auto_increment,'
        . ' `bot_string` varchar(50) NOT NULL default \'\','
        . ' `bot_fullname` varchar(50) NOT NULL default \'\','
        . ' PRIMARY KEY (`bot_id`),'
        . ' UNIQUE KEY `bot_string` (`bot_string`)'
        . ' ) TYPE=MyISAM';

/** #__jstats_browsers table will be replaced by below table - see below */
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_browsers` ('
  		. ' `browser_id` mediumint(9) NOT NULL,'
  		. ' `browser_string` varchar(50) NOT NULL default \'\','
  		. ' `browser_fullname` varchar(50) NOT NULL default \'\','
        . ' `browser_type` tinyint(1) NOT NULL default \'0\','
        . ' `browser_img` varchar(12) NOT NULL default \'noimage\','
  		. ' PRIMARY KEY  (`browser_id`),'
  		. ' UNIQUE KEY `browser_string` (`browser_string`)'
  		. ' ) TYPE=MyISAM';

/* #__jstats_browsers table will be replaced by below table
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_browsers` ('
        . ' `browser_id` SMALLINT NOT NULL,' //not auto_increment!
        . ' `browsertype_id` TINYINT NOT NULL default \'0\','
        . ' `browser_key` varchar(50) NOT NULL default \'\',' //50, maybe 25 is enough?
        . ' `browser_name` varchar(50) NOT NULL default \'\','//50, maybe 25 is enough?
        . ' `browser_img` varchar(12) NOT NULL default \'noimage\','
        . ' PRIMARY KEY (`os_id`)'
        . ' ) TYPE=MyISAM';
*/

$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_configuration` ('
        . ' `description` varchar(250) NOT NULL default \'-\','
        . ' `value` varchar(250) default NULL,'
        . ' PRIMARY KEY (`description`)'
        . ' ) TYPE=MyISAM';

/** #__jstats_ipaddresses table will be replaced by #__jstats_visitors table - see below */
$quer[] = 'CREATE TABLE IF NOT EXISTS #__jstats_ipaddresses ('
		. ' ip varchar(50) NOT NULL default \'\','
		. ' nslookup varchar(255) default NULL,'
		. ' tld varchar(10) NOT NULL default \'unknown\','
		. ' useragent varchar(255) default NULL,'
		. ' system varchar(50) NOT NULL default \'\','
		. ' browser varchar(50) NOT NULL default \'\','
		. ' id mediumint(9) NOT NULL auto_increment,'
		. ' type tinyint(1) NOT NULL default \'0\','
		. ' exclude tinyint(1) NOT NULL default \'0\','
		. ' PRIMARY KEY (id),'
		. ' KEY type (type),'
		. ' KEY tld (tld)'
		. ' ) TYPE=MyISAM';

/* '`' characters should be applyied //varchar columns should be moved to end of table!
$quer[] = 'CREATE TABLE IF NOT EXISTS #__jstats_visitors ('
		. ' visitor_id mediumint NOT NULL auto_increment,'//mediumint? in PostgreSQL INT is faster. Should we use int?
		. ' visitor_ip varchar(50) NOT NULL default \'\',' //50? Is it to much? //ip as string? we should use approprate type! (int or something else)
		. ' visitor_nslookup varchar(255) default NULL,' //do we need this column? we always could get this value from gethostbyaddress(). Column is very long and probably it is never searched nor queries. //columns with vary length should be moved to end of table - for performance //in PHP documentation it is called 'Internet host name'
		. ' tld_id SMALLINT NOT NULL default \'0\','
		. ' os_id SMALLINT NOT NULL default \'0\','
		. ' browser_id SMALLINT NOT NULL default \'0\','//maybe we should concatenate tables __bots and __browsers? (eg. bots id above > 1024) Joins will be easier for everyone. Then we can remove column type.
		. ' visitor_type TINYINT NOT NULL default \'0\','
		. ' visitor_exclude TINYINT NOT NULL default \'0\','
		. ' visitor_useragent varchar(255) default NULL,'
		. ' PRIMARY KEY (id),'
		//. ' KEY type (visitor_type),' //I am not sure but I think making index from int column do not speed up database
		//. ' KEY tld (tld)'//not needed due to applying tld_id
		. ' ) TYPE=MyISAM';
*/

$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_iptocountry` ('
        . ' `IP_FROM` bigint(20) NOT NULL default \'0\','
        . ' `IP_TO` bigint(20) NOT NULL default \'0\','
        . ' `COUNTRY_CODE2` char(2) NOT NULL default \'\','
        . ' `COUNTRY_NAME` varchar(50) NOT NULL default \'\','
        . ' PRIMARY KEY (`IP_FROM`)'
        . ' ) TYPE=MyISAM';

$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_keywords` ('
        . ' `kwdate` date NOT NULL default \'0000-00-00\','
        . ' `searchid` mediumint(9) NOT NULL default \'0\','
        . ' `keywords` varchar(255) NOT NULL default \'\''
        . ' ) TYPE=MyISAM';

//this table is crated also in other part of code!!! @todo remove duplicated code
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_page_request` ('
        . ' `page_id` mediumint(9) NOT NULL default \'0\','
        . ' `hour` tinyint(4) NOT NULL default \'0\','
        . ' `day` tinyint(4) NOT NULL default \'0\','
        . ' `month` tinyint(4) NOT NULL default \'0\','
        . ' `year` smallint(6) NOT NULL default \'0\','
        . ' `ip_id` mediumint(9) default NULL,'
        . ' KEY `page_id` (`page_id`),'
        . ' KEY `monthyear` (`month`,`year`),'
        . ' KEY `index_ip` (`ip_id`)'
        . ' ) TYPE=MyISAM';

/** #__jstats_page_request_c table will be replaced by #__jstats_page_request_sums - see below */
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_page_request_c` ('
        . ' `page_id` mediumint(9) NOT NULL default \'0\','
        . ' `hour` tinyint(4) NOT NULL default \'0\','
        . ' `day` tinyint(4) NOT NULL default \'0\','
        . ' `month` tinyint(4) NOT NULL default \'0\','
        . ' `year` smallint(6) NOT NULL default \'0\','
        . ' `count` mediumint(9) default NULL,'
        . ' KEY `page_id` (`page_id`),'
        . ' KEY `monthyear` (`month`,`year`)'
        . ' ) TYPE=MyISAM';
/* I am not sure about name 'page_request' it is too long for column names and consist of 2 words
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_page_request_sums` ('
        . ' `page_id` MEDIUMINT NOT NULL default \'0\','
        . ' `page_request_date` date NOT NULL,'//without default!
        . ' `page_request_sum` MEDIUMINT default NULL,'
        . ' KEY `page_id` (`page_id`)'
        //. ' KEY `page_request_date` (`page_request_date`),'we should check if we should use this index (KEY)
        . ' ) TYPE=MyISAM';
*/
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_pages` ('
        . ' `page_id` mediumint(9) NOT NULL auto_increment,'
        . ' `page` text NOT NULL,'
        . ' `page_title` varchar(255) default NULL,'
        . ' PRIMARY KEY (`page_id`)'
        . ' ) TYPE=MyISAM';

$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_referrer` ('
        . ' `referrer` varchar(255) NOT NULL default \'\','
        . ' `domain` varchar(100) NOT NULL default \'unknown\','
        . ' `refid` mediumint(9) NOT NULL auto_increment,'
        . ' `day` tinyint(4) NOT NULL default \'0\','
        . ' `month` tinyint(4) NOT NULL default \'0\','
        . ' `year` smallint(6) NOT NULL default \'0\','
        . ' PRIMARY KEY (`refid`),'
        . ' KEY `referrer` (`referrer`),'
        . ' KEY `monthyear` (`month`,`year`)'
        . ' ) TYPE=MyISAM';

$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_search_engines` ('
        . ' `searchid` mediumint(9) NOT NULL auto_increment,'
        . ' `description` varchar(100) NOT NULL default \'\','
        . ' `search` varchar(100) NOT NULL default \'\','
        . ' `searchvar` varchar(50) NOT NULL default \'\','
        . ' PRIMARY KEY (`searchid`)'
        . ' ) TYPE=MyISAM';

/** #__jstats_systems table will be replaced by #__jstats_os - see below */
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_systems` ('
        . ' `sys_id` mediumint(9) NOT NULL,'
        . ' `sys_string` varchar(25) NOT NULL default \'\','
        . ' `sys_fullname` varchar(25) NOT NULL default \'\','
        . ' `sys_type` tinyint(1) NOT NULL default \'0\','
        . ' `sys_img` varchar(12) NOT NULL default \'noimage\','
        . ' PRIMARY KEY (`sys_id`)'
        . ' ) TYPE=MyISAM';
/*
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_os` ('
        . ' `os_id` SMALLINT NOT NULL,'//not auto_increment!
        . ' `ostype_id` TINYINT NOT NULL default \'0\','
        . ' `os_key` varchar(25) NOT NULL default \'\','
        . ' `os_name` varchar(25) NOT NULL default \'\','
        . ' `os_img` varchar(12) NOT NULL default \'noimage\','
        . ' PRIMARY KEY (`os_id`)'
        . ' ) TYPE=MyISAM';
*/

//
//  VIRTUAL TABLE  - this table exist, but it is in php code (for performance)
//
//  NOTICE: Tables #__jstats_os and #__jstats_ostype will be merged together!!
//$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_ostype` ('
//        . ' `ostype_id` TINYINT NOT NULL,'//not auto_increment!
//        . ' `ostype_name` varchar(25) NOT NULL default \'\','
//        . ' `ostype_img` varchar(12) NOT NULL default \'noimage\','
//        . ' PRIMARY KEY (`ostype_id`)'
//        . ' ) TYPE=MyISAM';

/** #__jstats_topleveldomains table will be replaced by #__jstats_tlds - see below */
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_topleveldomains` ('
        . ' `tld_id` mediumint(9) NOT NULL,'
        . ' `tld` varchar(9) NOT NULL default \'\','
        . ' `fullname` varchar(255) NOT NULL default \'\','
        . ' PRIMARY KEY (`tld_id`),'
        . ' KEY `tld` (`tld`)'
        . ' ) TYPE=MyISAM';
/* (localhost is the longest tld)
$quer[] = 'CREATE TABLE IF NOT EXISTS `#__jstats_tlds` ('
        . ' `tld_id` SMALLINT NOT NULL,' // not auto_increment!
        . ' `tld` varchar(9) NOT NULL default \'\',' //I have no idea how to call this :(  
        . ' `tld_name` varchar(255) NOT NULL default \'\','
        . ' PRIMARY KEY (`tld_id`),'
        . ' KEY `tld` (`tld`)'
        . ' ) TYPE=MyISAM';
*/

$quer[] = 'CREATE TABLE IF NOT EXISTS #__jstats_visits ('
		. ' id mediumint(9) NOT NULL auto_increment,'
		. ' ip_id mediumint(9) NOT NULL default \'0\','
		. ' userid int(11) NOT NULL default \'0\','
		. ' hour tinyint(4) NOT NULL default \'0\','
		. ' day tinyint(4) NOT NULL default \'0\','
		. ' month tinyint(4) NOT NULL default \'0\','
		. ' year smallint(6) NOT NULL default \'0\','
		. ' time datetime NOT NULL default \'0000-00-00 00:00:00\','
		. ' PRIMARY KEY (id),'
		. ' KEY time (time),'
		. ' KEY ip_id (ip_id),'
		. ' KEY monthyear (month,year),'
		. ' KEY daymonthyear (day,month,year),'
		. ' KEY `userid` (`userid`)'
		. ' ) TYPE=MyISAM';
/* userid will be renamed to cms_userid (User ID if user is logged into 'Joomla CMS'. If user is not logged value is 0)
$quer[] = 'CREATE TABLE IF NOT EXISTS #__jstats_visits ('
		. ' `visit_id` mediumint(9) NOT NULL auto_increment,'
		. ' `visitor_id` mediumint(9) NOT NULL default \'0\','
		. ' `cms_userid` int(11) NOT NULL default \'0\','//int 11?
		. ' `visitor_date` date NOT NULL,'//Yes, without default!
		. ' `visitor_time` time NOT NULL,'//Yes, without default!
		. ' PRIMARY KEY (id),'
		. ' KEY `date` (`date`),'
		//. ' KEY `time` (`time`),' time never should be indexed!!! - it has no sense
		. ' KEY `visitor_id` (`visitor_id`),'//I am not sure if it is necessary. We should check this (and how many space on disk it takes)
		. ' ) TYPE=MyISAM';
*/

		
		
		
				
		
		
// Insert other configuration if they don't exist (if the descriptions exist, they are kept save by primairy key 'description')
$quer[]  =  "INSERT IGNORE INTO #__jstats_configuration (description, value) VALUES".
			"('version', '".$JSConfDef->JSVersion."'),".
			"('onlinetime','".$JSConfDef->onlinetime."'),".
			"('startoption','".$JSConfDef->startoption."'),".
			"('startdayormonth','".$JSConfDef->startdayormonth."'),".
			"('enable_whois','".(($JSConfDef->enable_whois)?'true':'false')."'),".
			"('enable_i18n','".(($JSConfDef->enable_i18n)?'true':'false')."'),".
			"('include_summarized','".(($JSConfDef->include_summarized)?'true':'false')."'),".
			"('show_summarized','".(($JSConfDef->show_summarized)?'true':'false')."'),".
			"('db_installed_from_version', '".$JSConfDef->JSVersion."')";
			