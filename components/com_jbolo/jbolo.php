<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$action = JRequest::getVar('action');
$user = &JFactory::getUser();
if (!$user->id) { return; }

switch ($action) {

	case 'chatheartbeat':
	chatHeartbeat();
	break;
	
	case 'sendchat':
	sendChat();
	break;
	
	case 'closechat':
	closeChat();
	break;
	
	case 'startchatsession':
	startChatSession();
	break;
	
	case 'js':
	printJs();
	break;
	
	case 'purge':
	purge();
	break;
	
	case 'nombreusuario':
	nombreusuario();
	break;
	
}

if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

function nombreusuario(){
	$usrnm	= JRequest::getVar('user','','request','string');
	$db		=& JFactory::getDBO();
	$sql	= "SELECT u.name FROM #__users AS u WHERE u.username = '$usrnm'";
	$db->setQuery($sql);
	$nombre	= $db->loadResult();
header('Content-type: application/json');
?>
{
		"nombre": "<?php echo $nombre ;?>",
}
<?php
	exit(0);
}

function chatHeartbeat() {

	$db	=& JFactory::getDBO();
	$user =& JFactory::getUser();
	
	$sql = "SELECT c.*, u.name FROM #__jbolo AS c LEFT JOIN #__users as u ON u.username = c.from
	WHERE c.to = ".$db->Quote($user->username)." AND c.recd = 0 
	ORDER by c.id ASC";
	$db->setQuery($sql);
	$chats = $db->loadObjectList();

	$items = '';

	$chatBoxes = array();

	foreach ($chats as $chat) {

		if (!isset($_SESSION['openChatBoxes'][$chat->from]) && isset($_SESSION['chatHistory'][$chat->from])) {
			$items = $_SESSION['chatHistory'][$chat->from];
		}

		$chat->message = sanitize($chat->message);

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat->from}",
			"fl": "{$chat->name}",
			"t": "{$chat->to}",
			"id": "{$chat->from}",
			"label": "{$chat->name}",
			"m": "{$chat->message}"
	   },
EOD;

	if (!isset($_SESSION['chatHistory'][$chat->from])) {
		$_SESSION['chatHistory'][$chat->from] = '';
	}

	$_SESSION['chatHistory'][$chat->from] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chat->from}",
			"fl": "{$chat->name}",
			"t": "{$chat->to}",
			"id": "{$chat->from}",
			"label": "{$chat->name}",
			"m": "{$chat->message}"
	   },
EOD;
		
		unset($_SESSION['tsChatBoxes'][$chat->from]);
		$_SESSION['openChatBoxes'][$chat->from] = $chat->sent;
	}

	if (!empty($_SESSION['openChatBoxes'])) {
	foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
		if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
			$now = time()-strtotime($time);
			//$time = date('g:iA M dS', strtotime($time));
			$time = strftime("%H:%M, %d", strtotime($time)).' '.ucfirst(strftime("%b", strtotime($time)));

			$message = "Enviado a las $time";
			
			$sql	= "SELECT u.name FROM #__users AS u WHERE u.username = '$chatbox'";
			$db->setQuery($sql);
			$nombre	= $db->loadResult();
			
			if ($now > 180) {
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"fl": "$nombre",
"t": "$chatbox",
"id": "$chatbox",
"label": "$nombre",
"m": "{$message}"
},
EOD;

	if (!isset($_SESSION['chatHistory'][$chatbox])) {
		$_SESSION['chatHistory'][$chatbox] = '';
	}

	$_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"fl": "$nombre",
"t": "$chatbox",
"id": "$chatbox",
"label": "$nombre",
"m": "{$message}"
},
EOD;
			$_SESSION['tsChatBoxes'][$chatbox] = 1;
		}
		}
	}
}

	$sql = "UPDATE #__jbolo AS c 
	SET c.recd = 1 
	WHERE c.to = ".$db->Quote($user->username)." 
	AND c.recd = 0";
	$db->setQuery($sql);
	$db->query();
	
	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}
