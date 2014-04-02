<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: mod_joomdoc_logs.php 629 2008-02-25 00:45:17Z mjaz $
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


/*
$query = "SELECT l.log_docid, l.log_ip, l.log_datetime, l.log_user, d.dmname, u.name"
        ."\n FROM #__joomdoc_log l, #__joomdoc d, #__users u"
        ."\n WHERE l.log_docid = d.id"
        ."\n AND (l.log_user = u.id OR l.log_user=0)"
        ."\n ORDER BY l.log_datetime DESC";
        */
$query = "SELECT l.log_docid, l.log_ip, l.log_datetime, l.log_user, d.dmname, u.name"
        ."\n FROM (#__joomdoc_log AS l LEFT JOIN #__joomdoc AS d ON l.log_docid = d.id)"
        ."\n LEFT JOIN #__users AS u ON l.log_user = u.id"
        ."\n ORDER BY l.log_datetime DESC";
$database->setQuery($query, 0, $params->get('limit', 10));
$rows = $database->loadObjectList();

?>
<table class="adminlist">
	<thead>
    	<tr>
    	    <th class="title"><?php echo JText::_('Latest Logged Downloads'); ?></th>
            <th class="title"><?php echo JText::_('User'); ?></th>
            <th class="title"><?php echo JText::_('IP'); ?></th>
            <th class="title"><?php echo JText::_('Date'); ?></th>
    	</tr>
	</thead>
	<tbody>
    	<?php if (!$_DOCMAN->getCfg('log')) { ?> 
    		<tr><td colspan="4"><?php echo JText::_('Logging is disabled in the configuration.'); ?></td></tr>
    	<?php } ?>	
        <?php foreach ($rows as $row) { ?>
        	<tr>
        	    <td>
                    <a href="index2.php?option=com_joomdoc&amp;section=documents&amp;task=edit&amp;cid[]=<?php echo $row->log_docid;?>">
                    <?php echo $row->dmname;?>
                    </a>
        	    </td>
                <td align="right"><?php echo ($row->log_user == 0) ? JText::_('Guest') : $row->name; ?></td>
        	    <td align="right"><a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput=<?php echo $row->log_ip;?>" target="_blank"><?php echo $row->log_ip;?></a></td>
        	    <td align="right"><?php echo $row->log_datetime;?></td>
        	</tr>
        <?php } ?>
        <tr><th colspan="4"><?php DOCMAN_Utils::getModuleButtons( isset($name) ? $name : '' ); ?></th></tr>
    </tbody>
</table>