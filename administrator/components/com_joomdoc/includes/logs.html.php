<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: logs.html.php 608 2008-02-18 13:31:26Z mjaz $
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

class HTML_DMLogs {
    function showLogs($option, $rows, $search, $pageNav)
    {
        $absolute_path = JPATH_ROOT;

        ?>
		<form action="index2.php" method="post" name="adminForm">
        <?php dmHTML::adminHeading( _DML_DOWNLOAD_LOGS, 'logs' )?>
			<div class="dm_filters">
                <?php echo _DML_FILTER;?>
				<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
            </div>
			<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
            <thead>
				<tr>
					<th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" /></th>
					<th class="title" width="10%" nowrap="nowrap"><div align="center"><?php echo _DML_DATE;?></div></th>
					<th class="title" width="20%" nowrap="nowrap"><div align="center"><?php echo _DML_USER;?></div></th>
					<th class="title" width="20%" nowrap="nowrap"><div align="center"><?php echo _DML_IP;?></div></th>
					<th class="title" width="20%" nowrap="nowrap"><div align="center"><?php echo _DML_DOCUMENT;?></div></th>
					<th class="title" width="10%" nowrap="nowrap"><div align="center"><?php echo _DML_BROWSER;?></div></th>
					<th class="title" width="10%" nowrap="nowrap"><div align="center"><?php echo _DML_OS;?></div></th>
				</tr>
             </thead>

             <tfoot><tr><td colspan="11"><?php echo $pageNav->getListFooter();?></td></tr></tfoot>

             <tbody>

				<?php
        $k = 0;
        for ($i = 0, $n = count($rows);$i < $n;$i++) {
            $row = &$rows[$i];
            echo "<tr class=\"row$k\">";
            echo "<td width=\"20\">";
            ?>

			<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id;?>" onclick="isChecked(this.checked);" />
					</td>
					<td align="center">
						<?php echo $row->log_datetime;?>
					</td>
					<td align="center">
						<?php echo $row->user;?>
					</td>
					<td align="center">
						<a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput=<?php echo $row->log_ip;?>" target="_blank"><?php echo $row->log_ip;?></a>
					</td>
					<td align="center">
						 <?php echo $row->dmname;?>
					</td>
					<td align="center">
						 <?php echo $row->log_browser;?>
					</td>
					<td align="center">
						 <?php echo $row->log_os;?>
					</td>
				</tr>
				<?php
            $k = 1 - $k;
        }

        ?>
        </tbody>
		</table>

		<input type="hidden" name="option" value="com_joomdoc" />
		<input type="hidden" name="section" value="logs" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
        <?php echo DOCMAN_token::render();?>
		</form>

		<?php include_once($absolute_path."/components/com_joomdoc/footer.php");
    }
}
