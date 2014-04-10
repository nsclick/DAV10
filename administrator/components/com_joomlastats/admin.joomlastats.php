<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */



if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

global $mainframe;


{//check user autorization (code from 'com_banners' from j1.0.15 and from 1.5.6)
	//code is divided to j1.0 and 1.5 becouse in j1.5 there was some problems with global variable $my
	if (!defined('_JEXEC')) { //this is body of function js_getJoomlaVesrion_IsJ15x()
		// ensure user has access to this function
		if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
				| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_joomlastats' ))) {
			mosRedirect( 'index2.php', _NOT_AUTH );
		}
	} else {	
		// Make sure the user is authorized to view this page
		global $mainframe;
		$user = & JFactory::getUser();
		//if( !$user->authorize( 'com_config', 'manage' ) ) {//if we use this line only 'super administrators' will be able to view JoomlaStats. Mic suggest to use that way - it is most restricted access
		//if (!$user->authorize( 'com_joomlastats', 'manage' )) { //this line is wrong!!! ACL has not got JoomlaStats registered! This line always fail
		if (!$user->authorize( 'com_components', 'manage' )) { //this line allow all (that have permission to login to joomla back-end) to view JoomlaStats
			$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
		}
	}
}

if( !defined( 'JPATH_BASE' ) ) {
	define( 'JPATH_BASE', dirname(__FILE__) );
}

require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'base.classes.php' ); // needed to load JS configuration and jxtools
require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'util.classes.php' ); // needed to load language (texts)

if( !defined( '_JEXEC' ) ) {
	// check and get language
	$path_to_admin = $GLOBALS['mosConfig_absolute_path'] .DIRECTORY_SEPARATOR. 'administrator' .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_joomlastats' .DIRECTORY_SEPARATOR. 'language';
	jxTools::_checkLanguage( false, $path_to_admin );
}


//add css style sheets to page <head>
if (js_getJoomlaVesrion_IsJ15x() == true) {
	$cssFile = JURI::base() . 'components/com_joomlastats/assets/' . 'icon.css';
	//$html = '<link href="'. $cssFile . '" type="text/css" rel="stylesheet" />';
	$doc =& JFactory::getDocument();
	$doc->addStyleSheet( $cssFile );
} else {
	global $mosConfig_live_site;
	$js_icon_css_html_code = '<link href="'.$mosConfig_live_site .'/administrator/components/com_joomlastats/assets/'.'icon.css" type="text/css" rel="stylesheet" />';
	$mainframe->addCustomHeadTag( $js_icon_css_html_code );
}


$task = JRequest::getVar( 'task', 'js_view_statistics_default' );

$JSConf = new js_JSConf();
$JSUtil = new js_JSUtil();

// 'js_view_statistics_default' means that we should display task that user select as 'default start page' in configuration (user selection)
if( $task == 'js_view_statistics_default' ) {
	$task = $JSConf->startoption;
	if (js_getJoomlaVesrion_IsJ15x() == true) {
		JRequest::setVar( 'task', $task );
	} else {
		$_REQUEST['task'] = $task;//works for j1.0.15
	}
}

js_echoJSDebugInfo('task: \''.$task.'\'', '');

