<?php
/**
 * @version		$Id: davila.php 2010-07-26 sgarcia $
 * @package		Joomla
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
	
// No se puede acceder directamente
defined('_JEXEC') or die('Restricted access');

class MenuDavila
{
	
	/**
	 * objeto de Parametros
	 *
	 * @var	object
	 * @access	protected
	 */
	var $params	= null;
	
	/**
	 * tipo de menu
	 *
	 * @var	object
	 * @access	protected
	 */
	var $menutype	= null;
	
	/**
	 * arreglo de menus
	 *
	 * @var	object
	 * @access	protected
	 */
	var $rows	= null;
	
	/**
	 * html
	 *
	 * @var	object
	 * @access	protected
	 */
	var $html	= '';

	/**
	 * Constructor.
	 *
	 * @access	protected
	 * @param	array Un array asociativo con configuraciones opcional.
	 */
	function __construct( &$params, $menutype = '' )
	{
		$user			= & JFactory::getUser();
		$menu			= & JSite::getMenu();
		$document		= & JFactory::getDocument();
		$this->params	= $params;
		$this->menutype	= $menutype;
		
		$rows			= $menu->getItems('menutype', $this->params->get('menutype'));
		
		
	
		$this->rows		= array ();
		if(is_array($rows) && count($rows)) :
			foreach ($rows as $row) :
				unset( $params );
				$row->params		= new JParameter($row->params);
				$permiso			= true;
				/*if( $params->get('intranet') && $user->get('id') && $user->get('gid') != 18 ) :
					$permiso	= false;	
				elseif( !$params->get('intranet') && $user->get('id') ) :
					$permiso	= false;	
				endif;*/
				//echo "<pre>"; print_r($row); echo "</pre>";
				/*if( $row->component == 'com_do' ):
					echo $row->component."<br />";
					echo $row->link."<br />";
					echo $row->params->get('controlador');
					exit;
				endif;*/
				if( $row->component == 'com_gpti' && !GPTIHelperACL::checkUser() ) :
					$permiso			= false;
				elseif( $row->component == 'com_do' && $row->params->get('controlador') == 'cupones' ) :
					// usuarios habilitados
					$usuarioshabilitados	= array('funcionario1','vheufemann','MACA','12583945','15272359');
					$permiso			= array_search($user->get('username'), $usuarioshabilitados) !== false;
				endif;
				
				if ( $row->access <= $user->get('aid', 0) && $permiso ) :
					$row->linkorig	= $row->link;
					$row->link		.= $row->type != 'url' && $row->type != 'menulink' ? "&amp;Itemid=" . $row->id : "";
										
					$this->rows[]	= $row;
				endif;
			endforeach;
		endif;
		
		// cargamos jQuery
		$document->addScript( 'modules/mod_mainmenu/davila.js' );
	}
	
	function render()
	{
		$metodos		= get_class_methods( get_class( $this ) );
		$menutype		= $this->menutype;
		if( $menutype && array_search( $menutype, $metodos ) !== false )
		{
			$this->$menutype();
		}
	}
	
	function davila_principal()
	{
		if( count( $this->rows ) ) :
			foreach( $this->rows as $r => $row ) :
			?>
				<a href="<?php echo $row->link;?>" title="<?php echo $row->name;?>"><img src="<?php JURI::base();?>images/stories/<?php echo $row->params->get('menu_image');?>" alt="<?php echo $row->name;?>" title="<?php echo $row->name;?>" border="0" /></a>
            <?php
			endforeach;
		endif;
	}
	
	function davila_suphome()
	{
		if( count( $this->rows ) ) :
		?>
        <div class="menu_suphome">
        <table border="0" align="right" class="menu_suphome">
        <tr> <td>
          <ul id="menu-suphome">
        <?php
			foreach( $this->rows as $r => $row ) :
			
			?>
				<li><?php echo $r==count($this->rows)-1?'&nbsp;&nbsp;|&nbsp;&nbsp;':'';?><a href="<?php echo $row->link;?>" title="<?php echo $row->name;?>"<?php echo $r==count($this->rows)-1?' class="diferente"':'';?>><?php echo $row->name;?><?php /*if( $row->params->get('menu_image') != -1 ) : ?> <img src="<?php JURI::base();?>images/stories/<?php echo $row->params->get('menu_image');?>" alt="<?php echo $row->name;?>" title="<?php echo $row->name;?>" border="0" align="right" /><?php endif;*/?></a>
                <?php if( $row->id == 2 ) : ?>
                	<div class="submenu">
                    	<!--<span><a href="<?php echo $row->link;?>" title="Administración">Administración</a></span>-->
                        <span><a href="<?php echo JRoute::_("index.php?option=com_content&Itemid=".$row->id."&view=article&id=18:resena-historica&catid=17:resena-historica");?>" title="Historia">Historia</a></span>
                    </div>
                <?php endif; ?>
                </li>
            <?php
			endforeach;
		?>
          </ul>
        </td>
        </tr>
        </table>
        </div>
        <?php
		endif;
	}
	
	function davila_suphome_bak()
	{
		if( count( $this->rows ) ) :
		?>
        <div class="menu_suphome">
        <?php
			foreach( $this->rows as $r => $row ) :
			?>
				<a href="<?php echo $row->link;?>" title="<?php echo $row->name;?>"><?php echo $row->name;?></a>
            <?php
			endforeach;
		?>
        </div>
        <?php
		endif;
	}
	
	function davila_supcontenido()
	{
		global $Itemid;
		
		if( count( $this->rows ) ) :
		?>
        <div class="menu_principal">
          <ul id="menu-principal">
        <?php
			foreach( $this->rows as $r => $row ) :
			
				switch ($row->browserNav)
				{
					// cases are slightly different
					case 1 :
						// open in a new window
						$txt = '<a href="' . $row->link . '" title="' . $row->name . '" target="_blank"'.( $row->id == $Itemid ? ' class="activo"':'').'>' . $row->name . '</a>';
						break;
			
					case 2 :
						// open in a popup window
						$txt = "<a href=\"#\" onclick=\"javascript: window.open('" . $row->link . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\"".( $row->id == $Itemid ? ' class="activo"':'').">" . $row->name . "</a>\n";
						break;
			
					case 3 :
						// don't link it
						$txt = '<span>' . $row->name . '</span>';
						break;
			
					default : // formerly case 2
						// open in parent window
						$txt = '<a href="' . $row->link . '"'.( $row->id == $Itemid ? ' class="activo"':'').'>' . $row->name . '</a>';
						break;
				}
			
			?>
				<li><?php echo $txt;?>
                <?php if( $row->id == 10 ) : ?>
                	<div class="submenu" style="width:150px;">
                    	<span><a href="<?php echo $row->link;?>" title="Buscador de Personas">Buscador de Personas</a></span>
                        <span><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=".$row->id."&c=cumpleanos");?>" title="Cumpleaños">Cumpleaños</a></span>
                    	<span><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=".$row->id."&c=reconocimientos");?>" title="Reconocimientos">Reconocimientos</a></span>
                    </div>
                <?php endif; ?>
                </li>
            <?php
			endforeach;
		?>
          </ul>
        </div>
        <?php
		endif;
	}
	
	function davila_supcontenido_bak()
	{
		global $Itemid;
		
		if( count( $this->rows ) ) :
		?>
        <div class="menu_supcontenido">
        <?php
			foreach( $this->rows as $r => $row ) :
			?>
				<a href="<?php echo $row->link;?>" title="<?php echo $row->name;?>"<?php echo $row->id == $Itemid ? ' class="activo"':'';?>><?php echo $row->name;?></a>
            <?php
			endforeach;
		?>
        </div>
        <?php
		endif;
	}
	
	function davila_pie()
	{
		if( count( $this->rows ) ) :
		?>
        <div class="menu">
        <?php
			foreach( $this->rows as $r => $row ) :
				$prefijo	= $r ? ' | ':'';
			?>
				<?php echo $prefijo;?><a href="<?php echo $row->link;?>" title="<?php echo $row->name;?>"><?php echo $row->name;?></a>
            <?php
			endforeach;
		?>
        </div>
        <?php
		endif;
	}
			
}
