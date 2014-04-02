<?php
/**
* @version $Id: spacer.php 11371 2008-12-30 01:31:50Z ian $
* @package Joomla.Framework
* @subpackage Parameter
* @copyright Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
* Renders the TC logo
*
* @package Joomla.Framework
* @subpackage Parameter
* @since 1.5
*/

class JElementLogo extends JElement
{
/**
* Element name
*
* @access protected
* @var string
*/
var $_name = 'Logo';

function fetchTooltip($label, $description, &$node, $control_name, $name) {
return '&nbsp;';
}

function fetchElement($name, $value, &$node, $control_name)
{

return '<div style="float:left;clear: both;"><img width="160" height="80" border="0" src="../modules/mod_ppc_simple_spotlight/elements/logo.png" title="Simple Spotlight" alt="Simple Spotlight"></div>
<div style="float:left; clear:both; margin-bottom:10px;font-size:12px;"><br>Having Trouble?<a href="http://www.pixelpointcreative.com/support.html" target="_blank"><font color="#828627"> Get Help</font></a> | Installed Version: 2.2</div>';
}
}
