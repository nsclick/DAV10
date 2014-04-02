<?php
/**
 * @package JoomlaStats
 * @copyright Copyright (C) 2004-2009 JoomlaStats Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */


if( !defined( '_VALID_MOS' )  && !defined( '_JEXEC' ) ) {
	die( 'JS: No Direct Access' );
}

// require_once( dirname( __FILE__ ) .DS. 'base.classes.php' ); // mic: WHY CALLING HERE, this is already done!

/**
 * Object of this class generate HTML code
 *
 * Here should be HTML code that is common for many JS pages
 *
 * All methods should be static functions!
 */
class js_JSTemplate
{

	/**
	 * It was tested in FF 2.0 and IE 7.0 - it is working!!!
	 * $MsgArr - array with messages to display
	 * $NoMsgInfoMsg - text displayed when there is no messages in $MsgArr
	 * FrameDataConst - Do not change colors - they are strictly connected with message types!
	 * 'b_clr' - border color; 't_b_clr' - title background color; 'c_b_clr' - contnt background color;
	 *
	 * @param unknown_type $type
	 * @param array $MsgArr
	 * @param string $NoMsgInfoMsg
	 * @param string $extraHtmlContent
	 * @return string
	 */
	function generateMsgColorInfoFrame( $type, $MsgArr, $NoMsgInfoMsg, $extraHtmlContent='' ) {
		$FDConstArray =	array(
			'error' 	=> array(
				'title'		=> JTEXT::_( 'Errors' ),
				'b_clr'		=> '#FF0000',
				't_b_clr'	=> '#FF9999',
				'c_b_clr'	=> '#FFEEEE' ),
			'warning' 	=> array(
				'title'		=> JTEXT::_( 'Warnings' ),
				'b_clr'		=> '#AF0A37',
				't_b_clr'	=> '#F7A5A5',
				'c_b_clr'	=> '#FDF3F3' ),
			'recommend'	=> array(
				'title'		=> JTEXT::_( 'Recommendations' ),
				'b_clr'		=> '#F58E0E',
				't_b_clr'	=> '#FFE9C4',
				'c_b_clr'	=> '#FFF4E0' ),
			'info'		=> array(
				'title'		=> JTEXT::_( 'Information' ),
				'b_clr'		=> '#0000FF',
				't_b_clr'	=> '#A0A0FF',
				'c_b_clr'	=> '#F0F0FF' )
			//'db_backup'	=> green
		);

		if ( !array_key_exists( $type, $FDConstArray ) ) {
			$HtmlCode  = 'js_template_err01 - function js_JSTemplate::generateMsgColorInfoFrame in file template.html.php. There is no $type\''.$type.'\'.';
    		return $HtmlCode;
		}

		$FDConst = $FDConstArray[$type];

		$HtmlCode = '<div class="status">' . "\n"
		. '<div style="margin-top: 15px"></div>' . "\n"
		. '<div style="margin-left: 15px">
		<span style="border-width: 2px 0px 0px 0px; border-style: solid; border-color: '.$FDConst['b_clr'].'; padding: 0px 10px 0px 10px; background-color: '.$FDConst['t_b_clr'].'; font-weight: bold; font-size: larger;">'.$FDConst['title'].'</span>'
		. '</div>' . "\n"
		. '<div style="text-align: justify; border-width: 2px; border-style: solid; border-color: '.$FDConst['b_clr'].'; padding: 4px; background-color: '.$FDConst['c_b_clr'].';">';

		if( ( count( $MsgArr ) == 0 ) && ( $extraHtmlContent == '' ) ) {
			// text displayed when there is no messages
			$HtmlCode .= $NoMsgInfoMsg;
		}else{
			$isFirst = true;
			foreach( $MsgArr as $msg ) {
				if( $isFirst == true ) {
					$isFirst = false;
				}else{
					// space from previous message
					$HtmlCode .= '<br/><br/>';
				}

				$HtmlCode .= '<div style="padding-bottom: 3px;">'
				. '<span style="border-bottom: 3px double ' . $FDConst['b_clr'] . ';">' . $msg['name'] . '</span>'
				. '</div>' . "\n"
				. '<div style="padding-left: 1em;">' . $msg['description'] . '</div>' . "\n";
			}
		}

	 	if ( ( count( $MsgArr ) > 0 ) && ( $extraHtmlContent != '' ) ) {
	 		// generate space between info msgs and extra content
 			$HtmlCode .= '<div style="clear:both; margin-top: 15px"></div>' . "\n";
	 	}

	 	if ( $extraHtmlContent != '' ) {
	 		// show extra content
			$HtmlCode .= $extraHtmlContent;
	 	}

		$HtmlCode .= '</div>' . "\n" . '</div>' . "\n"; //end of content frame

		return $HtmlCode;
	}

