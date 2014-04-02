<?php
/**
 * @version 1.0 $Id: view.html.php 197 2009-01-31 21:34:36Z schlu $
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
 * HTML View class for the Items View
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqViewItems extends JView
{
	/**
	 * Creates the item page
	 *
	 * @since 1.0
	 */
	function display( $tpl = null )
	{
		global $mainframe;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$user		= & JFactory::getUser();
		$menus		= & JSite::getMenu();
		$lang 		= & JFactory::getLanguage();
		$menu    	= $menus->getActive();
		$dispatcher = & JDispatcher::getInstance();
		$params 	= & $mainframe->getParams('com_quickfaq');
		
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$cid		= JRequest::getInt('cid', 0);

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//add css file
		$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq.css');
		if ($lang->isRTL()) {
			$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq_rtl.css');
		}
		
		$document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #quickfaq dd { height: 1%; }</style><![endif]-->');
		
		//get faq item data
		$item 	= & $this->get('Item');
				
		$iparams	=& $item->attribs;
		$params->merge($iparams);
		
		if (($item->id == 0))
		{	
			$id	= JRequest::getInt('id', 0);
			return JError::raiseError( 404, JText::sprintf( 'ITEM #%d NOT FOUND', $id ) );
		}
		
		//get tags if enabled
		if ($params->get('show_tags')) {
        	$tags		= & $this->get('Tags');
		}
		
		//get categories if enabled
		if ($params->get('show_categories')) {
        	$categories	= & $this->get('Categories');
		}
		
		//get favourites if enabled
		if ($params->get('show_favourites')) {
			$favourites	= & $this->get('Favourites');
			$favoured	= & $this->get('Favoured');
		}
		
		//get files if enabled
		$files = & $this->get('Files');
		
		/*
		 * Process the prepare content plugins if enabled
		 */
		if ($params->get('trigger_onprepare_content')) {
			JPluginHelper::importPlugin('content');
			$results = $dispatcher->trigger('onPrepareContent', array (& $item, & $params, $limitstart));
		}
		
		//pathway
		$cats		= new quickfaq_cats($cid);
        $parents	= $cats->getParentlist();
		$pathway 	= & $mainframe->getPathWay();
		
		foreach($parents as $parent) {
			$pathway->addItem( $this->escape($parent->title), JRoute::_('index.php?view=category&cid='.$parent->categoryslug));
		}
		$pathway->addItem( $this->escape($item->title), JRoute::_('index.php?view=items&id='.$item->slug));
		
		/*
		 * Handle the metadata
		 */
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		// Get the menu item object		
		if (is_object($menu)) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$item->title);
			}
		} else {
			$params->set('page_title',	$item->title);
		}

		/*
		 * Create the document title
		 * 
		 * First is to check if we have a category id, if yes add it.
		 * If we haven't one than we accessed this screen direct via the menu and don't add the parent category
		 */
		if($cid) {
			$parentcat = array_pop($parents);
			$doc_title = $parentcat->title.' - '.$params->get( 'page_title' );
		} else {
			$doc_title = $params->get( 'page_title' );
		}
		
		$document->setTitle($doc_title);
		
		if ($item->meta_description) {
			$document->setDescription( $item->meta_description );
		}
		
		if ($item->meta_keywords) {
			$document->setMetadata('keywords', $item->meta_keywords);
		}
		
		if ($mainframe->getCfg('MetaTitle') == '1') {
			$mainframe->addMetaTag('title', $item->title);
		}
		
		if ($mainframe->getCfg('MetaAuthor') == '1') {
			$mainframe->addMetaTag('author', $item->author);
		}

		$mdata = new JParameter($item->metadata);
		$mdata = $mdata->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$document->setMetadata($k, $v);
			}
		}
		
		if ($user->authorize('com_quickfaq', 'state') && $params->get('show_state_icon')) {
			JHTML::_('behavior.mootools');
			$document->addScript( 'components/com_quickfaq/assets/js/stateselector.js' );
			
			$js = "window.onDomReady(stateselector.init.bind(stateselector));

			function dostate(state, id)
			{	
				var change = new processstate();
   				 change.dostate( state, id );
			}";
			
			$document->addScriptDeclaration($js);
		}
		
		/*
		 * Handle display events
		 * No need for it currently
		$item->event = new stdClass();
		$results = $dispatcher->trigger('onAfterDisplayTitle', array ($item, &$params, $limitstart));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $item, & $params, $limitstart));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterDisplayContent', array (& $item, & $params, $limitstart));
		$item->event->afterDisplayContent = trim(implode("\n", $results));
        */
        $print_link = JRoute::_('index.php?view=items&cid='.$item->categoryslug.'&id='.$item->slug.'&pop=1&tmpl=component');

		$this->assignRef('item' , 				$item);
		$this->assignRef('tags' , 				$tags);
		$this->assignRef('categories' ,			$categories);
		$this->assignRef('favourites' ,			$favourites);
		$this->assignRef('favoured' ,			$favoured);
		$this->assignRef('files' ,				$files);
		$this->assignRef('user' , 				$user);
		$this->assignRef('params' , 			$params);
		$this->assignRef('print_link' , 		$print_link);
		$this->assignRef('parentcat',			$parentcat);

		parent::display($tpl);

	}
	
	/**
	 * Creates the item submit form
	 *
	 * @since 1.0
	 */
	function _displayForm($tpl)
	{
		global $mainframe;

		//Initialize variables
		$document	=& JFactory::getDocument();
		$user		=& JFactory::getUser();
		$uri     	=& JFactory::getURI();
		$item		=& $this->get('Item');
		$tags 		=& $this->get('Alltags');
		$used 		=& $this->get('Usedtags');
		$params		=& $mainframe->getParams('com_quickfaq');
		
		//Add the js includes to the document <head> section
		JHTML::_('behavior.formvalidation');
		JHTML::_('behavior.tooltip');

		//ensure $used is an array
		if(!is_array($used)){
			$used = array();
		}
		
		//add css file
		$document->addStyleSheet($this->baseurl.'/components/com_quickfaq/assets/css/quickfaq.css');
		$document->addCustomTag('<!--[if IE]><style type="text/css">.floattext{zoom:1;}, * html #quickfaq dd { height: 1%; }</style><![endif]-->');
		
		//check if we have an id(edit) and check if we have global edit rights or if we are allowed to edit own items.
		if( $item->id > 1 && !($user->authorize('com_quickfaq', 'edit') || ($user->authorize('com_content', 'edit', 'content', 'own') && $item->created_by == $user->get('id')) ) ) {
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}
		
      	if ($user->authorize('com_quickfaq', 'newtags')) {
			$document->addScript( 'components/com_quickfaq/assets/js/tags.js' );
		}
		
		//Get the lists
		$lists = $this->_buildEditLists();

		//Load the JEditor object
		$editor =& JFactory::getEditor();

		//Build the page title string
		$title = $item->id ? JText::_('Edit') : JText::_('New');

		//Set page title
		$document->setTitle($title);

		//get pathway
		$pathway =& $mainframe->getPathWay();
		$pathway->addItem($title, '');

		// Unify the introtext and fulltext fields and separated the fields by the readmore tag
		if (JString::strlen($item->fulltext) > 1) {
			$item->text = $item->introtext."<hr id=\"system-readmore\" />".$item->fulltext;
		} else {
			$item->text = $item->introtext;
		}

		//Ensure the row data is safe html
		JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES );

		$this->assign('action', 	$uri->toString());

		$this->assignRef('item',	$item);
		$this->assignRef('params',	$params);
		$this->assignRef('lists',	$lists);
		$this->assignRef('editor',	$editor);
		$this->assignRef('user',	$user);
		$this->assignRef('tags',	$tags);
		$this->assignRef('used',	$used);

		parent::display($tpl);
	}
	
	/**
	 * Creates the item submit form
	 *
	 * @since 1.0
	 */
	function _buildEditLists()
	{
		//Get the item from the model
		$item 		= & $this->get('Item');
		//get the categories tree
		$categories = quickfaq_cats::getCategoriesTree(1);
		//get ids of selected categories (edit action)
		$selectedcats = & $this->get( 'Catsselected' );
		
		//build selectlist
		$lists = array();
		$lists['cid'] = quickfaq_cats::buildcatselect($categories, 'cid[]', $selectedcats, false, 'class="inputbox required validate-cid" multiple="multiple" size="8"');
		
		$state = array();
		$state[] = JHTML::_('select.option',  1, JText::_( 'PUBLISHED' ) );
		$state[] = JHTML::_('select.option',  0, JText::_( 'UNPUBLISHED' ) );
		//$state[] = JHTML::_('select.option',  -1, JText::_( 'ARCHIVED' ) );
		$state[] = JHTML::_('select.option',  -2, JText::_( 'PENDING' ) );
		$state[] = JHTML::_('select.option',  -3, JText::_( 'OPEN QUESTION' ) );
		$state[] = JHTML::_('select.option',  -4, JText::_( 'IN PROGRESS' ) );

		$lists['state'] = JHTML::_('select.genericlist', $state, 'state', '', 'value', 'text', $item->state );

		return $lists;
	}
}
?>