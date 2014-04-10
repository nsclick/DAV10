<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'statistics.common.html.php' );


/**
 *  This class hold HTML templates that are used by statistics pages
 *
 *  NOTICE: methods from class JoomlaStats_Engine will be moved here
 */
class js_JSStatisticsTpl
{
	/**
	 * this function return HTML template to page 'Page Hits'
	 * (case r06)
	 *
	 * old function name 'getPageHits();'
	 *
	 * @return string - html code
	 */
	function viewPageHitsPageTpl( $nbr_visited_pages, $sum_all_pages_impressions, $max_page_impressions, $result_arr, $summarized_info, $pagination ) {
		
		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();
		if (strlen($summarized_info['pages']) > 0) //($summarized_info['pages'] != '') not working for int!!!
			$summarized_info['pages'] = '&nbsp;'.$JSStatisticsCommonTpl->getStyleForSummarizedNumber( $summarized_info['pages'] ).'&nbsp;'.'&nbsp;';

		if (strlen($summarized_info['count']) > 0)
			$summarized_info['count'] = '&nbsp;'.$JSStatisticsCommonTpl->getStyleForSummarizedNumber( $summarized_info['count'] );
		
			
		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n"
		. '<tr>' . "\n"
		. '<th width="3%">&nbsp;</th>'										// Order Nr.
		. '<th width="5%">' . JTEXT::_( 'Count' ) . '</th>'					// Count
		. '<th width="20%">' . JTEXT::_( 'Percent' ) . '</th>'				// Percent	
		. '<th align="left" width="72%">' . JTEXT::_( 'Page' ) . '</th>'	// Page name with url
		. '</tr>' . "\n";
		
		if ( count($result_arr) > 0 )
		{
			$totalmaxpercent = round( ( ( $max_page_impressions / $sum_all_pages_impressions ) * 100 ), 1 );				

			$k		= 0;
			$line_nbr = $pagination->limitstart;

			foreach( $result_arr as $result_row )
			{
			    $retval .= '<tr class="row' . $k . '">';
			    
			    $retval .= '<td align="right"><em>' . ( $line_nbr + 1 ) . '.</em></td>';
                $retval .= '<td align="center" nowrap="nowrap">' . $result_row->page_impressions . '</td>';

                $percent = round( ( ( $result_row->page_impressions / $sum_all_pages_impressions ) * 100 ), 1 );

                $retval .= '<td align="left" nowrap="nowrap">&nbsp;'
                . $JSStatisticsCommonTpl->PercentBar($percent,$totalmaxpercent)
                . '&nbsp;&nbsp;' . number_format( $percent, 1, ',', '' ) . '%'
                . '</td>';

                $retval .= '<td align="left" nowrap="nowrap">'
                . '<a href="' . htmlentities( $result_row->page_url ) . '" target="_blank" title="'
                . htmlentities( $result_row->page_url ) . '">' . ( ($result_row->page_title!='') ? $result_row->page_title : $result_row->page_url ) . '</a>'
                . '</td>';
                
                $retval .= '</tr>';

				$k = 1 - $k;
				
                $line_nbr++;
			}
		}
		else
		{
        	$retval .= '<tr><td colspan="4" style="text-align:center">'	. JTEXT::_( 'No data' )	. '</td></tr>';
        }

        //last row of table contain total values
		$retval .='<tr>' . "\n"
		. '<th>&nbsp;</th>'
		//'<th>Total:</th>'//@todo move text to translation files and replace previous line by this
		. '<th nowrap="nowrap">'.$sum_all_pages_impressions.$summarized_info['count'].'</th>'
		. '<th>&nbsp;</th>'
		. '<th align="left">'
		. $nbr_visited_pages . $summarized_info['pages'] . '&nbsp;'
		. ( $nbr_visited_pages == 1 ? JTEXT::_( 'Page' ) : JTEXT::_( 'Pages' ) )
		. '</th>'
		. '</tr>';
		
		$retval .='</table>';

		$retval .= $pagination->getListFooter();

		return $retval;
	}

	
	/**
	 * this function return HTML template to page 'Operating Systems'
	 * (case r07)
	 *
	 * old function name 'getSystems();'
	 *
	 * @return string - html code
	 */
	function viewSystemsPageTpl( $sum_all_system_visits, $max_system_visits, $ostype_name_arr, $result_arr ) {
		
		$JSStatisticsCommonTpl = new js_JSStatisticsCommonTpl();

		$totalsystems = count($result_arr);
	
					
		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n";
		
		{// Header
			$ostype_name_str = JTEXT::sprintf('JoomlaStats group OS into %s sets', count($ostype_name_arr)) .': '. implode('; ', $ostype_name_arr);
			$retval .= ''
			. '<tr>'
			. '<th style="width: 1px;">&nbsp;</th>'
			. '<th style="width: 1px;">' . JTEXT::_( 'Count' ) . '</th>'
			. '<th style="width: 1px; text-align: center;">' . JTEXT::_( 'Percent' ) . '</th>'
			. '<th style="width: 100%">' . JTEXT::_( 'Operating Systems' ) .' ('. JTEXT::_( 'OS' ) .')'. '</th>'
			. '<th style="width: 1px; text-align: center;" title="'.$ostype_name_str.'">' . JTEXT::_( 'OS Type' ) . '</th>'
			. '</tr>' . "\n";
		}

		// Body
		if( $totalsystems > 0 ) {
			$k			= 0;
			$order_nbr	= 0;

			foreach( $result_arr as $row ) {
				$order_nbr++;
				
				$ostype_img_html = '<img src="'.$row->ostype_img_url.'" alt="'.$row->ostype_name.'" title="'.JTEXT::_( 'OS type' ).': '.$row->ostype_name.'" />';

				$retval .= '<tr class="row'.$k.'">'
			  	. '<td style="text-align: right;"><em>'.$order_nbr.'.</em></td>'
			  	. '<td style="text-align: center;">' . $row->os_visits . '</td>'
			  	. '<td>' . $JSStatisticsCommonTpl->getPercentBarWithPercentNbr( $row->os_visits, $max_system_visits, $sum_all_system_visits ) . '</td>'
				. '<td nowrap="nowrap">'.$row->os_img_html.'&nbsp;&nbsp;'
				. ( $row->os_name ? $row->os_name : '<span style="color:#FF0000;">' . JTEXT::_( 'Unknown' ) . '</span>' )
				. '</td>'
				. '<td style="text-align: center;">'.$ostype_img_html.'</td>'
				. '</tr>' . "\n";

				$k = 1 - $k;
			}
		}
		

		{// TotalLine - Footer
			$retval .= '<tr>'
			. '<th>&nbsp;</th>'
			. '<th style="text-align: center;">' . $sum_all_system_visits . '</th>'
			. '<th>&nbsp;</th>'
			. '<th>';
	
			if( $totalsystems != 0 ) {
				$retval .=  $totalsystems . ' ' . ( $totalsystems == 1 ? JTEXT::_( 'Operating System' ) : JTEXT::_( 'Operating Systems' ) );
			}else{
				$retval .= JTEXT::_( 'No known systems' );
			}
	
			$retval .= '</th>'
			. '<th>&nbsp;</th>';
		}
		
		$retval .= '</table>' . "\n";

		return $retval;
	}

			
	/**
	 * This function return HTML code to page 'Not identified visitors' 
	 * (case r11)
	 *
	 * @param array $rows
	 * @param array $pagination
	 * @return string
	 */
	function viewNotIdentifiedVisitorsPageTpl( $rows, $pagination ) {

		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n"
		. '<tr>'
		. '<th align="left" width="10%">' . JTEXT::_( 'Time' ) . '</th>'
		. '<th align="left" width="5%">' . JTEXT::_( 'Code' ) . '</th>'
		. '<th align="left" width="10%">' . JTEXT::_( 'Country/Domain' ) . '</th>'
		. '<th align="left" width="75%">' . JTEXT::_( 'UserAgent' ) . '</th>'
		. '</tr>';

		if ( $rows ) {
			$k = 0;
		    foreach( $rows as $row ) {
                $retval .= '<tr class="row' . $k . '">'
                . '<td nowrap="nowrap">' . $row->time. '</td>'
                . '<td nowrap="nowrap">&nbsp;' . $row->tld . '</td>'
                . '<td nowrap="nowrap">&nbsp;' . $row->fullname . '</td>'
                . '<td nowrap="nowrap">&nbsp;' . $row->useragent . '</td>';
                $retval .= '</tr>';
                $k = 1 - $k;
            }
        } else {
        	$retval .= '<tr><td colspan="4" style="text-align:center">' . JTEXT::_( 'No data' ) . '</td></tr>';
    	}

		$retval .= '<tr><td colspan="4">'
        . $pagination->getListFooter()
		. '</td></tr>' . "\n"
        . '</table>' . "\n";

		return $retval;
	}

