<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: install.docman.php 628 2008-02-25 00:36:53Z mjaz $
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

require_once (dirname ( __FILE__ ) . DS . 'install.docman.helper.php');
require_once (dirname ( __FILE__ ) . DS . 'alibraries' . DS . 'installer.php');

function com_install() {
	$absolute_path = JPATH_ROOT;
	
	$return = true;
	
	// Logo
	DMInstallHelper::showLogo ();
	
	if (! DMInstallHelper::checkWritable ()) {
		$link = 'index.php?option=com_installer&type=components&task=manage&mosmsg=Select+JoomDOC+and+click+uninstall';
		// this should get the attention of people who prefer to ignore error messages!
?>
		<p style="font-size: 200%">
			Installation failed! Please 
			<a href="<?php echo $link?>">
				click here to uninstall docman
			</a>. Next, make the folders list above writable and try again.
		</p>
<?php
		$return = false;
	}
	
	// Upgrade tables
	DMInstallHelper::upgradeTables ();
	
	// Files
	DMInstallHelper::fileOperations ();
	
	AInstaller::install();
	
	
	// index.html files
	$paths = array ('components' . DS . 'com_joomdoc', 'administrator' . DS . 'components' . DS . 'com_joomdoc', 'mambots' . DS . 'docman', 'joomdocs' );
	foreach ( $paths as $path ) {
		$path = $absolute_path . DS . $path;
		DMInstallHelper::createIndex ( $path );
	}
	
	// Link to add sample data
	DMInstallHelper::cpanel ();
	
	return $return;
}
