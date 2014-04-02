/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: listview.js 561 2008-01-17 11:34:40Z mjaz $
 * @package JoomDOC
 * @copyright (C) 2003-2008 The DOCman Development Team
 *            Improved to JoomDOC by Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/

var st, st1, st2; //sortable tables identifiers

//Initialise listview
function _listview_init()	{

	if(document.getElementById("tableBody") != null)	{
		st = new SortableTable(
			document.getElementById("tableBody"),
			["None", "CaseInsensitiveString", "Number", "CaseInsensitiveString"]
		);
	}
}

function onclickFolder(parid, catid, name, url, icon)	{
	window.parent.setFields(name, url, catid, icon, '', '');
	window.parent.setListCtrl(parid, catid);
}

function onclickItem(name, id, cid, ext, size, time)	{
	window.parent.setFields(name, id, cid, ext, size, time);
}

function setListView(catid) {
	location.href = "index.php?option=com_joomdoc&task=doclink-listview&catid="+catid;
}

window.onload = _listview_init
//always hide the loading status
window.parent.changeDialogStatus('load');