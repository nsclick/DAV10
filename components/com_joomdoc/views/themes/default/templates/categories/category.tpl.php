<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: category.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display category details (required)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this->data		(object) : holds the category data
*  $this->links 	(object) : holds the category operations
*  $this->paths 	(object) : holds the category paths
*/
?>

<div class="dm_cat">

<?php
    if($this->data->image) :
        ?><img class="dm_thumb" src="<?php echo $this->paths->thumb; ?>" style="float:<?php echo $this->data->image_position;?>" alt="" /><?php
    endif;

    if($this->data->title != '') :
        ?><div class="dm_name"><?php echo $this->data->title;?></div><?php
    endif;

	if($this->data->description != '') :
		?><div class="dm_description"><?php echo $this->data->description;?></div><?php
	endif;
?>
</div>
<div class="clr"></div>
