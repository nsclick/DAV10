<?php

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// We dont want to unnecessarily load the JS in the Admin
if($mainframe->isAdmin()) {
	return;
}

jimport('joomla.filesystem.file');

if (!JFile::exists(JPATH_ROOT.DS.'components'.DS.'com_jbolo'.DS.'jbolo.php')) {
	return;
}

class plgSystemJbolo extends JPlugin{

	function onAfterInitialise ( ) {
	
		global $mainframe;
		
		/* Need to use $mainframe->addCustomHeadTag() since addScript and addScriptDeclaration do not
		 * load the files in the defined order which causes JS errors
		 */
		$cscript[] = '<script type="text/javascript" src="' . JURI::root() . 'components/com_jbolo/js/jquery.js"></script>';
		$cscript[] = '<script type="text/javascript">$jbolo = jQuery.noConflict();</script>';
		$cscript[] = '<script type="text/javascript" src="' . JURI::root() . 'index.php?option=com_jbolo&amp;action=js&amp;format=raw">
		</script>';
		$cscript[] = '<script type="text/javascript" src="' . JURI::root() . 'components/com_jbolo/js/chat.js"></script>';
		$cscript[] = '<link href="'.JURI::root().'components/com_jbolo/css/chat.css" type="text/css" rel="stylesheet"/>';
		$cscript[] = '<link href="'.JURI::root().'components/com_jbolo/css/screen.css" type="text/css" rel="stylesheet"/>';
		$cscript[] = '<link href="'.JURI::root().'components/com_jbolo/css/jbolo.css" type="text/css" rel="stylesheet"/>';

		$cscript[] = '<!--[if lte IE 7]>';
		$cscript[] = '<link href="'.JURI::root().'components/com_jbolo/css/screen_ie.css" type="text/css" rel="stylesheet"/>';
		$cscript[] = '<![endif]-->';
				
		$mainframe->addCustomHeadTag(implode("\n", $cscript));
	
		return;
	}
	
}
