<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: toolbar.docman.html.php 651 2008-03-20 20:33:15Z mjaz $
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

if (defined('_DOCMAN_TOOLBAR')) {
    return;
} else {
    define('_DOCMAN_TOOLBAR', 1);
}

class TOOLBAR_docman {
    function NEW_DOCUMENT_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save();
        dmToolBar::apply();
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function MOVE_DOCUMENT_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save('move_process');
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function COPY_DOCUMENT_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save('copy_process');
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function DOCUMENTS_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::publishList();
        dmToolBar::unpublishList();
        dmToolBar::addNew();
        dmToolBar::editList();
        dmToolBar::copy('copy_form');
        dmToolBar::move('move_form');
        dmToolBar::deleteList();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function UPLOAD_FILE_MENU()
    {
        $step = (int) JRequest::getVar( 'step', '');
        dmToolBar::startPanelle();
        dmToolBar::logo();
        switch ($step) {
            case '2':
            case '4';
                dmToolBar::back( 'back',_DML_TOOLBAR_BACK, 'index2.php?option=com_joomdoc&amp;section=files&amp;task=upload');
                dmToolBar::divider();
                break;
            default:
                break;
        }
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function FILES_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::addNewDocument();
        dmToolBar::deleteList();
        dmToolBar::upload();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function EDIT_CATEGORY_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save();
        dmToolBar::apply();
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function CATEGORIES_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::publishList();
        dmToolBar::unpublishList();
        dmToolBar::addNew('new', _DML_ADD);
        dmToolBar::editList();
        dmToolBar::deleteList();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function LOGS_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::deleteList();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function EDIT_GROUPS_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save('saveg');
        dmToolBar::apply();
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function GROUPS_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::addNew('new', _DML_ADD);
        dmToolBar::editList();
        dmToolBar::deleteList();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function EMAIL_GROUPS_MENU(){
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::sendEmail();
        dmToolBar::cancel();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();

    }

    function EDIT_LICENSES_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save();
        dmToolBar::apply();
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function LICENSES_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::addNew('edit', _DML_ADD);
        dmToolBar::editList();
        dmToolBar::deleteList();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function STATS_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function NEW_THEME_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::back();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function EDIT_THEME_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save();
        dmToolBar::apply();
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function CSS_THEME_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save('save_css');
        dmToolBar::apply('apply_css');
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function THEMES_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::publishList( 'publish', _DML_TOOLBAR_DEFAULT);
        dmToolBar::addNew('new', _DML_ADD );
        dmToolBar::editList();
        dmToolBar::deleteList();
        dmToolBar::editCss();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    /* TEMPORARILY REMOVED UPDATE FEATURE (Mjaz)
    function UPDATES_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::cpanel();
        dmToolBar::endPanelle();
    }
    */

    function CONFIG_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::save();
        dmToolBar::apply();
        dmToolBar::cancel();
        dmToolBar::spacer();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function CPANEL_MENU()
    {
        dmToolBar::startPanelle();
        dmToolBar::endPanelle();
    }

    function CREDITS_MENU(){
    	dmToolBar::startPanelle();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }

    function CLEARDATA_MENU(){
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::clear();
        dmToolBar::divider();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }
    function _DEFAULT()
    {
        dmToolBar::startPanelle();
        dmToolBar::logo();
        dmToolBar::addNew();
        dmToolBar::editList();
        dmToolBar::deleteList();
        dmToolBar::cpanel();
        dmToolBar::help();
        dmToolBar::spacer();
        dmToolBar::endPanelle();
    }
} // end class

