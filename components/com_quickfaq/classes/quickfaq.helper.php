<?php
/**
 * @version 1.0 $Id: quickfaq.helper.php 195 2009-01-30 06:33:12Z schlu $
 * @package Joomla
 * @subpackage QuickFAQ
 * @copyright (C) 2008 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENCE.php
 * QuickFAQ is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * QuickFAQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with QuickFAQ; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class quickfaq_html
{
	/**
	 * Creates the print button
	 *
	 * @param string $print_link
	 * @param array $params
	 * @since 1.0
	 */
	function printbutton( $print_link, &$params )
	{
		if ($params->get( 'show_print_icon' )) {

			JHTML::_('behavior.tooltip');

			$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

			// checks template image directory for image, if non found default are loaded
			if ( $params->get( 'show_icons' ) ) {
				$image = JHTML::_('image.site', 'printButton.png', 'images/M_images/', NULL, NULL, JText::_( 'PRINT' ));
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'PRINT' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
			}

			if (JRequest::getInt('pop')) {
				//button in popup
				$output = '<a href="#" onclick="window.print();return false;">'.$image.'</a>';
			} else {
				//button in view
				$overlib = JText::_( 'PRINT TIP' );
				$text = JText::_( 'PRINT' );

				$output	= '<a href="'. JRoute::_($print_link) .'" class="editlinktip hasTip" onclick="window.open(this.href,\'win2\',\''.$status.'\'); return false;" title="'.$text.'::'.$overlib.'">'.$image.'</a>';
			}

			return $output;
		}
		return;
	}

	/**
	 * Creates the email button
	 *
	 * @param string $print_link
	 * @param array $params
	 * @since 1.0
	 */
	function mailbutton($view, &$params, $slug = null, $itemslug = null )
	{
		if ($params->get('show_email_icon')) {

			JHTML::_('behavior.tooltip');
			$uri    =& JURI::getInstance();
			$base  	= $uri->toString( array('scheme', 'host', 'port'));
			
			//TODO: clean this static stuff (Probs when determining the url directly with subdomains)
			if($view == 'category') {
				$link 	= $base.JRoute::_( 'index.php?view='.$view.'&cid='.$slug, false );
			} elseif($view == 'items') {
				$link 	= $base.JRoute::_( 'index.php?view='.$view.'&cid='.$slug.'&id='.$itemslug, false );
			} elseif($view == 'tags') {
				$link 	= $base.JRoute::_( 'index.php?view='.$view.'&id='.$slug, false );
			} else {
				$link 	= $base.JRoute::_( 'index.php?view='.$view, false );
			}
			$url 	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode( $link );
			$status = 'width=400,height=300,menubar=yes,resizable=yes';

			if ($params->get('show_icons')) 	{
				$image = JHTML::_('image.site', 'emailButton.png', 'images/M_images/', NULL, NULL, JText::_( 'EMAIL' ));
			} else {
				$image = '&nbsp;'.JText::_( 'EMAIL' );
			}

			$overlib = JText::_( 'EMAIL TIP' );
			$text = JText::_( 'EMAIL' );

			$output	= '<a href="'. JRoute::_($url) .'" class="editlinktip hasTip" onclick="window.open(this.href,\'win2\',\''.$status.'\'); return false;" title="'.$text.'::'.$overlib.'">'.$image.'</a>';

			return $output;
		}
		return;
	}

	/**
	 * Creates the pdf button
	 *
	 * @param int $id
	 * @param array $params
	 * @since 1.0
	 */
	function pdfbutton( $item, &$params)
	{
		if ($params->get('show_pdf_icon')) {

			JHTML::_('behavior.tooltip');

			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', 'pdf_button.png', 'images/M_images/', NULL, NULL, JText::_( 'CREATE PDF' ));
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'CREATE PDF' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
			}
			$overlib = JText::_( 'CREATE PDF TIP' );
			$text = JText::_( 'CREATE PDF' );

			$link 	= 'index.php?view=items&cid='.$item->categoryslug.'&id='.$item->slug.'&format=pdf';
			$output	= '<a href="'.JRoute::_($link).'" class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$image.'</a>';

			return $output;
		}
		return;
	}

	/**
	 * Creates the edit button
	 *
	 * @param int $id
	 * @param array $params
	 * @since 1.0
	 */
	function editbutton( $item, &$params)
	{
		$user	= & JFactory::getUser();

		if ($user->authorize('com_quickfaq', 'edit') || ($user->authorize('com_content', 'edit', 'content', 'own') && $item->created_by == $user->get('id')) ) {

			JHTML::_('behavior.tooltip');

			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', 'edit.png', 'images/M_images/', NULL, NULL, JText::_( 'EDIT' ));
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'EDIT' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
			}
			$overlib = JText::_( 'EDIT TIP' );
			$text = JText::_( 'EDIT' );

			$link 	= 'index.php?view=items&cid='.$item->categoryslug.'&id='.$item->slug.'&task=edit';
			$output	= '<a href="'.JRoute::_($link).'" class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$image.'</a>';

			return $output;
		}
		return;
	}

	/**
	 * Creates the add button
	 *
	 * @param array $params
	 * @since 1.0
	 */
	function addbutton(&$params)
	{
		$user	= & JFactory::getUser();

		if ($user->authorize('com_quickfaq', 'add')) {

			JHTML::_('behavior.tooltip');

			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', 'add.png', 'components/com_quickfaq/assets/images/', NULL, NULL, JText::_( 'ADD' ));
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'ADD' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
			}
			$overlib = JText::_( 'ADD TIP' );
			$text = JText::_( 'ADD' );

			$link 	= 'index.php?view=items&task=add';
			$output	= '<a href="'.JRoute::_($link).'" class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$image.'</a>';

			return $output;
		}
		return;
	}

	/**
	 * Creates the stateicon
	 *
	 * @param int $state
	 * @param array $params
	 * @since 1.0
	 */
	function stateicon( $state, &$params)
	{
		$user		= & JFactory::getUser();

		if ($user->authorize('com_quickfaq', 'state') && $params->get('show_state_icon')) {

			JHTML::_('behavior.tooltip');

			if ( $state == 1 ) {
				$img = 'tick.png';
				$alt = JText::_( 'PUBLISHED' );
				$state = 1;
			} else if ( $state == 0 ) {
				$img = 'publish_x.png';
				$alt = JText::_( 'UNPUBLISHED' );
				$state = 0;
			} else if ( $state == -1 ) {
				$img = 'disabled.png';
				$alt = JText::_( 'ARCHIVED' );
				$state = -1;
			} else if ( $state == -2 ) {
				$img = 'publish_r.png';
				$alt = JText::_( 'PENDING' );
				$state = -2;
			} else if ( $state == -3 ) {
				$img = 'publish_y.png';
				$alt = JText::_( 'OPEN QUESTION' );
				$state = -3;
			} else if ( $state == -4 ) {
				$img = 'publish_g.png';
				$alt = JText::_( 'IN PROGRESS' );
				$state = -4;
			}

			$text = JText::_( 'STATE' );
			
			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', $img, 'components/com_quickfaq/assets/images/', NULL, NULL, $alt, 'class="editlinktip hasTip" title="'.$text.'::'.$alt.'"' );
			} else {
				$image = '&nbsp;'. $alt;
			}
			return $image;
		}
		return;
	}

	/**
	 * Creates the ratingbar
	 *
	 * @param array $item
	 * @since 1.0
	 */
	function ratingbar($item)
	{
		JHTML::_('behavior.tooltip');

		//sql calculation doesn't work with negative values and thus only minus votes will not be taken into account
		if ($item->votes == 0 && $item->minus != 0) {
			return JText::sprintf('RATED NEGATIVE', $item->minus);
		} elseif ($item->votes == 0 && $item->minus == 0) {
			return JText::_('NOT YET RATED');
		}

		//we do the rounding here and not in the query to get better ordering results
		$rating = round($item->votes);

		$output = '<span class="qf_ratingbarcontainer editlinktip hasTip" title="'.JText::_('RATING').'::'.JText::_('USEFULLNESS').': '.$rating.'%&lt;br /&gt;'.JText::_('GOOD').': '.$item->plus.'&lt;br /&gt;'.JText::_('BAD').': '.$item->minus.'">';
		$output .= '<span class="qf_ratingbar" style="width:'.$rating.'%;">&nbsp;</span></span>';

		return $output;
	}
	
	
	/**
	 * Copy of com_content filtering. QuickFAQ doesn't need own filtering rules for content,
	 * @return 
	 * @param object $row
	 */
	function saveContentPrep( &$row )
	{
		// Get submitted text from the request variables
		$text = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW );

		// Clean text for xhtml transitional compliance
		$text		= str_replace( '<br>', '<br />', $text );

		// Search for the {readmore} tag and split the text up accordingly.
		$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$tagPos	= preg_match($pattern, $text);

		if ( $tagPos == 0 )
		{
			$row->introtext	= $text;
		} else
		{
			list($row->introtext, $row->fulltext) = preg_split($pattern, $text, 2);
		}

		// Filter settings
		jimport( 'joomla.application.component.helper' );
		$config	= JComponentHelper::getParams( 'com_content' );
		$user	= &JFactory::getUser();
		$gid	= $user->get( 'gid' );

		$filterGroups	=  $config->get( 'filter_groups' );

		if (is_array($filterGroups) && in_array( $gid, $filterGroups ))
		{
			$filterType		= $config->get( 'filter_type' );
			$filterTags		= preg_split( '#[,\s]+#', trim( $config->get( 'filter_tags' ) ) );
			$filterAttrs	= preg_split( '#[,\s]+#', trim( $config->get( 'filter_attritbutes' ) ) );
			switch ($filterType)
			{
				case 'NH':
					$filter	= new JFilterInput();
					break;
				case 'WL':
					$filter	= new JFilterInput( $filterTags, $filterAttrs, 0, 0, 0);  // turn off xss auto clean
					break;
				case 'BL':
				default:
					$filter	= new JFilterInput( $filterTags, $filterAttrs, 1, 1 );
					break;
			}
			$row->introtext	= $filter->clean( $row->introtext );
			$row->fulltext	= $filter->clean( $row->fulltext );
		} elseif(empty($filterGroups) && $gid != '25') { // no default filtering for super admin (gid=25)
			$filter = new JFilterInput( array(), array(), 1, 1 );
			$row->introtext	= $filter->clean( $row->introtext );
			$row->fulltext	= $filter->clean( $row->fulltext );
		}
		
		return true;
	}

	/**
	 * Creates the voteicons
	 *
	 * @param array $params
	 * @since 1.0
	 */
	function voteicons($item, &$params)
	{
		JHTML::_('behavior.tooltip');
		$document 	= & JFactory::getDocument();
		
		if ( $params->get('show_icons') ) {
			$voteup = JHTML::_('image.site', 'thumb_up.png', 'components/com_quickfaq/assets/images/', NULL, NULL, JText::_( 'GOOD' ) );
			$votedown = JHTML::_('image.site', 'thumb_down.png', 'components/com_quickfaq/assets/images/', NULL, NULL, JText::_( 'BAD' ) );
		} else {
			$voteup = JText::_( 'GOOD' ). '&nbsp;';
			$votedown = '&nbsp;'.JText::_( 'BAD' );
		}
		
		$js = 'function voting(vote) {
				if (vote == 1) {
					location = "'.JRoute::_("index.php?task=vote&vote=1&cid=".$item->categoryslug."&id=".$item->slug).'";
				} else {
					location = "'.JRoute::_("index.php?task=vote&vote=0&cid=".$item->categoryslug."&id=".$item->slug).'";
				}}';
		$document->addScriptDeclaration($js);

		$output = '<a href="#" class="editlinktip hasTip" title="'.JText::_( 'VOTE UP' ).'::'.JText::_( 'VOTE UP TIP' ).'" rel="nofollow" onclick="voting(1)">'.$voteup.'</a>';
		$output .= ' - ';
		$output .= '<a href="#" class="editlinktip hasTip" title="'.JText::_( 'VOTE DOWN' ).'::'.JText::_( 'VOTE DOWN TIP' ).'" rel="nofollow" onclick="voting(0)">'.$votedown.'</a>';

		return $output;
	}

	/**
	 * Creates the favourite icons
	 *
	 * @param array $params
	 * @since 1.0
	 */
	function favoure($item, &$params, $favoured)
	{
		$user	= & JFactory::getUser();

		JHTML::_('behavior.tooltip');

		if ($user->id && $favoured) {
			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', 'heart_delete.png', 'components/com_quickfaq/assets/images/', NULL, NULL, JText::_( 'REMOVE FAVOURITE' ));
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'REMOVE FAVOURITE' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
			}
			$overlib = JText::_( 'REMOVE FAVOURITE TIP' );
			$text = JText::_( 'REMOVE FAVOURITE' );

			$link 	= 'index.php?task=removefavourite&cid='.$item->categoryslug.'&id='.$item->slug;
			$output	= '<a href="'.JRoute::_($link).'" class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$image.'</a>';
		} elseif($user->id) {
			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', 'heart_add.png', 'components/com_quickfaq/assets/images/', NULL, NULL, JText::_( 'FAVOURE' ));
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'FAVOURE' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
			}
			$overlib = JText::_( 'FAVOURE TIP' );
			$text = JText::_( 'FAVOURE' );

			$link 	= 'index.php?task=addfavourite&cid='.$item->categoryslug.'&id='.$item->slug;
			$output	= '<a href="'.JRoute::_($link).'" class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$image.'</a>';
		} else {

			$overlib = JText::_( 'FAVOURE LOGIN TIP' );
			$text = JText::_( 'FAVOURE' );

			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', 'heart_login.png', 'components/com_quickfaq/assets/images/', NULL, NULL, $text, 'class="editlinktip hasTip" title="'.$text.'::'.$overlib.'"' );
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. '<span class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$text.'</span>';
			}

			$output	= $image;
		}

		return $output;
	}

	/**
	 * Creates the favourites button
	 *
	 * @param array $params
	 * @since 1.0
	 */
	function favouritesbutton(&$params)
	{
		if ($params->get('show_favourites')) {
			JHTML::_('behavior.tooltip');

			if ( $params->get('show_icons') ) {
				$image = JHTML::_('image.site', 'heart.png', 'components/com_quickfaq/assets/images/', NULL, NULL, JText::_( 'FAVOURITES' ));
			} else {
				$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'FAVOURITES' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
			}
			$overlib = JText::_( 'FAVOURITES TIP' );
			$text = JText::_( 'FAVOURITES' );

			$link 	= 'index.php?view=favourites';
			$output	= '<a href="'.JRoute::_($link).'" class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$image.'</a>';

			return $output;
		}
		return;
	}

	/**
	 * Creates the favourite remove button
	 *
	 * @param array $params
	 * @since 1.0
	 */
	function removefavbutton(&$params, $item)
	{
		$user	= & JFactory::getUser();
		JHTML::_('behavior.tooltip');

		if ($user->id) {
			if ($params->get('show_favourites')) {
				JHTML::_('behavior.tooltip');

				if ( $params->get('show_icons') ) {
					$image = JHTML::_('image.site', 'delete.png', 'components/com_quickfaq/assets/images/', NULL, NULL, JText::_( 'REMOVE FAVOURITE' ));
				} else {
					$image = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'FAVOURITES' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
				}
				$overlib = JText::_( 'REMOVE FAVOURITE TIP' );
				$text = JText::_( 'REMOVE FAVOURITE' );

				$link 	= 'index.php?task=removefavourite&id='.$item->id;
				$output	= '<a href="'.JRoute::_($link).'" class="editlinktip hasTip" title="'.$text.'::'.$overlib.'">'.$image.'</a>';

				return $output;
			}
		}
		return;
	}
}

