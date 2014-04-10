<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

class js_JSToolBarMenu
{
	function custom( $task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true ) {
		if( isJ15() ) {
			JToolBarHelper::custom( $task, $iconOver, $icon, $alt, $listSelect );
		}else{
			mosMenuBar::custom( $task, '../components/com_joomlastats/images/j10x_compatibility/icon-32-' . $icon,
			'../components/com_joomlastats/images/j10x_compatibility/icon-32-'.$iconOver, $alt, $listSelect );
		}
	}

	/**
	 * This function add space on tool bar - it is needed by j1.0.15 and banned by j1.5.6
	 */
	function spacerForJ10x() {
		if ( isJ15() ) {
			return;
		}

		mosMenuBar::spacer();
	}

	function CONFIG_MENU() {

		if( isJ15() ) {
			JToolBarHelper::title( 'JoomlaStats'.': <small><small>[ ' . JTEXT::_( 'Configuration' ) . ' ]</small></small>',
			'js_js-logo.png' ); // this generate demand for css style 'icon-48-js_js-logo'
		}

		( isJ15() ? '' : mosMenuBar::startTable() );
		js_JSToolBarMenu::custom('js_do_configuration_set_default', 'js_default.png', 'js_default.png', JTEXT::_( 'Default' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_do_configuration_save', 'js_save.png', 'js_save.png', JTEXT::_( 'Save' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_do_configuration_apply', 'js_apply.png', 'js_apply.png', JTEXT::_( 'Apply' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_statistics_default', 'js_cancel.png', 'js_cancel.png', JTEXT::_( 'Cancel' ), false);
		( isJ15() ? '' : mosMenuBar::endTable() );
	}

	function TOOLS_MENU() {

		if( isJ15() ) {
			JToolBarHelper::title( 'JoomlaStats'.': <small><small>[ ' . JTEXT::_( 'Tools' ) . ' ]</small></small>',
			'js_js-logo.png' ); // this generate demand for css style 'icon-48-js_js-logo'
		}

		( isJ15() ? '' : mosMenuBar::startTable() );
		//js_JSToolBarMenu::custom('js_view_summarize', 'js_summarize.png', 'js_summarize.png', JTEXT::_( 'Summarize' ), false);
		//js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_uninstall', 'js_uninstall.png', 'js_uninstall.png', JTEXT::_( 'Uninstall' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_statistics_default', 'js_back.png', 'js_back.png', JTEXT::_( 'Back' ), false);
		( isJ15() ? '' : mosMenuBar::endTable() );
	}

	function UNINSTALL_MENU() {

		if( isJ15() ) {
			JToolBarHelper::title( 'JoomlaStats'.': <small><small>[ ' . JTEXT::_( 'Uninstall' ) . ' ]</small></small>',
			'js_js-logo.png' ); //this generate demand for css style 'icon-48-js_js-logo'
		}

		( isJ15() ? '' : mosMenuBar::startTable() );
		js_JSToolBarMenu::custom('js_do_uninstall', 'js_uninstall.png', 'js_uninstall.png', JTEXT::_( 'Uninstall' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_tools', 'js_back.png', 'js_back.png', JTEXT::_( 'Back' ), false);
		( isJ15() ? '' : mosMenuBar::endTable() );
	}

	function SUMMARISE_MENU() {

		if( isJ15() ) {
			JToolBarHelper::title( 'JoomlaStats'.': <small><small>[ '. JTEXT::_( 'Summarize' ) .' ]</small></small>',
			'js_js-logo.png' ); // this generate demand for css style 'icon-48-js_js-logo'
		}

		( isJ15() ? '' : mosMenuBar::startTable() );
		js_JSToolBarMenu::custom('js_do_summarize', 'js_summarize.png', 'js_summarize.png', JTEXT::_( 'Summarize' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_tools', 'js_back.png', 'js_back.png', JTEXT::_( 'Back' ), false);
		( isJ15() ? '' : mosMenuBar::endTable() );
	}

	function BACK_TO_STAT_MENU( $task_name ) {

		if( isJ15() ) {
			JToolBarHelper::title( 'JoomlaStats'.': <small><small>[ '.$task_name.' ]</small></small>',
			'js_js-logo.png' ); // this generate demand for css style 'icon-48-js_js-logo'
		}

		( isJ15() ? '' : mosMenuBar::startTable() );
		js_JSToolBarMenu::custom('js_view_statistics_default', 'js_back.png', 'js_back.png', JTEXT::_( 'Back' ), false);
		( isJ15() ? '' : mosMenuBar::endTable() );
	}

	function BACK_TO_MAINTENANCE_MENU( $task_name ) {

		if ( isJ15() ) {
			JToolBarHelper::title( 'JoomlaStats'.': <small><small>[ '.$task_name.' ]</small></small>',
			'js_js-logo.png' ); //this generate demand for css style 'icon-48-js_js-logo'
		}

		( isJ15() ? '' : mosMenuBar::startTable() );
		js_JSToolBarMenu::custom('js_view_tools', 'js_back.png', 'js_back.png', JTEXT::_( 'Back' ), false);
		( isJ15() ? '' : mosMenuBar::endTable() );
	}

	function DEFAULT_MENU( $task_name ) {

		if ( isJ15() ) {
			JToolBarHelper::title( 'JoomlaStats'.': <small><small>[ '.$task_name.' ]</small></small>',
			'js_js-logo.png' ); //this generate demand for css style 'icon-48-js_js-logo'
		}

		( isJ15() ? '' : mosMenuBar::startTable() );
		js_JSToolBarMenu::custom('js_view_statistics_default', 'js_statistics.png', 'js_statistics.png', JTEXT::_( 'Statistics' ), false);
		//js_JSToolBarMenu::spacerForJ10x();
		//js_JSToolBarMenu::custom('js_graphics', 'js_graphics.png', 'js_graphics.png', JTEXT::_( 'Graphics' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_exclude', 'js_exclude.png', 'js_exclude.png', JTEXT::_( 'Exclude' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_configuration', 'js_configuration.png', 'js_configuration.png', JTEXT::_( 'Configuration' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_tools', 'js_tools.png', 'js_tools.png', JTEXT::_( 'Tools' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_status', 'js_status.png', 'js_status.png', JTEXT::_( 'Status' ), false);
		js_JSToolBarMenu::spacerForJ10x();
		js_JSToolBarMenu::custom('js_view_help', 'js_help.png', 'js_help.png', JTEXT::_( 'Help' ), false);
		( isJ15() ? '' : mosMenuBar::endTable() );
	}
}

