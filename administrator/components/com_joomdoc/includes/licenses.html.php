<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: licenses.html.php 638 2008-03-01 12:49:09Z mjaz $
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

if (defined('_DOCMAN_HTML_LICENSES')) {
    return;
} else {
    define('_DOCMAN_HTML_LICENSES', 1);
}

class HTML_DMLicenses {
    function editLicense($option, &$row)
    {
        $mosConfig_absolute_path = JPATH_ROOT;

        JFilterOutput::objectHTMLSafe($row);

        ?>

        <script language="javascript" type="text/javascript">
            function submitbutton(pressbutton) {
				  var form = document.adminForm;
				  if (pressbutton == 'cancel') {
					submitform( pressbutton );
					return;
				  }

				if (form.name.value == "") {
					alert ( "<?php echo _E_WARNTITLE;?>" );
				} else {
				  <?php 
				  	  jimport( 'joomla.html.editor' );
					  $editor =& JFactory::getEditor();
	                  echo $editor->save( 'license' );	
				  ?>
				  submitform( pressbutton );
				}
			}
        </script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<?php
        $tmp = ($row->id ? _DML_EDIT : _DML_ADD) .' '._DML_LICENSES;
        dmHTML::adminHeading( $tmp, 'licenses' )
        ?>

        <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
				<tr>
					<td width="20%" align="right"><?php echo _DML_NAME;?>:</td>
					<td width="80%">
						<input class="inputbox" type="text" name="name" size="50" maxlength="100" value="<?php echo $row->name;?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo _DML_LICENSE_TEXT;?>:</td>
				<td>
					<?php
        			DOCMAN_Compat::editorArea('editor1', $row->license, 'license', '700', '600', '60', '30');
        			?>
				</td>
			  </tr>

			<input type="hidden" name="id" value="<?php echo $row->id;?>" />
			<input type="hidden" name="option" value="com_joomdoc" />
			<input type="hidden" name="section" value="licenses" />
			<input type="hidden" name="task" value="" />
            <?php echo DOCMAN_token::render();?>
		</form>
	</table>
    <?php include_once($mosConfig_absolute_path."/components/com_joomdoc/footer.php");
    }

    function showLicenses($option, $rows, $search, $pageNav)
    {
        $absolute_path = JPATH_ROOT;

        ?>
		<form action="index2.php" method="post" name="adminForm">
        <?php dmHTML::adminHeading( _DML_LICENSES, 'licenses' )?>
        <div class="dm_filters">
            <?php echo _DML_FILTER_NAME;?>
            <input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
        </div>

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
            <thead>
			<tr>
				<th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" /></th>
				<th class="title" width="30%" nowrap="nowrap"><div align="left"><?php echo _DML_NAME?></div></th>
                <th class="title" width="68%"><div align="left"><?php echo _DML_LICENSE_TEXT?></div></th>
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
    					<td align="left">
    						<a href="#edit" onclick="return listItemTask('cb<?php echo $i;?>','edit')">
    						<?php echo $row->name;?>
    						</a>
    					</td>
                        <td align="left">
                            <?php echo $row->license;?>
                        </td>
    				</tr>
    				<?php
                $k = 1 - $k;
            }

            ?>
            </tbody>
		  </table>


		  <input type="hidden" name="option" value="com_joomdoc" />
		  <input type="hidden" name="section" value="licenses" />
		  <input type="hidden" name="task" value="licenses" />
		  <input type="hidden" name="boxchecked" value="0" />
          <?php echo DOCMAN_token::render();?>
		</form>
	  <?php include_once($absolute_path."/components/com_joomdoc/footer.php");
    }
}

