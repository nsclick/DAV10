<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: pathway.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display the pathway (required)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this->links (array) : an array of link objects
*
*/


/*
* Traverse through the links object array and display each link,
* remove the last item of the array and only display it's name.
*
* Link object variables
*	$link->link (string) : url of the link
*	$link->name (string) : name of the link
*	$link->title (string): title of the link
*/
global $mainframe;

if(defined('_DM_J15')){
    $pathway = & $mainframe->getPathWay();
    $first = array_shift($this->links);

    foreach($this->links as $link) {
        $uri = str_replace(JURI::root(), '', $link->link);
        $uri = str_replace('&amp;', '&', $uri);
        $pathway->addItem($link->title, $uri);
    }
} else {
    $last = array_pop($this->links);
    $first = array_shift($this->links);

    foreach($this->links as $link) :
    	ob_start();
        ?><a title="<?php echo $link->title; ?>" href="<?php echo $link->link; ?>">
        <?php echo $link->title; ?></a><?php
        $mainframe->appendPathway( ob_get_clean() );
    endforeach;
    $mainframe->appendPathway( $last->title );
}
