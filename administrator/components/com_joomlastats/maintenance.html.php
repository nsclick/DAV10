<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
} 

require_once( dirname(__FILE__) .DS. 'template.html.php' );

$JSTemplate = new js_JSTemplate();
$JSTemplate->jsLoadToolTip();

?>
<div style="font-size: 1px;">&nbsp;</div><!-- This div is needed to show content of tab correctly in 'IE 7.0' in 'j1.5.6 Legacy'. Tested in: FF, IE, j1.0.15, j1.5.6 and works OK -->
<table class="adminform" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
        <td width="200" align="left">
            <input type="button" name="optimize_database" style="width:165px" value="<?php echo JTEXT::_( 'Optimize database' ); ?>" onclick="submitbutton('js_maintenance_do_optimize_database');" />
        </td>
        <td>
            <?php
            echo $JSTemplate->jsToolTip( JTEXT::_( 'Optimize all JoomlaStats database tables' ) ); ?>
        </td>
	</tr>
  	<tr>
        <td>
            <input type="button" name="backup" style="width:165px" value="<?php echo JTEXT::_( 'TLD-Check' ); ?>" onclick="submitbutton('js_do_tldlookup');" />
        </td>
        <td>
            <?php
            echo $JSTemplate->jsToolTip( JTEXT::_( 'This process will perform a TLD-Lookup' ) ); ?>
        </td>
  	</tr>

</table>