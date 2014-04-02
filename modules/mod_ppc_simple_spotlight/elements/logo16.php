<?php
/*------------------------------------------------------------------------
 # Simple Spotlight - Version 1.6
 # ------------------------------------------------------------------------
 # Copyright (C) 2011 Pixel Point Creative. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Author: Pixel Point Creative
 # Websites: http://www.pixelpointcreative.com
 -------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldLogo16 extends JFormField
{
	/**
  * The form field type.
  *
  * @var  string
  * @since	1.6*/
protected $type = 'logo16';

function fetchTooltip($label, $description, &$node, $control_name, $name) {
return '&nbsp;';
}

protected function getInput()
{
if ($value) {
return JText::_($value);
} else {
return '<div style="float:left;clear: both;"><img width="160" height="80" border="0" src="../modules/mod_ppc_simple_spotlight/elements/logo.png" title="Simple Spotlight" alt="Simple Spotlight"></div>
<div style="float:left; clear:both; margin-bottom:10px;font-size:12px;"><br>Having Trouble?<a href="http://www.pixelpointcreative.com/support.html" target="_blank"><font color="#828627"> Get Help</font></a> | Installed Version: 2.1</div>';
}
}
}