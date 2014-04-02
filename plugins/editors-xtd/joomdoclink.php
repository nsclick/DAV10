<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: joomdoclink.php 780 2009-02-08 15:53:30Z mathias $
 * @package JoomDOC
 * @copyright (C) 2003-2008 The DOCman Development Team
 *            Improved to JoomDOC by Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/

defined('_JEXEC') or die('Restricted access');

/**
 * plgButtonJoomDocLink Class
 */
class plgButtonJoomDocLink extends JPlugin
{

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param   object $subject The object to observe
     * @param   array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function plgButtonJoomDocLink (& $subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
     * Display the button
     */
    function onDisplay ($name)
    {
        global $mainframe;
        
        $doc = & JFactory::getDocument();
        
        $doclink_url = JURI::root() . "plugins/editors-xtd/joomdoclink";
        $docman_url = JURI::root() . "components/com_joomdoc/";
        
        $style = ".button2-left .joomdoclink {
                background:transparent url($doclink_url/images/btn_joomdoclink.png) no-repeat scroll 100% 0pt;
                }";
        $doc->addStyleDeclaration($style);
        
        $js = $docman_url . 'assets/js/';
        
        $doc->addScript($js . 'doclink.js');
        $doc->addScript($js . 'dldialog.js');
        $doc->addScript($js . 'popup.js');
        $doc->addScript($js . 'dlutils.js');
        
        $button = new JObject();
        $button->set('modal', true);
        $button->set('text', JText::_('JoomDOC Link'));
        $button->set('name', 'joomdoclink');
        $button->set('link', 'index.php?option=com_joomdoc&task=doclink&e_name=' . $name);
        $button->set('options', "{handler: 'iframe', size: {x: 570, y: 510}}");
        
        return $button;
    }
}
?>
