<?php
/**
 * @version 1.0 $Id: view.pdf.php 195 2009-01-30 06:33:12Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2008 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENCE.php
 * QuickFAQ is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * QuickFAQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with QuickFAQ; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML Item View class for the QuickFAQ component
 *
 * @package		Joomla
 * @subpackage	QuickFAQ
 * @since 1.0
 */
class QuickfaqViewItems extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$dispatcher	=& JDispatcher::getInstance();

		// Initialize some variables
		$item 		= & $this->get('Item');
		$params 	= & $mainframe->getParams('com_quickfaq');

		// process the new plugins
		JPluginHelper::importPlugin('content', 'image');
		$dispatcher->trigger('onPrepareContent', array (& $item, & $params, 0));

		$document = &JFactory::getDocument();

		// set document information
		$document->setTitle($item->title);
		$document->setName($item->alias);
		$document->setDescription($item->meta_description);
		$document->setMetaData('keywords', $item->meta_keywords);

		// prepare header lines
		$document->setHeader($this->_getHeaderText($item, $params));

		echo $item->text;
	}

	function _getHeaderText(& $item, & $params)
	{
		// Initialize some variables
		$text = '';

		if ($params->get('show_author')) {
			// Display Author name
			$text .= "\n";
			$text .= JText::_('WRITTEN BY').' '. ($item->created_by_alias ? $item->created_by_alias : $item->author);
		}

		if ($params->get('show_create_date') && $params->get('show_author')) {
			// Display Separator
			$text .= "\n";
		}

		if ($params->get('show_create_date')) {
			// Display Created Date
			if (intval($item->created)) {
				$create_date = JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC2'));
				$text .= $create_date;
			}
		}

		if ($params->get('show_modify_date') && ($params->get('show_author') || $params->get('show_create_date'))) {
			// Display Separator
			$text .= " - ";
		}

		if ($params->get('show_modify_date')) {
			// Display Modified Date
			if (intval($item->modified)) {
				$mod_date = JHTML::_('date', $item->modified);
				$text .= JText::_('LAST REVISED').' '.$mod_date;
			}
		}
		return $text;
	}
}
?>