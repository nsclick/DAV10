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
class QuickfaqViewItem extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$lang 		= & JFactory::getLanguage();
		$pane 		= & JPane::getInstance('sliders');

		JHTML::_('behavior.tooltip');

		$nullDate 		= $db->getNullDate();

		//get vars
		$cid 		= JRequest::getVar( 'cid' );

		//add css to document
		$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet('components/com_quickfaq/assets/css/quickfaqbackend_rtl.css');
    	}
		
		$document->addScript( JURI::base().'components/com_quickfaq/assets/js/itemscreen.js' );

		//create the toolbar
		if ( $cid ) {
			JToolBarHelper::title( JText::_( 'EDIT FAQ ITEM' ), 'itemedit' );

		} else {
			JToolBarHelper::title( JText::_( 'NEW ITEM' ), 'itemedit' );

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
		JToolBarHelper::cancel();


		//Get data from the model
		$model		= & $this->getModel();
		$row     	= & $this->get( 'Item' );
		$files 		= & $this->get( 'Files' );
		$categories = quickfaq_cats::getCategoriesTree(1);
		$selectedcats = & $this->get( 'Catsselected' );

		// fail if checked out not by 'me'
		if ($row->id) {
			if ($model->isCheckedOut( $user->get('id') )) {
				JError::raiseWarning( 'SOME_ERROR_CODE', $row->title.' '.JText::_( 'EDITED BY ANOTHER ADMIN' ));
				$mainframe->redirect( 'index.php?option=com_quickfaq&view=categories' );
			}
		}

		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES );

		//build selectlists
		$lists = array();
		$lists['cid'] = quickfaq_cats::buildcatselect($categories, 'cid[]', $selectedcats, false, 'multiple="multiple" size="8"');

		// Create the form
		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'item.xml');

		// Details Group
		$active = (intval($row->created_by) ? intval($row->created_by) : $user->get('id'));
		$form->set('created_by', $active);
		$form->set('created_by_alias', $row->created_by_alias);
		$form->set('created', JHTML::_('date', $row->created, '%Y-%m-%d %H:%M:%S'));

		// Advanced Group
		$form->loadINI($row->attribs);

		// Metadata Group
		$form->set('description', $row->meta_description);
		$form->set('keywords', $row->meta_keywords);
		$form->loadINI($row->metadata);

		//build state list
		$state[] = JHTML::_('select.option',  1, JText::_( 'PUBLISHED' ) );
		$state[] = JHTML::_('select.option',  0, JText::_( 'UNPUBLISHED' ) );
		$state[] = JHTML::_('select.option',  -1, JText::_( 'ARCHIVED' ) );
		$state[] = JHTML::_('select.option',  -2, JText::_( 'PENDING' ) );
		$state[] = JHTML::_('select.option',  -3, JText::_( 'OPEN QUESTION' ) );
		$state[] = JHTML::_('select.option',  -4, JText::_( 'IN PROGRESS' ) );

		$lists['state'] = JHTML::_('select.genericlist',   $state, 'state', '', 'value', 'text', $row->state );

		//build fileselect js and load the view
		$js = "
		function qfSelectFile(id, file) {
		
			var name = 'a_name'+id;
			var ixid = 'a_id'+id;			
			var txt = document.createElement('input');
			var hid = document.createElement('input');
			var br = document.createElement('br');
			
			var filelist = document.getElementById('filelist');
			
			var button = document.createElement('input');
			button.type = 'button';
			button.name = 'removebutton_'+id;
			button.id = 'removebutton_'+id;
			$(button).addEvent('click', function() { qfRemoveFile('".JText::_('REMOVED')."', id ) });
			button.value = '".JText::_('REMOVE')."';
			
			txt.type = 'text';
			txt.disabled = 'disabled';
			txt.id	= name;
			txt.value	= file;
			
			hid.type = 'hidden';
			hid.name = 'fid[]';
			hid.value = id;
			hid.id = ixid;
			
			filelist.appendChild(txt);
			filelist.appendChild(button);
			filelist.appendChild(hid);
			filelist.appendChild(br);
		}
		
		function qfRemoveFile(file, i) {
		
			var name = 'a_name' + i;
			var id = 'a_id' + i;

			document.getElementById(id).value = 0;
			document.getElementById(name).value = file;
		}";

		$document->addScriptDeclaration($js);

		JHTML::_('behavior.modal', 'a.modal');

		$i = 0;
		$fileselect = '';
		
		if($files) {
			
			foreach($files as $file) {
				$fileselect .= "\n<input style=\"background: #ffffff;\" type=\"text\" id=\"a_name".$i."\" value=\"$file->filename\" disabled=\"disabled\" />";
				$fileselect .= "\n<input type=\"hidden\" id=\"a_id".$i."\" name=\"fid[]\" value=\"$file->fileid\" />";
				$fileselect .= "\n&nbsp;<input class=\"inputbox\" type=\"button\" onclick=\"qfRemoveFile('".JText::_('REMOVED')."', ".$i." );\" value=\"".JText::_('REMOVE')."\" />";
				$i++;
			}
		}
		$linkfsel = 'index.php?option=com_quickfaq&amp;view=fileselement&amp;tmpl=component&amp;index='.$i;
		
		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('row'      	, $row);
		$this->assignRef('editor'		, $editor);
		$this->assignRef('pane'			, $pane);
		$this->assignRef('nullDate'		, $nullDate);
		$this->assignRef('form'			, $form);
		$this->assignRef('fileselect'	, $fileselect);
		$this->assignRef('linkfsel'		, $linkfsel);

		parent::display($tpl);
	}
}
?>