<?php
/**
 * @version		$Id: ccbcategory.php 110 2009-04-26 07:59:21Z thomasv $
 * @Project		ccBoard - Joomla! Bulletin Board Extension/Component
 * @author 		Thomas Varghese
 * @package		ccBoard
 * @copyright	Copyright (C) 2008-2009 codeclassic.org. All rights reserved.
 * @license 	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined( '_JEXEC' ) or die( 'Restricted Access' );
class ccboardTableccbCategory extends JTable
{
    var $id = null;
    var $cat_name = null;
    var $ordering = null;


    function  __construct(&$db) {
        parent::__construct('#__ccb_category',  'id', $db);
    }

}
?>
