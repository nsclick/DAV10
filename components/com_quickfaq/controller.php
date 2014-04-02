<?php
/**
 * @version 1.0 $Id: controller.php 200 2009-02-01 01:33:51Z schlu $
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

jimport('joomla.application.component.controller');

/**
 * QuickFAQ Component Controller
 *
 * @package Joomla
 * @subpackage QuickFAQ
 * @since 1.0
 */
class QuickfaqController extends JController
{
	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display the view
	 */
	function display()
	{
		// View caching logic -- simple... are we logged in?
		// Crappy joomla caching...its a fix t exclude certain items from caching
		// see: http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=10840
		$user = &JFactory::getUser();
		if ($user->get('id') || JRequest::getVar('view') == 'category') {
			parent::display(false);
		} else {
			parent::display(true);
		}
	}

	/**
	* Edits an item
	*
	* @access	public
	* @since	1.0
	*/
	function edit()
	{
		$user	=& JFactory::getUser();

		// Create the view
		$view = & $this->getView('Items', 'html');

		//general access check
		if (!($user->authorize('com_quickfaq', 'edit') || $user->authorize('com_content', 'edit', 'content', 'own'))) {
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		// Get/Create the model
		$model = & $this->getModel('Items');

		//check if we have an id(edit) and check if we have global edit rights or if we are allowed to edit own items.
		if( $model->get('id') > 1 && !($user->authorize('com_quickfaq', 'edit') || ($user->authorize('com_content', 'edit', 'content', 'own') && $model->get('created_by') == $user->get('id')) ) ) {
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		//checked out?
		if ( $model->isCheckedOut($user->get('id')))
		{
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('The item'), $model->get('title'));
			$this->setRedirect(JRoute::_('index.php?view=items&id='.$model->get('id'), false), $msg);
			return;
		}

		//Checkout the item
		$model->checkout();

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Set the layout
		$view->setLayout('form');

		// Display the view
		$view->display();
	}
	
	/**
	* adds an item
	*
	* @access	public
	* @since	1.0
	*/
	function add()
	{
		$user	=& JFactory::getUser();

		// Create the view
		$view = & $this->getView('Items', 'html');

		//general access check
		if (!$user->authorize('com_quickfaq', 'add')) {
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		// Get/Create the model
		$model = & $this->getModel('Items');

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Set the layout
		$view->setLayout('form');

		// Display the view
		$view->display();
	}

	/**
	* Saves the item
	*
	* @access	public
	* @since	1.0
	*/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize variables
		$db			= & JFactory::getDBO();
		$user		= & JFactory::getUser();

		//get model
		$model = $this->getModel('Items');

		//get data from request
		$post = JRequest::get('post');

		//perform access checks
		$isNew = ((int) $post['id'] < 1);

		// Must be logged in
		if ($user->get('id') < 1) {
			JError::raiseError( 403, JText::_('ALERTNOTAUTH') );
			return;
		}

		//access checks
		if ( !(($user->authorize('com_quickfaq', 'edit') || $user->authorize('com_content', 'edit', 'content', 'own')) || $user->authorize('com_quickfaq', 'add'))) {
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		if ($model->store($post)) {
			if($isNew) {
				$post['id'] = (int) $model->get('id');
			}
		} else {
			$msg = JText::_( 'ERROR STORING ITEM' );
			JError::raiseError( 500, $model->getError() );
		}

		$model->checkin();

		if ($isNew) {

			//Get categories for information mail
			$query = 'SELECT DISTINCT c.id, c.title,'
			. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug'
			. ' FROM #__quickfaq_categories AS c'
			. ' LEFT JOIN #__quickfaq_cats_item_relations AS rel ON rel.catid = c.id'
			. ' WHERE rel.itemid = '.(int) $model->get('id')
			;

			$db->setQuery( $query );

			$categories = $db->loadObjectList();

			//loop through the categories to create a string
			$n = count($categories);
			$i = 0;
			$catstring = '';
			foreach ($categories as $category) {
				$catstring .= $category->title;
				$i++;
				if ($i != $n) {
					$catstring .= ', ';
				}
			}

			//get list of admins who receive system mails
			$query = 'SELECT id, email, name' .
			' FROM #__users' .
			' WHERE sendEmail = 1';
			$db->setQuery($query);
			if (!$db->query()) {
				JError::raiseError( 500, $db->stderr(true));
				return;
			}
			$adminRows = $db->loadObjectList();

			require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_messages'.DS.'tables'.DS.'message.php');

			// send email notification to admins
			foreach ($adminRows as $adminRow) {

				//Not really  needed cause in com_message you can set to be notified about new messages by email
				//JUtility::sendAdminMail($adminRow->name, $adminRow->email, '', JText::_('NEW FAQ ITEM'), $post['title'], $user->get('username'), JURI::base());

				//Send a message to the admins personal message boxes
				$message = new TableMessage($db);
				$message->send($user->get('id'), $adminRow->id, JText::_('NEW FAQ ITEM'), JText::sprintf('ON NEW ITEM', $post['title'], $user->get('username'), $catstring));
			}

		} else {
			// If the item isn't new, then we need to clean the cache so that our changes appear realtime
			$cache = &JFactory::getCache('com_quickfaq');
			$cache->clean();
		}

		if ($user->authorize('com_quickfaq', 'state') )
		{
			$msg = JText::_( 'ITEM SAVED' );
		}
		else
		{
			$msg = $isNew ? JText::_('THANKS SUBMISSION') : JText::_('ITEM SAVED');
		}

		$link = JRequest::getString('referer', JURI::base(), 'post');
		$this->setRedirect($link, $msg);
	}

	/**
	* Cancels an edit item operation
	*
	* @access	public
	* @since	1.0
	*/
	function cancel()
	{
		// Initialize some variables
		$user	= & JFactory::getUser();

		// Get an item table object and bind post variabes to it
		$item = & JTable::getInstance('quickfaq_items', '');
		$item->bind(JRequest::get('post'));

		// todo: add task checks
		if ($user->authorize('com_quickfaq', 'edit') || $user->authorize('com_quickfaq', 'edit', 'own')) {
			$item->checkin();
		}

		// If the task was edit or cancel, we go back to the item
		$referer = JRequest::getString('referer', JURI::base(), 'post');
		$this->setRedirect($referer);
	}

	/**
	 *  Method of the voting
	 *
	 * @access public
	 * @since 1.0
	 */
	function vote()
	{
		global $mainframe;

		$id 		= JRequest::getInt('id', 0);
		$cid 		= JRequest::getInt('cid', 0);
		$vote		= JRequest::getInt('vote', 0);
		$session 	=& JFactory::getSession();
		$params 	= & $mainframe->getParams('com_quickfaq');

		if (!$params->get('show_vote')) {
			return;
		}

		$cookieName	= JUtility::getHash( $mainframe->getName() . 'quickfaqvote' . $id );
		$voted = JRequest::getVar( $cookieName, '0', 'COOKIE', 'INT');

		$votecheck = false;
		if ($session->has('vote', 'quickfaq')) {
			$votecheck = $session->get('vote', 0,'quickfaq');
			$votecheck = in_array($id, $votecheck);
		}

		if ( $voted || $votecheck )	{
			JError::raiseWarning(JText::_('SOME ERROR CODE'), JText::_('YOU ALLREADY VOTED'));
		} else {
			setcookie( $cookieName, '1', time()+1*24*60*60*60 );

			$stamp = array();
			$stamp[] = $id;
			$session->set('vote', $stamp, 'quickfaq');

			$model 	= $this->getModel('items');
			if ($model->storevote($id, $vote)) {
				$msg = JText::_( 'VOTE COUNTED' );
			} else {
				$msg = JText::_( 'VOTE FAILURE' );
				JError::raiseError( 500, $model->getError() );
			}
		}
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		$this->setRedirect(JRoute::_('index.php?view=items&cid='.$cid.'&id='. $id, false), $msg );
	}

	/**
	 *  Get the new tags and outputs html (ajax)
	 *
	 * @access public
	 * @since 1.0
	 */
	function getajaxtags()
	{
		$user = JFactory::getUser();

		if (!$user->authorize('com_quickfaq', 'newtags')) {
			return;
		}

		$id 	= JRequest::getInt('id', 0);
		$model 	= $this->getModel('Items');
		$tags 	= $model->getAlltags();

		$used = null;

		if ($id) {
			//$used 	= $model->getUsedtags($id);
			$used 	= $model->getUsedtags();
		}
		if(!is_array($used)){
			$used = array();
		}

		$rsp = '';
		$n = count($tags);
		for( $i = 0, $n; $i < $n; $i++ ){
			$tag = $tags[$i];

			if( ( $i % 5 ) == 0 ){
				if( $i != 0 ){
					$rsp .= '</div>';
				}
				$rsp .=  '<div class="qf_tagline">';
			}
			$rsp .=  '<span class="qf_tag"><span class="qf_tagidbox"><input type="checkbox" name="tag[]" value="'.$tag->id.'"' . (in_array($tag->id, $used) ? 'checked="checked"' : '') . ' /></span>'.$tag->name.'</span>';
		}
		$rsp .= '</div>';
		$rsp .= '<div class="clear"></div>';
		$rsp .= '<div class="qf_addtag">';
		$rsp .= '<label for="addtags">'.JText::_('ADD TAG').'</label>';
		$rsp .= '<input type="text" id="tagname" class="inputbox" size="30" />';
		$rsp .=	'<input type="button" class="button" value="'.JText::_('ADD TAG BUTTON').'" onclick="addtag()" />';
		$rsp .= '</div>';

		echo $rsp;
	}

	/**
	 *  Add new Tag from item screen
	 *
	 * @access public
	 * @since 1.0
	 */
	function addtag()
	{

		$user = JFactory::getUser();

		$name 	= JRequest::getString('name', '');

		if ($user->authorize('com_quickfaq', 'newtags')) {
			$model 	= $this->getModel('items');
			$model->addtag($name);
		}
		return;
	}

	/**
	 *  Add favourite
	 *
	 * @access public
	 * @since 1.0
	 */
	function addfavourite()
	{
		$cid 	= JRequest::getInt('cid', 0);
		$id 	= JRequest::getInt('id', 0);

		$model 	= $this->getModel('items');
		if ($model->addfav()) {
			$msg = JText::_('FAVOURITE ADDED');
		} else {
			JError::raiseError( 500, $model->getError() );
			$msg = JText::_('FAVOURITE NOT ADDED');
		}
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		$this->setRedirect(JRoute::_('index.php?view=items&cid='.$cid.'&id='. $id, false), $msg );

		return;
	}

	/**
	 *  Remove favourite
	 *
	 * @access public
	 * @since 1.0
	 */
	function removefavourite()
	{
		$cid 	= JRequest::getInt('cid', 0);
		$id 	= JRequest::getInt('id', 0);

		$model 	= $this->getModel('items');
		if ($model->removefav()) {
			$msg = JText::_('FAVOURITE REMOVED');
		} else {
			JError::raiseError( 500, $model->getError() );
			$msg = JText::_('FAVOURITE NOT REMOVED');
		}
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();
		
		if ($cid) {
			$this->setRedirect(JRoute::_('index.php?view=items&cid='.$cid.'&id='. $id, false), $msg );
		} else {
			$this->setRedirect(JRoute::_('index.php?view=favourites', false), $msg );
		}
				
		return;
	}

	/**
	 * Logic to change the state
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	function setstate()
	{
		$id 	= JRequest::getInt( 'id', 0 );
		$state 	= JRequest::getInt( 'state', 0 );

		$model = $this->getModel('items');

		if(!$model->setitemstate($id, $state)) {
			JError::raiseError( 500, $model->getError() );
		}

		if ( $state == 1 ) {
			$img = 'tick.png';
			$alt = JText::_( 'Published' );
		} else if ( $state == 0 ) {
			$img = 'publish_x.png';
			$alt = JText::_( 'Unpublished' );
		} else if ( $state == -1 ) {
			$img = 'disabled.png';
			$alt = JText::_( 'ARCHIVED' );
		} else if ( $state == -2 ) {
			$img = 'publish_r.png';
			$alt = JText::_( 'PENDING' );
		} else if ( $state == -3 ) {
			$img = 'publish_y.png';
			$alt = JText::_( 'OPEN QUESTION' );
		} else if ( $state == -4 ) {
			$img = 'publish_g.png';
			$alt = JText::_( 'IN PROGRESS' );
		}
		
		$cache = &JFactory::getCache('com_quickfaq');
		$cache->clean();

		echo JHTML::image('components/com_quickfaq/assets/images/'.$img, $alt );
	}

	/**
	 * Download logic
	 *
	 * @access public
	 * @since 1.0
	 */
	function download()
	{
		global $mainframe;
		
		jimport('joomla.filesystem.file');

		$id 	= JRequest::getInt( 'fileid', 0 );
		$db		= &JFactory::getDBO();

		$query = 'SELECT filename'
		.' FROM #__quickfaq_files'
		.' WHERE id = ' . (int)$id
		;
		$db->setQuery($query);
		$file = $db->loadResult();

		$basePath = COM_QUICKFAQ_FILEPATH;

		$abspath = str_replace(DS, '/', JPath::clean($basePath.DS.$file));
		
		if (!JFile::exists($abspath)) {
			return;
		}
		
		//get filesize and extension
		$size 	= filesize($abspath);
		$ext 	= strtolower(JFile::getExt($file));
		
		//update hitcount
		$filetable = & JTable::getInstance('quickfaq_files', '');
		$filetable->hit($id);

		// required for IE, otherwise Content-disposition is ignored
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		switch( $ext )
		{
			case "pdf":
				$ctype = "application/pdf";
				break;
			case "exe":
				$ctype="application/octet-stream";
				break;
			case "rar":
			case "zip":
				$ctype = "application/zip";
				break;
			case "txt":
				$ctype = "text/plain";
				break;
			case "doc":
				$ctype = "application/msword";
				break;
			case "xls":
				$ctype = "application/vnd.ms-excel";
				break;
			case "ppt":
				$ctype = "application/vnd.ms-powerpoint";
				break;
			case "gif":
				$ctype = "image/gif";
				break;
			case "png":
				$ctype = "image/png";
				break;
			case "jpeg":
			case "jpg":
				$ctype = "image/jpg";
				break;
			case "mp3":
				$ctype = "audio/mpeg";
				break;
			default:
				$ctype = "application/force-download";
		}
/*		
		JResponse::setHeader('Pragma', 'public');
		JResponse::setHeader('Expires', 0);
		JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
		JResponse::setHeader('Cache-Control', 'private', false);
		JResponse::setHeader('Content-Type', $ctype);
		JResponse::setHeader('Content-Disposition', 'attachment; filename="'.$file.'";');
		JResponse::setHeader('Content-Transfer-Encoding', 'binary');
		JResponse::setHeader('Content-Length', $size);
*/
		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header("Content-Type: $ctype");
		//quotes to allow spaces in filenames
		header("Content-Disposition: attachment; filename=\"".$file."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$size);

		readfile($abspath);
		$mainframe->close();
	}
}
?>