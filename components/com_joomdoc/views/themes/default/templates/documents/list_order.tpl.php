<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: list_order.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display the documents list ordering (called by document/list.tpl.php)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this->order->links     (array)  : holds an array of order by task links
*  $this->order->orderby   (string) : current orderby setting
*  $this->order->direction (string) : current order direction
*/
?>
<div class="dm_orderby"> <?php echo _DML_TPL_ORDER_BY ?> :
<?php
	if($this->order->orderby != 'name') :
		?><a href="<?php echo $this->order->links['name'] ?>"><?php echo _DML_TPL_ORDER_NAME ?></a> | <?php
	else :
 		?><strong><?php echo _DML_TPL_ORDER_NAME ?> </strong> | <?php
 	endif;

	if($this->order->orderby != 'date') :
 		?><a href="<?php echo $this->order->links['date'] ?>"><?php echo _DML_TPL_ORDER_DATE ?></a> | <?php
 	else :
 		?><strong><?php echo _DML_TPL_ORDER_DATE ?> </strong> | <?php
 	endif;

 	if($this->order->orderby != 'hits') :
 		?><a href="<?php echo $this->order->links['hits'] ?>"><?php echo _DML_TPL_ORDER_HITS ?></a> <?php
 	else :
 		?><strong><?php echo _DML_TPL_ORDER_HITS ?> </strong> | <?php
 	endif;

	if ($this->order->direction == 'ASC') :
		?><a href="<?php echo $this->order->links['dir'] ?>">[ <?php echo _DML_TPL_ORDER_DESCENT ?> ]</a><?php
   	else :
       	 ?><a href="<?php echo $this->order->links['dir'] ?>">[ <?php echo _DML_TPL_ORDER_ASCENT ?> ]</a><?php
    endif;
?>
</div>