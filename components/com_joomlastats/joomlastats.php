<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

//ensure this file is being included by a parent file
defined('_VALID_MOS') or die ('Direct Access to this location is not allowed.');

require_once($mainframe->getPath('front_html', 'com_joomlastats'));

$task = mosGetParam( $_REQUEST,	'task',	'' );

switch (strtolower($task))
{
	default:
		noyetimplemented(); // mic: disabled - do not really need any message at frontend //at: Yes we want - "Use modules..." 
		break;
}

function noyetimplemented()
{
	global $mainframe;

	// Dynamic Page Title
	$mainframe->SetPageTitle( "not yet implemented" );

	HTML_joomlastats::defaultmessage();
}
