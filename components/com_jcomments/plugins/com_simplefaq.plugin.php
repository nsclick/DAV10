<?php
/**
 * JComments plugin for SimpleFaq events support
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2009 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
(defined('_VALID_MOS') OR defined('_JEXEC')) or die('Direct Access to this location is not allowed.');

class jc_com_simplefaq extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = & JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT question FROM #__simplefaq WHERE published = 1 AND id = ' . $id );
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$_Itemid = JCommentsPlugin::getItemid( 'simplefaq' );

		$db = & JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT catid FROM #__simplefaq WHERE published = 1 AND id = ' . $id );
		$catid = $db->loadResult();

		$link = JoomlaTuneRoute::_('index.php?option=com_simplefaq&amp;task=answer&amp;catid='.$catid.'&amp;aid=' . $id . '&amp;Itemid=' . $_Itemid);
		return $link;
	}
}
?>