class quickfaq_upload
{
	function check($file, &$err)
	{
		$params = &JComponentHelper::getParams( 'com_quickfaq' );

		if(empty($file['name'])) {
			$err = 'Please input a file for upload';
			return false;
		}

		jimport('joomla.filesystem.file');
		if ($file['name'] !== JFile::makesafe($file['name'])) {
			$err = 'WARNFILENAME';
			return false;
		}

		//check if the imagefiletype is valid
		$format 	= strtolower(JFile::getExt($file['name']));

		$allowable = explode( ',', $params->get( 'upload_extensions' ));
		$ignored = explode(',', $params->get( 'ignore_extensions' ));
		if (!in_array($format, $allowable) && !in_array($format,$ignored))
		{
			$err = 'WARNFILETYPE';
			return false;
		}

		//Check filesize
		$maxSize = (int) $params->get( 'upload_maxsize', 0 );
		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			$err = 'WARNFILETOOLARGE';
			return false;
		}

		$imginfo = null;

		$images = explode( ',', $params->get( 'image_extensions' ));
		
		if($params->get('restrict_uploads', 1) ) {
			
			if(in_array($format, $images)) { // if its an image run it through getimagesize
				if(($imginfo = getimagesize($file['tmp_name'])) === FALSE) {
					$err = 'WARNINVALIDIMG';
					return false;
				}

			} else if(!in_array($format, $ignored)) {

				// if its not an image...and we're not ignoring it
				$allowed_mime = explode(',', $params->get('upload_mime'));
				$illegal_mime = explode(',', $params->get('upload_mime_illegal'));

				if(function_exists('finfo_open') && $params->get('check_mime',1)) {
					// We have fileinfo
					$finfo = finfo_open(FILEINFO_MIME);
					$type = finfo_file($finfo, $file['tmp_name']);
					if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
						$err = 'WARNINVALIDMIME';
						return false;
					}
					finfo_close($finfo);

				} else if(function_exists('mime_content_type') && $params->get('check_mime',1)) {

					// we have mime magic
					$type = mime_content_type($file['tmp_name']);

					if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
						$err = 'WARNINVALIDMIME';
						return false;
					}

				}
			}
		}
		$xss_check =  JFile::read($file['tmp_name'],false,256);
		$html_tags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if(stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				$err = 'WARNIEXSS';
				return false;
			}
		}

		return true;
	}

	/**
	* Sanitize the image file name and return an unique string
	*
	* @since 1.0
	*
	* @param string $base_Dir the target directory
	* @param string $filename the unsanitized imagefile name
	*
	* @return string $filename the sanitized and unique image file name
	*/
	function sanitize($base_Dir, $filename)
	{
		jimport('joomla.filesystem.file');

		//check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
		$filename = preg_replace( "/^[.]*/", '', $filename );
		$filename = preg_replace( "/[.]*$/", '', $filename ); //shouldn't be necessary, see above

		//we need to save the last dot position cause preg_replace will also replace dots
		$lastdotpos = strrpos( $filename, '.' );

		//replace invalid characters
		$chars = '[^0-9a-zA-Z()_-]';
		$filename 	= strtolower( preg_replace( "/$chars/", '_', $filename ) );

		//get the parts before and after the dot (assuming we have an extension...check was done before)
		$beforedot	= substr( $filename, 0, $lastdotpos );
		$afterdot 	= substr( $filename, $lastdotpos + 1 );

		//make a unique filename for the image and check it is not already taken
		//if it is already taken keep trying till success
		$now = time();

		while( JFile::exists( $base_Dir . $beforedot . '_' . $now . '.' . $afterdot ) )
		{
			$now++;
		}

		//create out of the seperated parts the new filename
		$filename = $beforedot . '_' . $now . '.' . $afterdot;

		return $filename;
	}
}

