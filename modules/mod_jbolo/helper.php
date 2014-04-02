<?php
defined('_JEXEC') or die('Restricted access'); 

class modJboloHelper	
{	
	function getList()
	{
		require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
		global $mainframe;
		$count		= '';
		$chatuser	= '';
		$chattitle	= '';
		$rows		= '';
		$user =& JFactory::getUser();	
		$doc =& JFactory::getDocument();
	
		if($chat_config['chatusertitle'])
			$chattitle	= 'username';
		else
			$chattitle	= 'name';

		if ($user->id) { 

			$db	= JFactory::getDBO();
			if($chat_config['community']==1)
			{ //this is for community builder
				if($chat_config['fonly'])
				{
					$query = "SELECT DISTINCT u.".$chattitle.", u.id, b.avatar, u.username, u.name " .
					"FROM #__users u, #__session s, #__comprofiler_members a " .
					"LEFT JOIN #__comprofiler b ON a.memberid = b.user_id " .
					"WHERE a.referenceid=".$user->id." AND u.id = a.memberid AND a.memberid " .
					"IN ( s.userid ) AND (a.accepted=1 OR a.pending=1) ORDER BY u.".$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT u.".$chattitle.", u.id, u.username, u.name, b.avatar " .
					"FROM #__users u, #__session s, #__comprofiler b " .
					"WHERE u.id=b.user_id AND u.id IN ( s.userid ) ORDER BY u.".$chattitle ;
				}	
				
			}
			else if( $chat_config['community']==2 )
			{
				$query = "SELECT DISTINCT u.".$chattitle.", u.id, u.username, u.name  " .
				"FROM #__users u, #__session s ".
				"WHERE u.id = s.userid ORDER BY u.".$chattitle ;
			}
			else
			{ 
			// this is for jomsocial community 
				if($chat_config['fonly'])
				{
					$query = "SELECT DISTINCT b.".$chattitle.", cu.thumb, b.username, b.name, b.id "
					. ' FROM #__community_connection as a, #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE a.`connect_from`='. $user->id
					. ' AND cu.userid=a.connect_to '
					. ' AND a.`status`=1 '
					. ' AND a.`connect_to`=b.`id` '
					. ' AND b.id=s.userid '
					. ' ORDER BY b.'.$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT b.".$chattitle.", cu.thumb, b.username, b.name, b.id "
					. ' FROM  #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE b.id=cu.userid AND b.id IN (s.userid) '
					. ' ORDER BY b.'.$chattitle ;
				}	
			}
			
	
			
			$db->setQuery($query);
			$rows	=	$db->loadObjectList();
			$chatuser	.= "<div id='chbox-holder'>
								<div class='ch-box-tl'>
								<div class='ch-box-tr'>	".Jtext::_('MOD_CHAT_TITLE')."</div></div>							
								
								<div class='ch-box-mid'>
								<ul id='jbusers'>
								<li class='logeduser'>".$user->$chattitle."</li>"; 

			$count	= count($rows);
			$i=1;
			$itemid	= JRequest::getVar('Itemid');
			if($count)
			foreach ($rows as $row)
				if( $row->$chattitle != $user->$chattitle )
				{
					if( $chat_config['community']==1 || $chat_config['community']!=2 )
					{
						$img	= '';
						if($chat_config['community']==1){
							$a	=	'<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_comprofiler&&task=userProfile&user='.$row->id.'&Itemid='.$itemid.'">';
							if($row->avatar)
								$img	= $a.'<img src="images/comprofiler/'.$row->avatar.'" width="120" height="90" alt="'.$row->username.'" ></a>';	
							else
								$img	= $a.'<img src="components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png" alt="'.$row->username.'"></a>';
											
						}	
						else if(!$chat_config['community']) 
						{
							$a	= '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_community&view=profile&userid='.$row->id.'&Itemid='.$itemid.'">';
							$img= $a.'<img src="'.$row->thumb.'" alt="'.$row->username.'"></a>';
						}						
						
						$chatuser	.= "<li><a class='tt' href=javascript:void(0) onclick=javascript:chatWith('".$row->username."')>".$row->$chattitle."</a></li>";
										
										
						}
					else
					{	//this is for stand alone
						$chatuser	.= "<li><a class='tt' href=javascript:void(0) onclick=javascript:chatWith('".$row->username."')>".$row->$chattitle."</a></li>";
					}
				}
				
				if($user && !$rows)
					$chatuser	.= "<div class='onoffmsg'>".Jtext::_('MOD_ONLINE_MSG')."</div>";
									
					$chatuser .="</ul></div></div>";
		}
		else
			$chatuser	.= Jtext::_('MOD_OFFLINE_MSG');

		if($count>10)	$chatuser	= "<div style='height:200px; overflow:auto;'>" . $chatuser . "</div>";
	return $chatuser;
	}
}
