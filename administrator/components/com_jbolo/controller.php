<?php 
/**
 * @This component is to be converted from
 * joomla1.o to 1.5 This is the file where 
 * the control come after calling by main file 
 * in this component main file is invitex.php;
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class JboloController extends JController
{	
	function display()
	{
		global $mainframe;

		$vName = JRequest::getCmd('view', 'config');
		$controllerName = JRequest::getCmd( 'controller', 'config' );
		
		JSubMenuHelper::addEntry(JText::_('MENU_TITLE2'), 'index.php?option=com_jbolo');
		
		$vLayout = JRequest::getCmd( 'layout', 'config' );
		$mName = 'config';

		$document = &JFactory::getDocument();
		$vType		= $document->getType();

		// Get/Create the view
		$view = &$this->getView( $vName, $vType);

		// Get/Create the model
		if ($model = &$this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}

		// Set the layout
		$view->setLayout($vLayout);

		// Display the view
		$view->display();
	}	
}
