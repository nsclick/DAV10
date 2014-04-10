/**
 * @version		$Id: davila.js 2010-08-04 sgarcia $
 * @package		Clínica Dávila
 * @subpackage	DO
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/

function menudavila()
{
	jQuery(" #menu-suphome div.submenu ").css({display: "none"});
	jQuery(" #menu-suphome li").hover(function(){
		if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7 )
		{
			pos = jQuery(this).offset();
			jQuery(this).find('div.submenu:first:hidden').css({'top': parseInt(pos["top"])+13, 'left': parseInt(pos["left"]) });
		}
		else if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<8 )
		{
			pos = jQuery(this).offset();
			jQuery(this).find('div.submenu:first:hidden').css({'top': parseInt(pos["top"])+8, 'left': parseInt(pos["left"]) });
		}
		jQuery(this).find('div.submenu:first:hidden').css({visibility: "visible",display: "none"}).slideDown(400);
	},function(){
		jQuery(this).find('div.submenu:first').slideUp(400);
	});
	
	jQuery(" #menu-principal div.submenu ").css({display: "none"});
	jQuery(" #menu-principal li").hover(function(){
		if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<8 )
		{
			pos = jQuery(this).offset();
			jQuery(this).find('div.submenu:first:hidden').css({'top': parseInt(pos["top"])+20, 'left': parseInt(pos["left"]) });
		}
		jQuery(this).find('div.submenu:first:hidden').css({visibility: "visible",display: "none"}).slideDown(400);
	},function(){
		jQuery(this).find('div.submenu:first').slideUp(400);
	});
}
jQuery(document).ready(function()
{
	menudavila();
});
