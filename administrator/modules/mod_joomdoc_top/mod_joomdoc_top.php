<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: mod_joomdoc_top.php 561 2008-01-17 11:34:40Z mjaz $
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

global $_DOCMAN;
$database = &JFactory::getDBO();
$_DOCMAN->setType(_DM_TYPE_MODULE);
require_once($_DOCMAN->getPath('classes', 'utils'));

$query = "SELECT * FROM #__joomdoc "
        ."\n ORDER BY dmcounter DESC ";
$database->setQuery( $query, 0, $params->get('limit', 10));
$rows = $database->loadObjectList();
?>

<table class="adminlist">
	<thead>
    	<tr>
    	    <th class="title"><?php echo JText::_('Most Downloaded Documents'); ?></th>
            <th class="title"><?php echo JText::_('Hits'); ?></th>
    	</tr>
	</thead>
	<tbody>
        <?php if (!count($rows)) { ?>
        	<tr><td colspan="2"><?php echo JText::_('There are no documents downloaded.'); ?></td></tr>
        <?php } ?>	
        <?php foreach ($rows as $row) { ?>
        	<tr>
        	    <td><a href="#edit" onClick="submitcpform('<?php echo $row->id;?>', '<?php echo $row->id;?>')"><?php echo $row->dmname;?></a>
        	    </td>
        	    <td align="right"><?php echo $row->dmcounter;?></td>
        	</tr>
        <?php } ?>
        <tr><th colspan="2"><?php DOCMAN_Utils::getModuleButtons( isset($name) ? $name : '' ); ?></th></tr>
    </tbody>
</table>