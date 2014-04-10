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

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JElementMedia extends JElement{
	var	$_name			= 'media';
	var $_controlName	= '';
	protected static $initialised = false;
	
	/**
	 * fetch Element
	 */
	function fetchElement($name, $value, &$node, $control_name){
		
		$assetField	= 'asset_id';
		$authorField= 'created_by';
		$asset		= '' ;
		if ($asset == "") {
			 $asset = JRequest::getCmd('option');
		}
		
		$link = (string) '';
		if (!self::$initialised) {

			// Load the modal behavior script.
			JHtml::_('behavior.modal');

			// Build the script.
			$pattern = "/src=[\"']?([^\"']?.*(png|jpg|gif))[\"']?/i";

			$script = array();
			$script[] = '	function jInsertEditorText(value, id) {';
			$script[] = '		var old_id = $(id).value;';
			$script[] = '		if (old_id != id) {';

			$script[] = '		var patt='.$pattern.';result=patt.exec(value);';
			$script[] = '			if( result.length && result[1] ){';
			$script[] = '				var elem = $(id)';
			$script[] = '				elem.value = result[1];';
			$script[] = '				elem.fireEvent("change");';
			$script[] = '			}';
			$script[] = '		}';
			$script[] = '	}';

			// Add the script to the document head.
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
			self::$initialised = true;
		}

		// Initialize variables.
		$html = array();
		$attr = '';

		// The text field.
		$html[] = '<div class="fltlft" style="float:left;">';
		$html[] = '	<input type="text" name="'.$control_name.'['.$name.'][]'.'" id="'.$name.'"' .
					' value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'"' .
					' readonly="readonly"'.$attr.' />';
		$html[] = '</div>';

		$directory = (string)'';
		if ($value && file_exists(JPATH_ROOT . '/' . $value)) {
			$folder = explode ('/',$value);
			array_shift($folder);
			array_pop($folder);
			$folder = implode('/',$folder);
		}
		elseif (file_exists(JPATH_ROOT . '/images/' . $directory)) {
			$folder = $directory;
		}
		else {
			$folder='';
		}
		$link .= 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;e_name='.$name.'&folder='.$folder;
		// The button.
		$html[] = '<div class="button2-left">';
		$html[] = '	<div class="blank">';
		$html[] = '		<a class="modal" title="'.JText::_('JLIB_FORM_BUTTON_SELECT').'"' .
					' href="'.$link.'"' .
					' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
		$html[] = '			'.JText::_('Select').'</a>';
		$html[] = '	</div>';
		$html[] = '</div>';
		
		$html[] = '<div class="button2-left">';
		$html[] = '	<div class="blank">';
		$html[] = '		<a title="'.JText::_('JLIB_FORM_BUTTON_CLEAR').'"' .
					' href="#"'.
					' onclick="document.getElementById(\''.$name.'\').value=\'\';">';
		$html[] = '			'.JText::_('Clear').'</a>';
		$html[] = '	</div>';
		$html[] = '</div>';

		return implode("\n", $html);
	}
}
