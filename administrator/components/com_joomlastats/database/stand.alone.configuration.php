<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

 
//It is stand alone version!
defined('_JS_STAND_ALONE') or die ('JoomlaStats Stand Alone version: Direct Access to this location is not allowed.');
 



class js_JSStandAloneConfiguration
{
	var $JConfigArr = array(
	
	//Settings from Joomla CMS (copy values from joomla 'configuration.php' file)
	//	j1.5.6 name	   value				comment										j1.0.15 name
		'dbtype' 	=> 'mysql',				// Normally mysql, could be mysqli			
		'host' 		=> 'localhost',			// This is normally set to localhost		mosConfig_host
		'user' 		=> 'j159',				// MySQL username							mosConfig_user
		'password' 	=> 'password',			// MySQL password							mosConfig_password
		'db' 		=> 'j159_2009-01-17',	// MySQL database name						mosConfig_db
		'database' 	=> 'j159_2009-01-17',	// MySQL database name						mosConfig_db
		'dbprefix' 	=> 'jos_',				// Do not change unless you need to!		mosConfig_dbprefix
		'offset' 	=> 0, 					//hour offset								mosConfig_offset_user (not mosConfig_offset)
		'debug'     => 1                    // set to 1 to see debug messages 	
	//JoomlaStats settings
	
	);

}