switch( $task ) {
	
	/** 
	 *  STATICTIC PAGES
	 * 
	 *  NEW ENGINE - we should write code like in those pages! 
	 */
	case 'r06':
	case 'r07':
	case 'r11':
	{
			
		require_once( dirname( __FILE__ ) .DS. 'statistics.php' );
		$JSStatistics = new js_JSStatistics();
			
		switch( $task ) {
			case 'r06':
				echo $JSStatistics->viewPageHits();
				break;
			case 'r07':
				echo $JSStatistics->viewSystems();
				break;
			case 'r11':
				echo $JSStatistics->viewNotIdentifiedVisitors();
				break;
				
			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');
				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}
	}
	break;
	
	
	
	/** 
	 *  STATICTIC PAGES
	 * 
	 *  - OLD STYLE - will be moved to new engine 
	 */
	case 'r01':
	case 'r02':
	case 'r03':
	case 'r03a':
	case 'r03b':
	//case 'r04':
	case 'r05':
	case 'r08':
	case 'r09':
	case 'r09a':
	case 'r10':
	case 'r12':
	case 'r14':
	case 'r15':
		{
		//@At I know, a lot of messy code here. It will disappear if JoomlaStats_Engine move to js_JSStatistics classes
		require_once( dirname( __FILE__ ) .DS. 'admin.joomlastats.html.php' );//deprecated
		require_once( dirname( __FILE__ ) .DS. 'statistics.php' );
		require_once( dirname( __FILE__ ) .DS. 'statistics.common.php' );
		require_once( dirname( __FILE__ ) .DS. 'filters.php' );

		$show_search_filter = false;

		if( $task == 'r03' ) {
			// we are on the visitors table
			$show_search_filter = true;
		}

		$FilterSearch = new js_JSFilterSearch();
		$FilterSearch->readSearchStringFromRequest();
		$FilterSearch->setSearchHint( JTEXT::_( 'Search tip' ) );
		$FilterSearch->show_search_filter = $show_search_filter;

		//global $mosConfig_offset;
		$TimePeriod = new js_JSFilterTimePeriodDeprecated();
		$TimePeriod->SetDMY2Now( $mainframe->getCfg( 'offset' ) );
		$TimePeriod->readDateFromRequest( $mainframe->getCfg( 'offset' ), $JSConf->startdayormonth );

		$FilterDomain = new js_JSFilterDomain();
		$FilterDomain->readDomainStringFromRequest();

		$JoomlaStatsEngine = new JoomlaStats_Engine( $task, $JSConf );//deprecated
		$JoomlaStatsEngine->d = $TimePeriod->d;
		$JoomlaStatsEngine->m = $TimePeriod->m;
		$JoomlaStatsEngine->y = $TimePeriod->y;

		$DatabaseSizeHtmlCode = $JSUtil->getJSDatabaseSizeHtmlCode();

		$JSStatistics = new js_JSStatistics();
		//$JoomlaStatsEngine->JoomlaStatsHeader($FilterSearch, $show_search_filter, $TimePeriod, $JoomlaStatsEngine->vid, $JoomlaStatsEngine->moreinfo, $DatabaseSizeHtmlCode, $JSVersion, $FilterDomain);

		$JSStatisticsCommon = new js_JSStatisticsCommon();

		echo $JSStatisticsCommon->getJSStatisticsHeaderHtmlCode($JSConf, $FilterSearch, $TimePeriod, $JoomlaStatsEngine->vid, $JoomlaStatsEngine->moreinfo, $FilterDomain);

	   	$JoomlaStatsEngine->resetVar( 1 );

		switch( $task ) {
			case 'r01':
				echo $JoomlaStatsEngine->ysummary();
				break;

			case 'r02':
				echo $JoomlaStatsEngine->msummary();
				break;

			case 'r03':
				echo $JoomlaStatsEngine->VisitInformation();
				break;

			case 'r03a':
				echo $JoomlaStatsEngine->moreVisitInfo();
				break;

			case 'r03b':
				echo $JoomlaStatsEngine->morePathInfo();
				break;

			///RB: Is this one (r04) added by mic or should it be removed?
			//case 'r04':
			//	echo $JoomlaStatsEngine->botsInformation();
			//	break;
			case 'r05':
				echo $JoomlaStatsEngine->getVisitorsByTld();
				break;
			case 'r06':
				echo $JoomlaStatsEngine->getPageHits();
				break;
			case 'r08':
				echo $JoomlaStatsEngine->getBrowsers();
				break;
			case 'r09':
				echo $JoomlaStatsEngine->getBots();
				break;
			case 'r09a':
				echo $JoomlaStatsEngine->moreVisitInfo();
				break;
			case 'r10':
				echo $JoomlaStatsEngine->getReferrers();
				break;
			case 'r12':
				echo $JoomlaStatsEngine->getUnknown();
				break;
			case 'r14':
				echo $JoomlaStatsEngine->getSearches();
				break;

			// new mic 20081016: resolution
			case 'r15':
				$buid = $JoomlaStatsEngine->Buid();
				echo $JSStatistics->viewResolutions( $TimePeriod, $buid );
				break;

			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}

		echo $JSStatisticsCommon->getJSStatisticsFooterHtmlCode();
	}
	break;

	// new mic 2008.10.25
	case 'js_graphics':
		require_once( dirname( __FILE__ ) .DS. 'tools.php' );
		$JSTools = new js_JSTools();
		$JSTools->doGraphic();
		break;
	
	/** tools options from tool bar (without options from tabs) */
	case 'js_view_tools':
	case 'js_view_uninstall':
	case 'js_do_uninstall':
	case 'js_view_summarize':
	case 'js_do_summarize':
		{
		require_once( dirname( __FILE__ ) .DS. 'tools.php' );
		$JSTools = new js_JSTools();

		switch( $task ) {
			case 'js_view_tools':
				$JSTools->viewJSToolsPage();
				break;

			case 'js_view_uninstall':
				$JSTools->viewJSUninstallPage();
				break;

			case 'js_do_uninstall':
				$JSTools->doJSUninstall();
				break;

			case 'js_view_summarize':
				$JSTools->viewJSSummarizePage();
				break;

			case 'js_do_summarize':
				$JSTools->doJSSummarize();
				break;

			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}
	}
	break;

	/** configuration page options */
	case 'js_view_configuration':
	case 'js_do_configuration_save':
	case 'js_do_configuration_apply':
	case 'js_do_configuration_set_default':
		{
		require_once( dirname( __FILE__ ) .DS. 'configuration.php' );
		$JSConfiguration = new js_JSConfiguration();

		switch( $task ) {
			case 'js_view_configuration':
				$JSConfiguration->viewJSConfigurationPage();
				break;

			case 'js_do_configuration_save':
				//@bug insted of 'r01' should be page from which user went to configuration mic: than use returnURL
				$JSConfiguration->SetConfiguration( $JSConf->startoption );
				break;

			case 'js_do_configuration_apply':
				$JSConfiguration->SetConfiguration( 'js_view_configuration' );
				break;

			case 'js_do_configuration_set_default':
				$JSConfiguration->SetDefaultConfiguration();
				break;

			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}
	}
	break;

	case 'js_view_status':
		require_once( dirname( __FILE__ ) .DS. 'status.php' );

		/** $prevTask this member is used to back to stats to the same subpage */
		$prevTask = 'r01';//@bug insted of 'r01' should be page from which user went to configuration
		$JSStatus = new js_JSStatus();
		$JSStatus->viewJSStatusPage( $prevTask );
		break;

	/** Exclude Manager page options */
	case 'js_view_exclude': //old js_view_ip_list
	case 'js_do_ip_exclude': //old exclude
	case 'js_do_ip_include': //old unexclude
		{
		require_once( dirname( __FILE__ ) .DS. 'exclude.php' );
		$JSExclude = new js_JSExclude();

		switch( $task ) {
			case 'js_view_exclude':
				$JSExclude->viewJSExcludeManager();
				break;

			case 'js_do_ip_exclude':
				$JSExclude->excludeIpAddressArr( 'exclude' );
				break;

			case 'js_do_ip_include':
				$JSExclude->excludeIpAddressArr( 'include' );
				break;

			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}
	}
	break;

	/** options from maintenance tab */
	case 'js_maintenance_do_optimize_database':
	case 'js_maintenance_do_database_backup_partial':
	case 'js_maintenance_do_database_backup_full':
	case 'js_maintenance_do_database_initialize_with_sample_data':
	case 'js_do_tldlookup':
	{
		require_once( dirname( __FILE__ ) .DS. 'maintenance.php' );
		$JSMaintenance = new js_JSMaintenance();

		switch( $task ) {
			case 'js_maintenance_do_optimize_database':
				$JSMaintenance->doOptimizeDatabase();
				break;

			case 'js_maintenance_do_database_backup_partial':
				$JSMaintenance->backupDatabase( false );
				break;

			case 'js_maintenance_do_database_backup_full':
				$JSMaintenance->backupDatabase( true );
				break;

			case 'js_do_tldlookup':
				require_once( dirname( __FILE__ ) .DS. 'tld.php' );
				$JSTld = new js_JSTld();
				$JSTld->doJSTldLookUp();
				break;

			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}
	}
	break;

	/** export tab in maintenance page */
	case 'js_export_do_js2csv':
		{
		require_once( dirname( __FILE__ ) .DS. 'export.php' );
		$JSExport = new js_JSExport();

		switch( $task ) {
			case 'js_export_do_js2csv':
				echo $JSExport->exportJSToCsv();
				break;

			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}
	}
	break;
	

	/** options from TLD tab */
	case 'js_tld_do_get_tld_from_table':
	case 'js_tld_do_get_tld_from_ripe':
	{
		require_once( dirname( __FILE__ ) .DS. 'tld.php' );
		$JSTld = new js_JSTld();

		switch( $task ) {
			case 'js_tld_do_get_tld_from_table':
				echo $JSTld->doGetTldFromTable();
				break;

			case 'js_tld_do_get_tld_from_ripe':
				echo $JSTld->doGetTldFromRipe();
				break;

			default:
				$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

				if( isJ15() ) {
					JError::raiseError( '', JTEXT::_( $msg ) );
				}else{
					mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
				}
				break;
		}
	}
	break;
		
	case 'js_view_help':
		require_once( dirname(__FILE__) .DS. 'template.html.php' );
		$JSTemplate = new js_JSTemplate();
		echo $JSTemplate->generateBeginingOfAdminForm( /*'js_view_help'*/ );
		echo JTEXT::_( 'JoomlaStats Help - Whole Page');
		echo $JSTemplate->generateEndOfAdminForm();
		break;

	case 'whois':
		require_once( dirname(__FILE__) .DS. 'tools' .DS. 'whois.class.php' );
		$whois = new whois();
		require_once( dirname(__FILE__) .DS. 'tools' .DS. 'whois.popup.php' );
		break;

	default:
		/** this code should never be executed, if it is, something is wrong */
		$msg = JTEXT::_( 'Something went wrong, please inform the developer - thank you!');

		if( isJ15() ) {
			JError::raiseError( '', JTEXT::_( $msg ) );
		}else{
			mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_statistics_default', $msg );
		}

		break;
}