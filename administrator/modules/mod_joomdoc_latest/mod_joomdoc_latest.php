<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: mod_joomdoc_latest.php 561 2008-01-17 11:34:40Z mjaz $
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

$query = "SELECT id, dmname, approved, published, catid, dmdate_published"
     . "\n FROM #__joomdoc"
     . "\n ORDER BY dmdate_published DESC";
$limit = $params->get('limit', 10);
$database->setQuery( $query, 0, $limit );
$rows = $database->loadObjectList();

?>
<table class="adminlist">
	<thead>
		<tr>
	    	<th class="title"><?php echo JText::_('Most Recent Documents'); ?></th>
        	<th class="title"><?php echo JText::_('Date added'); ?></th>
		</tr>
	</thead>	
	<tbody>
        <?php if (!count($rows)) { ?>
        	<tr><td colspan="2"><?php echo JText::_('There are no documents'); ?></td></tr>
       	<?php } ?> 	
        <?php foreach ($rows as $row) { ?>
        	<tr>
        	    <td><a href="index2.php?option=com_joomdoc&amp;section=documents&task=edit&amp;cid[]=<?php echo $row->id ?>"><?php echo $row->dmname;?></a>
        	    <?php if ($row->approved == '0') echo '(' . JText::_('not approved') . ')'; ?>
        	    <?php if ($row->published == '0') echo '(' . JText::_('not published') . ')'; ?>
        	    </td>
        	    <td align="right"><?php echo $row->dmdate_published;?></td>
        	</tr>
        <?php }?>
        <tr><th colspan="2"><?php DOCMAN_Utils::getModuleButtons( isset($name) ? $name : '' ) ?></th></tr>
    </tbody>
</table>