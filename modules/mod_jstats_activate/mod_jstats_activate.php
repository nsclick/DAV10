<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


defined('_VALID_MOS') or die ('JS: Direct access to this location is not allowed.');


$js_activation_file = $GLOBALS['mosConfig_absolute_path'] .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_joomlastats' .DIRECTORY_SEPARATOR. 'joomlastats.inc.php';

if ( (file_exists($js_activation_file)) && is_readable($js_activation_file) ) {
	include_once($js_activation_file);
} else {
	$this_js_extension_homepage = 'http://www.joomlastats.org/index.php?option=com_content&amp;task=view&amp;id=70&amp;Itemid=38';//remeber to replace '&' by '&amp;'
	$this_js_extension_install_problem_text = '<br/>It seams that <a href="'.$this_js_extension_homepage.'" target="_blank"><b>module Activation (mod_jstats_activate)</b></a> is not installed correctly. Please refer to <a href="http://www.joomlastats.org/entry/installation_noengine.php" target="_blank"><b>JoomlaStats extension installation problem</b></a> page.<br/><br/><br/>';
	echo $this_js_extension_install_problem_text;
}
