<?php
/*
	codextension.com POPUP ANYWHERE PLUGIN - version 1.5.0
______________________________________________________________________
	@package		Joomla
	@subpackage	system
	License http://www.gnu.org/copyleft/gpl.html GNU/GPL
	Copyright(c) 2010 codeXtension.com All Rights Reserved.
	Comments & suggestions: http://codextension.com/
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementPreview extends JElement{
	var	$_name			= 'preview';
	var $_controlName	= '';

	/**
	 * fetch Element
	 */
	function fetchElement($name, $value, &$node, $control_name){
		$html = '<div class="button2-left"><div class="blank"><a href="../index.php?popup_anywhere_preview=true" target="_blank">Click for Preview (apply first)</a></div></div>';
		return $html;
	}
}
