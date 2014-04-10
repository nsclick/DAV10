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


class JElementAddarticle extends JElement{
	
	var	$_name			= 'addarticle';
	var $_controlName	= '';
	var $required		= '';
	/**
	 * fetch Element
	 */
	function fetchElement($name, $value, &$node, $control_name){
		$id			= $name;
		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();
		//$fieldName	= $control_name.'['.$name.']';
		$article =& JTable::getInstance('content');
		if ($value) {
			$article->load($value);
		} else {
			$article->title = 'Please select an Article';
		}

		$js = "
		function jSelectArticle(id, title, object) {			
			document.getElementById('".$id."'+'_id').value = id;
			document.getElementById('".$id."'+'_name').value = title;
			document.getElementById('paramslink').value = 'index.php?option=com_content&view=article&tmpl=component&id='+id;
			SqueezeBox.close();
		}";
		$doc->addScriptDeclaration($js);
		$link	= 'index.php?option=com_content&amp;task=element&amp;tmpl=component&object=id';
		//$link	= 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticle';
		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}
		
		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$id.'_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select an Article').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= '<input type="hidden" id="'.$id.'_id"'.$class.' name="'.$control_name.'['.$name.'][]'.'" value="'.$value.'" />';
		return $html;
	}
}