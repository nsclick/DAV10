<?php
/**
 * @version		$Id: gpti.php 2010-07-26 sgarcia $
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

class MenuGpti
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
		
		require_once( JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'config.php' );
		require_once( JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'helpers'.DS.'helper.acl.php' );
	
		$this->rows		= array ();
		if(is_array($rows) && count($rows)) :
			foreach ($rows as $row) :
				unset( $params );
				$row->params		= new JParameter($row->params);
				$permiso			= false;
				
				echo $row->params->get('roles');
				if( $row->params->get('roles') ) :
					$roles = explode(",",$row->params->get('roles'));
					foreach( $roles as $rol ) :
						$permiso	= GPTIHelperACL::check( $rol ) ? true : $permiso;
					endforeach;
				else :
					$permiso		= true;
				endif;
				
				//GPTIHelperACL::check( $p_rol='' )
				/*if( $params->get('intranet') && $user->get('id') && $user->get('gid') != 18 ) :
					$permiso	= false;	
				elseif( !$params->get('intranet') && $user->get('id') ) :
					$permiso	= false;	
				endif;*/
				if ( $row->access <= $user->get('aid', 0) && $permiso ) :
					$row->linkorig	= $row->link;
					$row->link		.= $row->type != 'url' && $row->type != 'menulink' ? "&amp;Itemid=" . $row->id : "";
										
					$this->rows[]	= $row;
				endif;
			endforeach;
		endif;
		
		// cargamos jQuery
		//$document->addScript( 'modules/mod_mainmenu/davila.js' );
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
	
	function gpti_principal()
	{
		if( count( $this->rows ) ) :
		?>
                <div class="gpti_ms">
                	<ul>
			<?php foreach( $this->rows as $r => $row ) :
			?>
						<li><a href="<?php echo $row->link;?>" title="<?php echo $row->name;?>"><?php echo $row->name;?></a></li>
            <?php
			endforeach; ?>
					</ul>
                </div>
                <div id="gpti_punta"><img src="<?php JURI::base();?>templates/gpti/imagenes/punta.jpg" width="25" height="13" alt="" /></div>
		<?php endif;
	}
				
}
