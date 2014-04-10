<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}


require_once( dirname( __FILE__ ) .DIRECTORY_SEPARATOR. 'tld.html.php' );


/**
 *  This class contain set of static functions that allow perform actions connected with TLD.
 *
 *  NOTICE: This class should contain only set of static, argument less functions that are called by task/action
 */
class js_JSTld extends JSBasic
{
	/** Constructor load current configuration */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Get content of TLD tab
	 *
	 * @access static, public
	 * @return HTML code
	 */
	function getTldTab() {
		
		$ip_tld_info = JRequest::getVar( 'ip_tld_info', '' );
		
		$JSTldTpl = new js_JSTldTpl();
		$html = $JSTldTpl->getTldTabTpl($ip_tld_info);
		return $html;
	}

	
	/**
	 * This functon return TLD for particular IP if exist in JS table
	 * If such IP not exist in JS table, this function return notification about this.
	 *
	 * @access static, public
	 */
	function doGetTldFromTable() {
		global $mainframe;
		
		$ip_tld_info = 'AAAAAAAAA';
		
		$url = '&ip_tld_info='.$ip_tld_info;
		if( isJ15() )
			$mainframe->redirect( 'index.php?option=com_joomlastats&task=js_view_tools'.$url);//notice is enough - database is not broken, so red is too hard, I think
		else
			mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_tools'.$url);
	}
	
	
	/**
	 * This functon return TLD for particular IP. It checks TLD in internet RIPE servers
	 * If TLD is not returned, this function return notification about this.
	 *
	 * @access static, public
	 */
	function doGetTldFromRipe() {
		
		global $mainframe;
		
		$ip_tld_info = '';
		
		$url = '&ip_tld_info='.$ip_tld_info;
		if( isJ15() )
			$mainframe->redirect( 'index.php?option=com_joomlastats&task=js_view_tools'.$url);//notice is enough - database is not broken, so red is too hard, I think
		else
			mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_tools'.$url);
	}
	
	
	/**
	 * performs a DNS lookup
	 *
	 * @todo function not finished - it has been only moved from admin.joomlastats.html.php file
	 * @since 2.3.x mic: 	- db.query added GROUP BY, because we need only unique ip.addresses
	 * 						- new field 'whois'
	 * 						- makes no sence to lookup the same address several times and getting a time-out!
	 */
	function doJSTldLookUp() {
		global $mainframe;

		$this->_getDB();

		// get the list of all unresolved nslookup ipaddresses
		$query = 'SELECT ip, id'
		. ' FROM #__jstats_ipaddresses'
		. ' WHERE ip = nslookup'
		. ' AND tld = \'\''
		. ' GROUP BY ip'
		;
		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();
		if( $this->db->getErrorNum()) {
			echo $this->db->stderr();
			return false;
		}

		if( $this->_debug() ) {
			ob_flush();
			echo '<br />DEBUG Info JoomlaStats - admin.joomlastats.php [ processTldLookUp ]<br />'
			. 'query:<br />' . $query . '<br />'
			. 'found records:<hr /';
			echo print_r( $rows )
			. '<br />----------------<br />';
		}

		$n = count( $rows );
		if( $n > 0 ) {
			// @todo: mic: why is this limited to 25?
			/*
			if( $n > 25 ) {
				$n = 25;
			}
			*/
			?>

			<script type="text/javascript">
				/* <![CDATA[ */
				redirect = true;

				function urlredirect( redirect ) {
					if( redirect == true ){
						document.location.href='<?php $this->_index(); ?>?option=com_joomlastats&task=js_do_tldlookup';
						//alert( 'redirect [' + redirect + ']' ); // for debug only
					}
				};
				/* ]]> */
			</script>

			<div style="color:#FF0000;"><?php echo JTEXT::_( 'Processing to go' ) . ': ' . count( $rows ); ?></div>
			<?php
			/*
			private addresses:
			10.0.0.0 - 10.255.255.255
			127.* (ipv4) = 0:0:0:0:0:0:0:1 (ipv6)
			169.254.*
			172.16.0.0 - 172.31.255.255
			192.168.*
			*/
			// new mic 20081010
			$local = '/^10|^127|^169\.254|^172\.16|^172\.17|^172\.18|^172\.19|^172\.20|^172\.21|^172\.22|^172\.23|^172\.24|^172\.25|^172\.26|^172\.27|^172\.28|^172\.29|^172\.30|^172\.31|^192|0:0:0:0:0:0:0:1/';

			// mic: check if data in table jstats_iptocountry are existing, therefore we have only 1 query instead of x useless!
			$query = 'SELECT count(*)'
			. ' FROM #__jstats_iptocountry'
			;
			$this->db->setQuery( $query );
			$isCountry = $this->db->loadResult();

			$ii = 0; // new mic: counter for lookups

			for( $i = 0; $i < $n; ++$i ) {
				$row = &$rows[$i];

				if( $this->_debug() ) {
					echo 'DEBUG (1) ip [' . $row->ip . ']<br />';
				}

				// Check for safe mode
				if( !ini_get('safe_mode') ){
					$curTime = ini_get('max_execution_time'); // get original value for restoring
					set_time_limit( 30 );
				}else{
					echo JTEXT::_( 'Attention: safe_mode enabled - script may stop if maximum time is reached!' );
				}

				// mic: no need for a lookup for local addresses ....
				if( preg_match( $local, $row->ip ) ) {
					$nslp	= 'localhost';
					$tld	= 'localhost';
				}else{
					if( $this->_debug() ) {
						echo 'DEBUG : doing address check by gethostbyaddr<br />'
						. 'ip [' . $row->ip . ']<br />';
					}

					++$ii;
					$nslp	= gethostbyaddr( $row->ip ); // mic 20081011: removed strtolower because they are only digits!
					$arr	= explode( '.', $nslp );
					$tld	= end( $arr ); // get last element

					if( !ereg( '([a-zA-Z])', $tld ) ) {
						$tld = 'unknown';
					}

					// new mic 20081011: check if records in table
					if( $isCountry ) {
						// following code block inserted by slako
						if( $tld === '' || $tld === 'eu' || strlen( $tld ) > 2 ) {
							$query = 'SELECT country_code2'
							. ' FROM #__jstats_iptocountry'
							. ' WHERE INET_ATON(\'' . $row->ip . '\') >= ip_from'
							. ' AND INET_ATON(\'' . $row->ip . '\') <= ip_to'
							;
							$this->db->setQuery( $query );
							$country_code2 = $this->db->loadResult();

							if( $country_code2 ) {
								$tld = strtolower( $country_code2 );
							}
						}
					}
				}

				if( $nslp != $row->ip ) {
					// mic new 20081012: update only if lookup was successful
					$query = 'UPDATE #__jstats_ipaddresses'
					. ' SET tld = ' . $this->db->Quote( $tld ) . ', nslookup = ' . $this->db->Quote( $nslp )
					// . ' WHERE id = '. $row->id
					//changed by mic 20081011 (see comment at beginning of function)
					. ' WHERE ip = ' . $this->db->Quote( $row->ip )
					;
					$this->db->setQuery( $query );
					if( !$this->db->query() ) {
						echo '<script type="text/javascript">alert(\'' . $this->db->getErrorMsg() . '\');</script>' . "\n";
						exit();
					}
					echo '<script type="text/javascript">redirect=false;</script>' . "\n";
				}else{
					// new by mic to avoid another query, write back flag for whois
					$query = 'UPDATE #__jstats_ipaddresses'
					. ' SET tld = \'unknown\''
					. ' WHERE ip = \'' . $row->ip . '\''
					;
					$this->db->setQuery( $query );
					$this->db->query();

					echo '<script type="text/javascript">redirect=false;</script>' . "\n";
				} ?>

				<div class="jsShowProgress">
					<?php echo $row->ip; ?>&nbsp;&nbsp;-->&nbsp;&nbsp;
					<?php echo JTEXT::_( $tld ); ?>
					&nbsp;&nbsp;-->&nbsp;&nbsp;
					<?php echo $nslp; ?>
				</div>
				<div style="color:green;"><?php echo JTEXT::_( 'Processing to go' ) . ': ' . ( $n - $ii ); ?></div>
				<script type="text/javascript">
					/* <![CDATA[ */
					<?php
					if( $nslp != $row->ip ) { ?>
						redirect = false;
						<?php
					} ?>
					urlredirect( redirect );
					/* ]]> */
				</script>
				<?php
			}

			// restore original time value
			set_time_limit( $curTime );

			$query = 'UPDATE #__jstats_ipaddresses'
			. ' SET tld = \'\''
			. ' WHERE tld = \'unknown\''
			;
			$this->db->setQuery( $query );
			if( !$this->db->query() ) {
				echo '<script type="text/javascript">alert(\'' . $database->getErrorMsg() . '\');</script>' . "\n";
				exit();
			} ?>

			<script type="text/javascript">
				/* <![CDATA[ */
				redirect = false;
				/* ]]> */
			</script>
			<div style="margin-top:15px">
				<?php echo JTEXT::sprintf( 'TLD-Lookup finished - processed %s lookups', $ii ); ?>
			</div>
			<div class="jsLink" style="margin-top:15px">
				<input type="submit" class="button" value="  <?php echo JTEXT::_( 'Click to proceed' ); ?>  " onclick="javascript:document.location.href='<?php $this->_index(); ?>?option=com_joomlastats&amp;task=js_view_tools';" />
			</div>

			<script type="text/javascript">
				/* <![CDATA[ */
				setTimeout( 'urlredirect(redirect)', 500 );
				/* ]]> */
			</script>
			<?php
		}else{
			$msg = JTEXT::_( 'TLD-Lookup finished - no addresses to process' );

			if( isJ15() ) {
				$mainframe->redirect( 'index.php?option=com_joomlastats&task=js_view_tools', $msg );
			}else{
				mosRedirect( 'index2.php?option=com_joomlastats&task=js_view_tools', $msg );
			}
		}
	}
	
}
