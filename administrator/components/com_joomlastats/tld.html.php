<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}


class js_JSTldTpl
{
	function getTldTabTpl($ip_tld_info) {
		$description = JTEXT::_( 'Check IP in JoomlaStats table' );
		$description2 = JTEXT::_( 'Check IP in internet providers' );
		$check = JTEXT::_( 'Check' );
		$eg = JTEXT::_( 'eg.' );
		$or = JTEXT::_( 'or' );
		
		$html = '
		<div style="font-size: 1px;">&nbsp;</div><!-- This div is needed to show content of tab correctly in \'IE 7.0\' in \'j1.5.6 Legacy\'. Tested in: FF, IE, j1.0.15, j1.5.6 and works OK -->
		';
		
		if ( strlen($ip_tld_info) > 0) {
			$html .= '<div style="border: 2px; border-style: solid; border-color: #0000FF; background-color: #F0F0FF;">'.$ip_tld_info.'</div>';
		}
		
		$html .= '
		<table class="adminform" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
			'.$description.'<br/>
			<br/>
			<small>'.$eg.'(97.102.244.231)</small><br/>
			<input type="text" name="ip" value="" class="text_area" />
			'.$or.'
			<small>'.$eg.'(googlebot.com)</small>
			<input type="text" name="nslookup" value="" class="text_area" />
			<input type="button" name="export2csv" value="'.$check.'" onclick="submitbutton(\'js_tld_do_get_tld_from_table\');" />
		</td></tr></table>
		
		<table class="adminform" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
			'.$description2.'<br/>
			<br/>
			<small>'.$eg.'(97.102.244.231)</small><br/>
			<input type="text" name="ip" value="" class="text_area" />
			'.$or.'
			<small>'.$eg.'(googlebot.com)</small>
			<input type="text" name="nslookup" value="" class="text_area" />
			<input type="button" name="export2csv" value="'.$check.'" onclick="submitbutton(\'js_tld_do_get_tld_from_ripe\');" />
		</td></tr></table>
		';
		
		
		return $html;
	}
}

