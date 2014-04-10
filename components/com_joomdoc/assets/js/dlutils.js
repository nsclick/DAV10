/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: dlutils.js 561 2008-01-17 11:34:40Z mjaz $
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

//Browser information
Browser = new Object();
Browser.agt    = navigator.userAgent.toLowerCase();
Browser.is_ie	= ((Browser.agt.indexOf("msie") != -1) && (Browser.agt.indexOf("opera") == -1));

//Map collaction object
function Map() {
	
}

Map.prototype.toString = function() {
	str = ''; 
	for(var key in this) {
		if(typeof(this[key]) != 'function') {
			if(str) str += ','; 
			str += key+'='+this[key];		
		}
	}
	return str;	
}

String.prototype.toMap = function() {
	var map = new Map();
	var array = this.split(",");
	for (number in array) {
		result = array[number].split("=");
		var key   = result[0];
		var value = result[1];
		map[key] = value;
	}
	return map;
}

function parseBool(str) {
	switch(str) {
		case 'false' :	return new Boolean(false); break;
		case 'true'  : return new Boolean(true);  break;
		default : return; break;
	}
}

// -- Utility function --------------------------
document.getElementsByClassName = function ( class_name ) {
    var all_obj, ret_obj = new Array(), j = 0, strict = 0;
    if ( document.getElementsByClassName.arguments.length > 1 )
        strict = ( document.getElementsByClassName.arguments[1] ? 1 : 0 );
    if ( document.all )
        all_obj = document.all;
    else if ( document.getElementsByTagName && !document.all )
        all_obj = document.getElementsByTagName ( "*" );
    for ( i = 0; i < all_obj.length; i++ ) {
        if ( ( ' ' + all_obj[i].getAttribute("class") + ' ').toLowerCase().match(
            new RegExp ( ( strict ? '^ ' + class_name.trim() + ' $' : 
                '^.* ' + class_name.trim() + ' .*$' ).toLowerCase(),'g' ) ) ) {
            ret_obj[j++] = all_obj[i];
        }
    }
    return ret_obj;
}

String.prototype.trim = function() {
  return(this.replace(/^\s+/,'').replace(/\s+$/,''));
}


