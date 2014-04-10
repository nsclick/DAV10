<?php
/**
 * @version 1.0 $Id: view.html.php 197 2009-01-31 21:34:36Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2005 - 2008 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
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
 * View class for the QuickFAQ category screen
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewCategory extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$lang 		= & JFactory::getLanguage();
		$pane 		= & JPane::getInstance('sliders');

		//get vars
		$cid 		= JRequest::getVar( 'cid' );

		//add css to document
		$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend_rtl.css');
    	}
		
		//create the toolbar
		if ( $cid ) {
			JToolBarHelper::title( JText::_( 'EDIT CATEGORY' ), 'qfcategoryedit' );

		} else {
			JToolBarHelper::title( JText::_( 'NEW CATEGORY' ), 'qfcategoryedit' );

			JSubMenuHelper::addEntry( JText::_( 'HOME' ), 'index.php?option=com_quickfaq');
			JSubMenuHelper::addEntry( JText::_( 'ITEMS' ), 'index.php?option=com_quickfaq&view=items');
			JSubMenuHelper::addEntry( JText::_( 'CATEGORIES' ), 'index.php?option=com_quickfaq&view=categories');
			JSubMenuHelper::addEntry( JText::_( 'TAGS' ), 'index.php?option=com_quickfaq&view=tags');
			JSubMenuHelper::addEntry( JText::_( 'ARCHIVE' ), 'index.php?option=com_quickfaq&view=archive');
			JSubMenuHelper::addEntry( JText::_( 'FILEMANAGER' ), 'index.php?option=com_quickfaq&view=filemanager');
			JSubMenuHelper::addEntry( JText::_( 'STATISTICS' ), 'index.php?option=com_quickfaq&view=stats');
		}
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::media_manager();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();

		//Get data from the model
		$model		= & $this->getModel();
		$row     	= & $this->get( 'Category' );
		$categories = quickfaq_cats::getCategoriesTree(0);

		//remove the current category to prevent selection as parent for itself
		foreach($categories as $category) {
			
			if ($category->id == $row->id) {
				unset($categories[$category->id]);
			}
		}

		//fail if checked out not by 'me'
		if ($row->id) {
			if ($model->isCheckedOut( $user->get('id') )) {
				JError::raiseWarning( 'SOME_ERROR_CODE', $row->title.' '.JText::_( 'EDITED BY ANOTHER ADMIN' ));
				$mainframe->redirect( 'index.php?option=com_quickfaq&view=categories' );
			}
		}

		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'text' );
	    	
		//build selectlists
		$Lists = array();
		$javascript = "onchange=\"javascript:if (document.forms[0].image.options[selectedIndex].value!='') {document.imagelib.src='../images/stories/' + document.forms[0].image.options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		$Lists['imagelist'] 		= JHTML::_('list.images', 'image', $row->image, $javascript, '/images/stories/' );
		$Lists['access'] 			= JHTML::_('list.accesslevel', $row );
		$Lists['parent_id'] 		= quickfaq_cats::buildcatselect($categories, 'parent_id', $row->parent_id, true);
			
		//assign vars to view
		$this->assignRef('Lists'      	, $Lists);
		$this->assignRef('row'      	, $row);
		$this->assignRef('editor'		, $editor);
		$this->assignRef('pane'			, $pane);

		parent::display($tpl);
	}
}
?>