class quickfaq_images
{
	/**
	 * Get file icons
	 *
	 * @since 1.0
	 */
	function BuildIcons($rows)
	{
		jimport('joomla.filesystem.file');
		
		$basePath = COM_QUICKFAQ_FILEPATH;

		for ($i=0, $n=count($rows); $i < $n; $i++) {

			if (is_file($basePath.DS.$rows[$i]->filename)) {
				$path = str_replace(DS, '/', JPath::clean($basePath.DS.$rows[$i]->filename));

				$size = filesize($path);

				if ($size < 1024) {
					$rows[$i]->size = $size . ' bytes';
				} else {
					if ($size >= 1024 && $size < 1024 * 1024) {
						$rows[$i]->size = sprintf('%01.2f', $size / 1024.0) . ' Kb';
					} else {
						$rows[$i]->size = sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
					}
				}

				$ext = strtolower(JFile::getExt($rows[$i]->filename));
				switch ($ext)
				{
					// Image
					case 'jpg':
					case 'png':
					case 'gif':
					case 'xcf':
					case 'odg':
					case 'bmp':
					case 'jpeg':
						$rows[$i]->icon = 'components/com_quickfaq/assets/images/mime-icon-16/image.png';
						break;

					// Non-image document
					default:
						$icon = JPATH_SITE.DS.'components'.DS.'com_quickfaq'.DS.'assets'.DS.'images'.DS.'mime-icon-16'.DS.$ext.'.png';
						if (file_exists($icon)) {
							$rows[$i]->icon = 'components/com_quickfaq/assets/images/mime-icon-16/'.$ext.'.png';
						} else {
							$rows[$i]->icon = 'components/com_quickfaq/assets/images/mime-icon-16/unknown.png';
						}
						break;
				}
			}

		}

		return $rows;
	}
}
?>