	/**
	 * This function generate 'header icon and title' for j1.0.15
	 * In j1.5.x 'header icon and title' are on toolbar
	 * Header title is located at right site of image
	 * E.g. $pageTitle = 'Banner', 	$pageSubTitle='New'
	 * E.g. $pageTitle = 'JoomlaStats',$pageSubTitle='Configuration'
	 *
	 * @param string $pageTitle
	 * @param string $pageSubTitle
	 * @param array $FilterNextToTitleArr
	 * @since 2.3.x only css
	 * @return string
	 */
	function generateHeaderIconAndTitleForJ10( $pageTitle, $pageSubTitle, $FilterNextToTitleArr = array() ) {

		if( js_getJoomlaVesrion_IsJ15x() == true ) {
			return '';
		}

		$urlToIcon = JURI::base() . '/components/com_joomlastats/images/icon-48-js_js-logo.png';

		$IconAndTitle = "\n" . '<div class="adminheading" style="background-color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; float:left; margin:0px; padding:0px; border:0px; width:100%; border-collapse:collapse;">' . "\n"
		. '<div class="headerImage" style="background: url(' . $urlToIcon . ') no-repeat left; float:left; height:50px; padding-left:50px; border-bottom:5px solid #FFFFFF;"></div>' . "\n"
		. '<div class="headerTitle" style="float:left; text-align:left; margin:15px 0 0 10px; color:#C64934; font-size:18px; font-weight:bold;">'
		. $pageTitle;

		if ( $pageSubTitle != '' ) {
			$IconAndTitle .= ':&nbsp;<small>'.$pageSubTitle.'</small>';//in 1.0.15 there is no '[' mic: WHO cares about that?!
		}
		$IconAndTitle .= '</div>' . "\n";

		if( count( $FilterNextToTitleArr ) > 0 ) {
			$IconAndTitle .= '<div class="filter" style="float:right; text-align:right; margin:15px 5px 0 0; font-size:0.9em">';
			foreach( $FilterNextToTitleArr as $FilterNextToTitle ) {
				 $IconAndTitle .= $FilterNextToTitle;
			}
			$IconAndTitle .= '</div>' . "\n";
		}
		$IconAndTitle .= '</div>' . "\n"
		. '<div style="clear:both;"></div>' . "\n";

		return $IconAndTitle;
	}

	/**
	 * Use this function to make tool bar works
	 * if $task=='' all icons on tool bar must set task!
	 *
	 * @param string $task
	 * @return string
	 */
	function generateAdminForm( $task = '' ) {
		$form  = $this->generateBeginingOfAdminForm( $task ) . $this->generateEndOfAdminForm();

		return $form;
	}

	/**
	 * if $task=='' all icons on tool bar must set task!
	 *
	 * @param unknown_type $task
	 * @return unknown
	 */
	function generateBeginingOfAdminForm( $task = '' ) {
		$form  = '<form name="adminForm" id="adminForm" method="post" action="index' . ( isJ15() ? '' : '2' ) . '.php" style="display: inline; margin: 0px; padding: 0px;" onsubmit="return true;">' . "\n"
		. '<input type="hidden" name="option" value="com_joomlastats" />' . "\n"
		. '<input type="hidden" name="task" value="' . $task . '" />' . "\n";
		//$form .= '<input type="hidden" name="boxchecked" value="0" />' . "\n"; // @at Do we use this?
		// >> mic: YES WE NEED THIS - but only IF - we have a form AND have some checkboxes to click on
		return $form;
	}

	/**
	 * writes the final </form> tag
	 *
	 * @return string
	 */
	function generateEndOfAdminForm() {
		$form = '</form>' . "\n";

		return $form;
	}

	/**
	 * return formated version number for dev and release version
	 *
	 * $JSVersion taken from $JSConf->JSVersion
	 */
	function getJSVersionStr( $JSVersion ) {
		$str = '<!-- JoomlaStats build version: '.$JSVersion.' -->';
		if (strpos($JSVersion, ' ') === false) {
			//for release, cut the build number (last digits) from version number
			$pos = strrpos($JSVersion, '.');
			if ($pos === false) {
				//somethings goes wrong, echo all
				$str .= $JSVersion;
			} else {
				$str .= substr($JSVersion, 0, $pos);
			}
		} else {
			$str .= $JSVersion;
		}
		return $str;
	}


	/**
	 * loads correct tooltip library
	 *
	 */
	function jsLoadToolTip() {
		if( isJ15() )
			JHTML::_('behavior.tooltip');
		else
			mosCommonHTML::loadOverlib();
	}
	
	/**
	 * helper function for displaying tooltip
	 *
	 * @since 2.3.x
	 * @param string $tip
	 */
	function jsToolTip( $tip ) {
		if( isJ15() )
			return JHTML::tooltip( $tip );
		else
			return mosToolTip( $tip );
	}
}