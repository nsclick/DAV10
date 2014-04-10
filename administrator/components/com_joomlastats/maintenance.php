<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

//require_once( dirname(__FILE__).'/base.classes.php' );
require_once( dirname( __FILE__ ) .DS. 'template.html.php' );
require_once( dirname( __FILE__ ) .DS. 'util.classes.php' );

/**
 *  Joomla Stats Tools class
 *
 *  This contain features from 'Tools' tab in 'Joomla Stats' Configuration panel.
 *  Basicly contain maintenance functions
 *
 *  NOTICE: This class should contain only set of static, argument less functions that are called by task/action
 */
class js_JSMaintenance extends JSBasic
{
	/** Constructor load current configuration */
	function __construct() {
		parent::__construct();
	}
	
    /**
     * This function optimize all JoomlaStats database tables
     * new from v2.3.0.170, tested - OK
     *
     * return true on success
     */
	function doOptimizeDatabase() {
		global $mainframe;

		$JSUtil = new js_JSUtil();
		$res = $JSUtil->optimizeAllJSTables();
		
		$msg = JTEXT::_( 'Database successfully optimized' );
		if ($res == false) {
			$msg = JTEXT::_( 'Database optimization failed' );
		}

		if( isJ15() ) {
			//'message', 'notice', 'error' //do not remove this line ->redirect
			if ($res == false)
				$mainframe->redirect( 'index.php?option=com_joomlastats&task=js_view_tools', $msg, 'notice' );//notice is enough - database is not broken, so red is too hard, I think
			else
				$mainframe->redirect( 'index.php?option=com_joomlastats&task=js_view_tools', $msg, 'message' );
		} else {
			mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_tools', $msg );
		}
	}
	
	/**
	 * backup routine
	 *
	 * new by mic 2006.12.16
	 * 2 parts: 1. part backup (only _page_request), 2. full backup - all tables
	 * backup existing tables, create original tables new
	 *
	 * @param bool $full
	 */
	function backupDatabase( $full ) {
		global $mainframe;
		global $msg;

		$this->_getDB();

        $i          = 0;
        $errors     = array();
        $msg        = array();
        $quer       = array();
        $dbPrefix	= $mainframe->getCfg( 'dbprefix' ); // needed often, assign once
        $buprefix   = 'bu_' . date( 'YmdHi' ) . '_';

        JoomlaStats_Engine::DoSummariseTask( true );

        if( $full == false ) {
            // partial backup
            $tables = array( $dbPrefix . 'jstats_page_request' );
            // step 1: backup tables
            foreach( $tables as $table ) {
                $butable = str_replace( $dbPrefix, $buprefix, $table );

                $query = 'RENAME TABLE `' . $table . '` TO `' . $butable . '`';

                if( $this->_debug() ) {
                	echo 'DEBUG Info from JS<br />';
                    echo 'query: '. $query .'<br />';
                    echo 'table: '. $table .'<br />';
                    echo 'butable: '. $butable .'<br />';
                }

                $this->db->setQuery( $query );
                if( !$this->db->query() ) {
                	$errors[$this->db->getQuery()] = $this->db->getErrorMsg();
                }else{
                    $msg[] = $table . ' ' . JTEXT::_( 'tables of JoomlaStats sucessfully edited' );
                    $i++;
                }
            }

            //step 2: create them new (again: add here what you want)
            //@at @todo Duplicated code it is a little dangerous. This functionality should be realized by separate function.
            $query = 'CREATE TABLE IF NOT EXISTS `#__jstats_page_request` ('
            . ' `page_id` mediumint(9) NOT NULL default \'0\','
            . ' `hour` tinyint(4) NOT NULL default \'0\','
            . ' `day` tinyint(4) NOT NULL default \'0\','
            . ' `month` tinyint(4) NOT NULL default \'0\','
            . ' `year` smallint(6) NOT NULL default \'0\','
            . ' `ip_id` mediumint(9) default NULL,'
            . ' KEY `page_id` (`page_id`),'
            . ' KEY `monthyear` (`month`,`year`),'
            . ' KEY `index_ip` (`ip_id`)'
            . ' ) TYPE=MyISAM';

            $this->db->setQuery( $query );
            if( !$this->db->query() ) {
                $errors[] = array( $this->db->getErrorMsg(), $query );
            }else{
                $msg[] = '1 ' . JTEXT::_( 'tables of JoomlaStats sucessfully edited' );
                $i++;
            }
        }else{
            // full backup
            // step 1: call for tables and backup (rename)
            $query = 'SHOW TABLES'
            . ' FROM `' . $mainframe->getCfg( 'db' ) . '`'
            . ' LIKE \'' . $dbPrefix . '%jstats%\''
            ;
            $this->db->setQuery( $query );

            if( $tables = $this->db->loadResultArray() ) {
                foreach( $tables as $table) {
                    if( strpos( $table, $dbPrefix ) === 0 ) {
                        $butable = str_replace( $dbPrefix, $buprefix, $table );

                        $query = 'RENAME TABLE `' . $table . '` TO `' . $butable . '`';
                        $this->db->setQuery( $query );
                        $this->db->query();

                        if( $this->db->getErrorNum()) {
                            $errors[$this->db->getQuery()] = $this->db->getErrorMsg();
                        }else{
                            $msg[] = $table . ' ' . JTEXT::_( 'tables of JoomlaStats sucessfully edited' );
                            $i++;
                        }
                    }
                }
            }

            // step 3: read in new tables and populate (all done inside the included file)
            //@at @todo this action should be done by separate function. Function should be localized (or localization should be removed from database)

            // mic: removed this file, because of the 'on the fly' translating by JTEXT
			// include( dirname( __FILE__ ) .DS. 'install' .DS. 'en.db.joomlastats.inc.php' );
			$JSUtil = new js_JSUtil();
			include( dirname( __FILE__ ) .DS. 'install' .DS. 'all.tables.joomlastats.inc.php' );
			$JSUtil->populateSQL( $quer, true );
			include( dirname( __FILE__ ) .DS. 'install' .DS. 'all.data.joomlastats.inc.php' );
			$JSUtil->populateSQL( $quer, true );
        }

		$JSTemplate = new js_JSTemplate();

		echo '<div style="text-align: left;"><!-- needed by j1.0.15 -->';
		echo $JSTemplate->generateHeaderIconAndTitleForJ10( 'JoomlaStats', 'Maintenance' ); ?>

        <div class="adminform" style="width:100%">
        	<div style="margin-left:100px">
        		<ul>
                <?php
        		if( !$errors ){
        			echo '<li>' . $i . ' ' . JTEXT::_( 'tables of JoomlaStats sucessfully edited' ) . '</li>' ;
        			if( !empty( $msg ) ) {
        			    foreach( $msg as $message ) {
                            echo '<li>' . $message . '</li>';
                        }
                    }
        		}else{
        			echo '<li><span style="color:red">'
        			. JTEXT::_( 'JoomlaStats database tables sucessfully edited' )
        			. '</span></li>';

        			foreach( $errors as $err ) {
        				echo '<li>' . $err . '</li>';
        			}
        		} ?>
        		</ul>
        	</div>
        </div>
        <?php

		echo $JSTemplate->generateAdminForm();
		echo '</div><!-- needed by j1.0.15 -->';
    }
}

