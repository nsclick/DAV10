<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: mod_joomdoc_approval.php 637 2008-03-01 10:36:40Z mjaz $
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
require_once($_DOCMAN->getPath('classes', 'token'));

$query = "SELECT id, dmname, catid, dmdate_published, dmlastupdateon, approved"
        ."\n FROM #__joomdoc"
        ."\n WHERE approved = 0"
        ."\n ORDER BY dmlastupdateon DESC";
$database->setQuery( $query, 0, $params->get('limit', 10));
$rows = $database->loadObjectList();

?>
<table class="adminlist">
    <thead>
		<tr>
        	<th class="title"><?php echo JText::_('Approve'); ?></th>
        	<th class="title"><?php echo JText::_('Edit Document'); ?></th>
        	<th class="title"><?php echo JText::_('Last Edited'); ?></th>
		</tr>
	</thead>
	<tbody>
    	<?php if (!count($rows)) { ?>
    		<tr><td colspan="3"><?php echo JText::_('All documents are approved'); ?></td></tr>
    	<?php } ?>	
        <?php foreach ($rows as $row) { ?>
        	<tr>
                <td width="5%" style="text-align:center">
                    <a href="index2.php?option=com_joomdoc&amp;section=documents&amp;task=approve&cid[]=<?php echo $row->id?>&amp;<?php echo DOCMAN_Token::get();?>=1&amp;redirect=index2.php%3Foption%3Dcom_joomdoc">
                    	<img src="images/publish_r.png" border=0 alt="approve" />
                    </a>
                </td>
                <td><a href="index2.php?option=com_joomdoc&amp;section=documents&task=edit&amp;cid[]=<?php echo $row->id ?>"><?php echo $row->dmname;?></a></td>
                <td align="right"><?php echo $row->dmlastupdateon;?></td>
        	</tr><?php
        }?>
        <tr><th colspan="3"><?php DOCMAN_Utils::getModuleButtons( isset($name) ? $name : '' ) ?></th></tr>
    </tbody>
</table>