	/**
	 * This function return HTML code to page 'Resolutions'
	 * (case r15)
	 *
	 * @param array $rows
	 * @param array $pagination
	 * @return string
	 * @since 2.3.x
	 */
	function viewResolutionsTpl( $rows, $pagination, $summary ) {

		$retval = '<table class="adminlist" cellspacing="0" width="100%">' . "\n"
		. '<tr>'
		. '<th width="10%">' . JTEXT::_( 'Count' ).'</th>'
		. '<th width="45%">' . JTEXT::_( 'Percent' ).'</th>'
		. '<th align="left" width="45%">' . JTEXT::_( 'Resolutions' ).'</th>'
		. '</tr>';

		if( $summary['number'] != 0 ) {
        	$totalmaxpercent = round( ( ( $summary['maximum'] / $summary['number'] ) * 100 ), 1 );
        	$k = 0;

			if( count( $rows ) > 0 ) {
            	foreach( $rows as $row ) {
            		$percent = round( ( ( $row->numbers / $summary['number'] ) * 100 ), 1 );

        			$retval .= '<tr class="row' . $k . '">'
					. '<td align="center" nowrap="nowrap">&nbsp;' . $row->numbers . '</td>'
					. '<td align="left" nowrap="nowrap">&nbsp;'
					. $this->PercentBar( $percent, $totalmaxpercent )
					. '&nbsp;&nbsp;' . number_format( $percent, 1, ',', '' ) . '%'
					. '</td>'
					. '<td align="left" nowrap="nowrap">&nbsp;'
					. ( $row->screen ? $row->screen : JTEXT::_( 'Unknown' ) )
					. '</td>'
					. '</tr>' . "\n";

					$k = 1 - $k;
				}
			}
		}

		// Summary Bar
		$retval .= '<tr><th align="center">&nbsp;' . $summary['number'] . '</th>'
		. '<th>&nbsp;</th>'
		. '<th align="left">' . $summary['screens'] . '&nbsp;';

		if( $summary['screens'] != 0 ) {
			$retval .= ( $summary['screens'] == 1 ? JTEXT::_( 'Resolution' ) : JTEXT::_( 'Resolutions' ) );
		}else{
			$retval .= JTEXT::_( 'Resolution' );
		}

		$retval .= '</th></tr>' . "\n"
		. '</table>' . "\n";
		// $retval .= $pagination->getListFooter(); // mic: prepared, but not used

		return $retval;
	}
}