<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
	$items = '';
	
	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {

	$user =& JFactory::getUser();
	$items = '';
	
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php echo $user->username ;?>",
        "name": "<?php echo $user->name ;?>",
		"items": [
			<?php echo $items;?>
        ]
}
<?php
	exit(0);
}

function sendChat() {
	
	$user =& JFactory::getUser();
	$db	=& JFactory::getDBO();
	$from = $user->username;
	$to = JRequest::getVar('to');
	$message = JRequest::getVar('message');
	$username = $user->username;
	$name = $user->name;

	$_SESSION['openChatBoxes'][$to] = date('Y-m-d H:i:s', time());
	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][$to])) {
		$_SESSION['chatHistory'][$to] = '';
	}
	
	$sql	= "SELECT u.name FROM #__users AS u WHERE u.username = '$to'";
	$db->setQuery($sql);
	$nombre	= $db->loadResult();

	$_SESSION['chatHistory'][$to] .= <<<EOD
		{
			"s": "1",
			"f": "{$username}",
			"fl": "{$name}",
			"t": "{$to}",
			"id": "{$to}",
			"label": "{$nombre}",
			"m": "{$messagesan}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][$to]);

	// Dont store in the DB if user has been logged out
	if (!$user->id) { return false; }
	$chat = new stdClass();
	$chat->from = $from;
	$chat->to = $to;
	$chat->message = $message;
	$chat->sent = date('Y-m-d H:i:s');

	$db->insertObject('#__jbolo', $chat);
	
	echo "1";
	exit(0);
}

function closeChat() {

	unset($_SESSION['openChatBoxes'][JRequest::getVar('chatbox')]);
	
	echo "1";
	exit(0);
}

function sanitize($text) {

	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	$text = addslashes( $text );
	
	return $text;
}

//purge script
function purge() 
{  
	require(JPATH_COMPONENT.DS."config".DS."config.php");
		$purge	= $chat_config['purge'];
		if($purge)
		{			
			if($chat_config['key']==JRequest::getVar('purge'))
			{
				$db		= JFactory::getDBO();
				$db->setQuery("DELETE FROM #__jbolo WHERE DATEDIFF('".date('Y-m-d')."',sent) >=".$chat_config['days']);
				$db->query();
			}
		}
		return 1;
}

function doLinks( ) {

$functext = <<<EOT
function doLinks(text) {
	
	pcs = text.split(" ");

	for(i=0;i<pcs.length;i++) {
		if (!pcs[i].indexOf('http')) {
			text = text.replace(pcs[i], '<a href="'+pcs[i]+'" target="_blank">'+pcs[i]+'</a>');
		}
	}

	return text;
}
EOT;

return $functext;
}

function doSmileys( ) {

	$functext = "function doSmileys(text) {\n";
	
	jimport('joomla.registry.registry');
	jimport('joomla.filesystem.file');
	$smileysfile = JFile::read(dirname(__FILE__) . DS . 'smileys.txt');
	$smileys = explode("\n", $smileysfile);
	
	$i = 0;
	foreach ($smileys as $smiley) {
		if (trim($smiley) == '') { continue; }
	
		$pcs = explode('=', $smiley);
		$pcs[0] = addslashes($pcs[0]);
	
		$img = 'components/com_jbolo/img/smileys/default/' . $pcs[1];
		$imgsrc = "<img src=\"{$img}\" border=\"0\" />";
		$functext .= "\ttext = text.replace('{$pcs[0]}', '{$imgsrc}');\n";
	
	}
	
	$functext .= "\n\treturn text;\n}";

	
	return $functext;	


}

function printJs() {

	error_reporting(0);
	$document =& JFactory::getDocument();
	$document->setMimeEncoding('text/javascript');

	echo doSmileys();
	echo "\n\n";
	echo doLinks();
	echo "\n\n";
	
$script = <<<EOT

function doReplace(text) {

	text = doLinks(text);
	text = doSmileys(text);
	
	return text;
	
}

EOT;

echo $script;
	
}
