<?php
/*
* Pixel Point Creative "Simple Spotlight" Module for Joomla!
* License: GNU General Public License version 2 http://www.gnu.org/copyleft/gpl.html
* Copyright (c)2010 Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
* Review our terms/license here: http://pixelpointcreative.com/terms.html
*/

// Check to ensure this file is within the rest of the framework

defined('JPATH_BASE') or die();

class JElementImageListUpload15 extends JElement

{
	var	$_name = 'ImageListUpload15';
	function fetchElement($name, $value, &$node, $control_name)
	{
		
		$filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$';
		$node->addAttribute('filter', $filter);
		$parameter =& $this->_parent->loadElement('filelist');
		$str = '';	
		$str .= '<script language="javascript">';
		$str .= ' function Upload() {';	
		$str .= 'var Mywidth;';
		$str .= 'var Myheight;';
		$str .= 'Mywidth = (window.screen.width/2) - (75 + 10);';
		$str .= 'Myheight = (window.screen.height/2) - (100 + 50);';
		$str .= 'var win2 = window.open("../modules/mod_ppc_simple_spotlight/elements/upload_file.php","Window2","status=no,height=200,width=350,resizable=yes,left=" + Mywidth +",top=" + Myheight +",screenX=" + Mywidth +",screenY=" + Myheight +",toolbar=no,menubar=no,location=no,directories=no");';	
		$str .= 'win2.focus();';
		$str .= ' } ';
		$str .= '</script>';
		$str .= '<input type="button" value="Upload" style="background:#828627; color:#fff; cursor:pointer" onclick="javascript:Upload()">';
		return $parameter->fetchElement($name, $value, $node, $control_name).'&nbsp&nbsp&nbsp'.$str;

	}

}

