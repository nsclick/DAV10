<?php

class Zend_View_Helper_Migas extends Zend_View_Helper_Abstract {

	public function migas($mods) {

		$i = 0;

		$html = '<div id="breadcrumb">';
		$html .= '<a href="' . URL . '/escritorio/" title="" class="tip-bottom"><i class="icon-home"></i> Escritorio</a>';

		foreach($mods as $mod) {
			
			if($mod == 'escritorio' || $mod == 'admin_prof') continue;
			if($mod == 'editar' || $mod == 'crear' || $mod == 'eliminar') {
				$html .= '<a href="' . URL . '/' . $mods[2] . '/' . $mod . '/" title="" class="tip-bottom"><i class=""></i> ' . ucwords($mod) . '</a>';
			} else {
				if(!empty($mod)) 
					$html .= '<a href="' . URL . '/' . $mod . '/" title="" class="tip-bottom"><i class=""></i> ' . ucwords($mod) . '</a>';
			}

			$i++;
		}

		$html .= '</div>';

		return $html;
	}
}