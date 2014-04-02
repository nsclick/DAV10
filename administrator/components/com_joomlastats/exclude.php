<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

require_once( dirname(__FILE__) .DS. 'base.classes.php' );
require_once( dirname(__FILE__) .DS. 'exclude.html.php' );

/**
 *  Object of this class generate 'Exclude Manager' page and perform exclude/include actions
 *
 *  NOTICE: This class should contain only set of static, argument less functions that are called by task/action
 */
class js_JSExclude extends JSBasic
{
	/**
	 * this function show 'JS Exclude Manager'
	 * old function name showIpAddresses
	 *
	 * @return unknown
	 */
	function viewJSExcludeManager()	{
		global $mainframe, $option;

		$this->_getDB();

		$limit		= $mainframe->getUserStateFromRequest( 'viewlistlimit', 'limit', 10 );
		$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
		$search		= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
		$search		= $this->db->getEscaped( trim( strtolower( $search ) ) );

		$where = array();

		if( isset( $search ) && strlen($search) > 0 ) {
			$where[] = '(ip LIKE \'%' . $search . '%\''
			. ' OR nslookup LIKE \'%' . $search . '%\''
			. ' OR browser LIKE \'%' . $search . '%\''
			. ' OR system LIKE \'%' . $search . '%\')';
		}

		$query= 'SELECT COUNT(*)'
		. ' FROM #__jstats_ipaddresses'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '')
		;
		$this->db->setQuery( $query );
		$total = $this->db->loadResult();
		if( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		if( isJ15() ) {
			jimport( 'joomla.html.pagination' );
			$pagination = new JPagination( $total, $limitstart, $limit );
		}else{
			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			$pagination = new mosPageNav( $total, $limitstart, $limit );
		}

		$query = 'SELECT id, ip, nslookup, system, browser, exclude'
		. ' FROM #__jstats_ipaddresses'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		. ' ORDER BY exclude DESC, ip DESC'
		;
		$this->db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $this->db->loadObjectList();
		if( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$JSExcludeTpl = new js_JSExcludeTpl();
		$JSExcludeTpl->viewJSExcludeManagerPageTpl( $rows, $pagination, $search );
	}

	/**
	 * This function include/exclude addresses that are in $_REQUEST['cid'] array
	 * old name excludeIpAddress
	 *
	 * @param string	$action = 'include': to include addresses; 'exclude': to exlcude addresses
	 */
	function excludeIpAddressArr( $action ) {
		global $mainframe;

		$this->_getDB();

		$cidv	= JRequest::getVar( 'cid', 0 );
		$vid	= JRequest::getVar( 'vid', array( 0 ) );
		$cid	= array( 0 );
		$block	= 0;

		if ( $action == 'exclude' ) {
			$block = 1;
		}

		if( is_array( $cidv ) ) {
			$cid = $cidv;
		}else{
			if( $cidv !== 0 ) {
				$cid[0] = $cidv;
			}
		}

		if( ( count( $vid ) > 0 ) && ( $vid[0] != 0 ) ) {
			$cid[0] = $vid;
		}

		if( count( $cid ) < 1 ) {
			$task_name = $block ? 'js_do_ip_exclude' : 'js_do_ip_include';
			echo '<script type="text/javascript">alert(\'' . JTEXT::_( 'Please choose an entry to' ) . ': ' . $task_name . '\'); window.history.go(-1);</script>' . "\n";
			exit;
		}

		$cids = 0;
		if( count( $cid ) > 1 ) {
			$cids = implode( ',', $cid );
		}else{
			$cids = $cid[0];
		}

		$query = 'UPDATE #__jstats_ipaddresses'
		. ' SET exclude = \'' . $block . '\''
		. ' WHERE id IN (' . $cids . ')'
		;
		$this->db->setQuery( $query );
		if( !$this->db->query() ) {
			echo '<script type="text/javascript">alert(\''.$database->getErrorMsg().'\');window.history.go(-1);</script>' ."\n";
			exit();
		}

		if( $block ) {
			$msg = JTEXT::_( 'IP address successfully excluded' );
		}else{
			$msg = JTEXT::_( 'IP address successfully included' );
		}

		//redirect to approprate page
		$task = 'js_view_exclude'; // return to 'Exclude Manager' page

		// use vid parameter, because we didn't want to use extra paramater to parse
		if( ( count( $vid ) > 0 ) && ( $vid[0] !=0 ) ) {
			//in case if function is called from the statistics page return to it
			$task = 'r03';
		}

		if ( js_getJoomlaVesrion_IsJ15x() == true )
			$mainframe->redirect( 'index.php?option=com_joomlastats&task=' . $task, $msg, 'message' );//third argument: 'message', 'notice', 'error'
		else
			mosRedirect( 'index2.php?option=com_joomlastats&task=' . $task, $msg );
	}
}