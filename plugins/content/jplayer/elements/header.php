<?php
/*
 * JPlayer for Joomla! 1.5
 * Author: Max
 * Version: 1.5
 * Last Update: 31/07/2010
*/

// no direct access
defined('_JEXEC') or die ('Restricted access');

class JElementHeader extends JElement {
   var $_name = 'header';

   function fetchElement($name, $value, &$node, $control_name) {
      // Output
      return '
		<div style="width:95%; font-weight:normal; font-size:12px; color:#ffffff; padding:4px; margin:0; background:#0B55C4;">
			'.JText::_($value).'
		</div>
		';
   }
	
}
