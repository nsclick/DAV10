<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: tasks.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Default DOCman Theme
 *
 * Creator:  The DOCman Development Team
 * Website:  http://www.joomlatools.org/
 * Email:    support@joomlatools.org
 * Revision: 1.4
 * Date:     February 2007
 **/

/*
* Display the document tasks (called by document/list_item.tpl.php and documents/document.tpl.php)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this-	>doc->buttons (array) : holds the tasks a user can preform on a
*document
*/

foreach($this->doc->buttons as $button) {
    $popup = ($button->params->get('popup', false)) ? 'type="popup"' : '';
    $attr = '';
    if($class = $button->params->get('class', '')) {
    	$attr = 'class="' . $class . '"';
    }
	?><li <?php echo $attr?>>
        <a href="<?php echo $button->link?>" <?php echo $popup?>>
            <?php echo $button->text ?>
        </a>
    </li><?php
}