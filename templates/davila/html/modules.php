<?php
/**
 * @version		$Id: modules.php 2010-07-26 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Dise�o Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Dise�o Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Dise�o Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// No se puede acceder directamente
defined('_JEXEC') or die('Restricted access');

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */

/*
 * Module chrome for rendering the module in a slider
 */
function modChrome_slider($module, &$params, &$attribs)
{
	jimport('joomla.html.pane');
	// Initialize variables
	$sliders = & JPane::getInstance('sliders');
	$sliders->startPanel( JText::_( $module->title ), 'module' . $module->id );
	echo $module->content;
	$sliders->endPanel();
}

/*
 * Module chrome that allows for rounded corners by wrapping in nested div tags
 */
function modChrome_jarounded($module, &$params, &$attribs)
{ ?>
		<div class="jamod module<?php echo $params->get('moduleclass_sfx'); ?>" id="Mod<?php echo $module->id; ?>">
			<div>
				<div>
					<div>
						<?php if ($module->showtitle != 0) : ?>
						<?php
						if(isset($_COOKIE['Mod'.$module->id])) $modhide = $_COOKIE['Mod'.$module->id];
						else $modhide = 'show';
						?>
						<h3 class="<?php echo $modhide; ?>"><span><?php echo $module->title; ?></span></h3>
						<?php endif; ?>
						<div class="jamod-content"><?php echo $module->content; ?></div>
					</div>
				</div>
			</div>
		</div>
	<?php
}

function modChrome_boxshome($module, &$params, &$attribs)
{
	if( $module->id ) :
		if( !isset( $GLOBALS["boxshome"] ) ) :
			$GLOBALS["boxshome"] = 1;
		else :
			++$GLOBALS["boxshome"];
		endif;
		echo ($GLOBALS["boxshome"]-1)%3 == 0 ? '</div><div class="contenedor_boxshome">':'';
		//echo "<pre>"; print_r( $attribs ); echo "</pre>";
		echo $module->content;
	endif;
}
?>
