<?php

class Zend_View_Helper_Sidebar extends Zend_View_Helper_Abstract {

	public function sidebar($mods) {

		//_db($mods, true);
		isset($mods[2]) ? $modulo = $mods[2] : $modulo = false;
		isset($mods[3]) ? $accion = $mods[3] : $accion = false;
		//echo $modulo . ' | ' . $accion; exit;
		$html = '<a href="#" class="visible-phone"><i class="icon icon-home"></i> Escritorio</a>';
		$html .= '<ul>';

		/** ESCRITORIO **/
		// if($modulo == 'escritorio')
		// 	$html .= '<li class="active"><a href="' . URL . '/"><i class="icon icon-home"></i> <span>Escritorio</span></a></li>';
		// else
		// 	$html .= '<li><a href="' . URL . '/escritorio/"><i class="icon icon-home"></i> <span>Escritorio</span></a></li>';
		// 
		// /** USUARIOS **/
		// if($modulo == 'usuarios')
		// 	$html .= '<li class="active submenu open">';
		// else
		// 	$html .= '<li class="submenu">';
		
		// $html .= '<a href="#"><i class="icon icon-user"></i> <span>Usuarios</span> </a>';
		// $html .= '<ul>';
		// 
		// if($modulo == 'usuarios' && empty($accion))
		// 	$html .= '<li class="active"><a href="' . URL . '/usuarios/">Listar</a></li>';
		// else
		// 	$html .= '<li><a href="' . URL . '/usuarios/">Listar</a></li>';	
		// 
		// if($modulo == 'usuarios' && $accion == 'crear')
		// 	$html .= '<li class="active"><a href="' . URL . '/usuarios/crear/">Crear</a></li>';
		// else
		// 	$html .= '<li><a href="' . URL . '/usuarios/crear/">Crear</a></li>';

		// if($modulo == 'usuarios' && $accion == 'editar')
		// 	$html .= '<li class="active"><a href="' . URL . '/usuarios/editar/">Editar</a></li>';
		// else
		// 	$html .= '<li><a href="' . URL . '/usuarios/editar/">Editar</a></li>';
		// 
		// if($modulo == 'usuarios' && $accion == 'eliminar')
		// 	$html .= '<li class="active"><a href="' . URL . '/usuarios/eliminar/">Eliminar</a></li>';
		// else
		// 	$html .= '<li><a href="' . URL . '/usuarios/eliminar/">Eliminar</a></li>';
		// 
		// $html .= '</ul>';
		// $html .= '</li>';

		/** FUNCIONARIOS **/
		if($modulo == 'funcionarios')
			$html .= '<li class="active submenu open">';
		else
			$html .= '<li class="submenu">';
		
		$html .= '<a href="#"><i class="icon icon-briefcase"></i> <span>Funcionarios</span> </a>';
		$html .= '<ul>';
		
		if($modulo == 'funcionarios' && $accion == 'buscar')		
			$html .= '<li class="active"><a href="' . URL . '/funcionarios/buscar/">Buscar funcionario</a></li>';
		else
			$html .= '<li><a href="' . URL . '/funcionarios/buscar/">Buscar funcionario</a></li>';

		if($modulo == 'funcionarios' && empty($accion))
			$html .= '<li class="active"><a href="' . URL . '/funcionarios/">Listar</a></li>';
		else
			$html .= '<li><a href="' . URL . '/funcionarios/">Listar</a></li>';	

		if($modulo == 'funcionarios' && $accion == 'editar-foto')
			$html .= '<li class="active"><a href="' . URL . '/funcionarios/editar-foto/">Editar foto</a></li>';
		else
			$html .= '<li><a href="' . URL . '/funcionarios/editar-foto/">Editar foto</a></li>';
		
		$html .= '</ul>';
		$html .= '</li>';

		/** CARGA DE FOTOS **/
		if($modulo == 'carga-masiva-fotos')
			$html .= '<li class="active"><a href="' . URL . '/carga-masiva-fotos/"><i class="icon icon-picture"></i> <span>Carga masiva de fotos</span></a></li>';
		else
			$html .= '<li><a href="' . URL . '/carga-masiva-fotos/"><i class="icon icon-picture"></i> <span>Carga masiva de fotos</span></a></li>';

		$html .= '</ul>';


		return $html;
	}
}