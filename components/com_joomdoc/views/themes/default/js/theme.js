/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: theme.js 561 2008-01-17 11:34:40Z mjaz $
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

/**
 * Default DOCman Theme
 *
 * Creator:  The DOCman Development Team
 * Website:  http://www.joomlatools.org/
 * Email:    support@joomlatools.org
 * Revision: 1.4
 * Date:     February 2007
 **/

function DMinitTheme()
{
	 DMaddPopupBehavior();
}

function DMaddPopupBehavior()
{
	var x = document.getElementsByTagName('a');
	for (var i=0;i<x.length;i++)
	{
		if (x[i].getAttribute('type') == 'popup')
		{
			x[i].onclick = function () {
				return DMpopupWindow(this.href)
			}
			x[i].title += ' (Popup)';
		}
	}
}

/* -------------------------------------------- */
/* -- utility functions ----------------------- */
/* -------------------------------------------- */

function DMpopupWindow(href)
{
	newwindow = window.open(href,'DOCman Popup','height=600,width=800');
	return false;
}

/* -------------------------------------------- */
/* -- page loader ----------------------------- */
/* -------------------------------------------- */

function DMaddLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

DMaddLoadEvent(function() {
  DMinitTheme();
});
