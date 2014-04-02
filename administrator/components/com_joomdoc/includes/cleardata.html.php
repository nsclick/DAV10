<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: cleardata.html.php 608 2008-02-18 13:31:26Z mjaz $
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

if (defined('_DOCMAN_HTML_CLEARDATA')) {
    return;
} else {
    define('_DOCMAN_HTML_CLEARDATA', 1);
}

class HTML_DMClear {
    function showClearData( $rows ) {
        $mosConfig_absolute_path = JPATH_ROOT;
    	?><form action="index2.php" method="post" name="adminForm">
        <?php dmHTML::adminHeading( _DML_CLEARDATA, 'cleardata' )?>

        <table class="adminlist">
          <thead>
          <tr>
            <th width="20" align="left">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" />
            </th>
            <th width="98%" align="left">
                <?php echo _DML_CLEARDATA_ITEM;?>
            </th>
          </tr>
          </thead>
          <tbody>
          <?php
          $k = 0;
          foreach( $rows as $i => $row ){?>
            <tr class="row<?php echo $k;?>">
                <td width="20">
                    <?php echo JHTML::_('grid.id',$i, $row->name);?>
                </td>
                <td>
                    <?php echo $row->friendlyname; ?>
                </td>
            </tr><?php
            $k = 1-$k;
          };?>
          </tbody>
        </table>
        <input type="hidden" name="option" value="com_joomdoc" />
        <input type="hidden" name="section" value="cleardata" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo DOCMAN_token::render();?>
        </form>
        <?php include_once($mosConfig_absolute_path."/components/com_joomdoc/footer.php");

    }
}
