<?php

class PerfilController extends Zend_Controller_Action {

	public function init() {
        
    }

    public function indexAction(){

    	if(!isset($_SESSION['usuario'])) {
			$this->_redirect(URL);
			exit;
		}

		$this->view->headLink()->appendStylesheet(URL . '/css/ace.min.css');
		$this->view->headLink()->appendStylesheet(URL . '/css/jquery.gritter.css');
		$this->view->headLink()->appendStylesheet(URL . '/css/bootstrap-editable.css');
		$this->view->headScript()->appendFile(URL . '/js/vendor/x-editable/bootstrap-editable.min.js');
		$this->view->headScript()->appendFile(URL . '/js/vendor/x-editable/ace-editable.min.js');
		$this->view->headScript()->appendFile(URL . '/js/vendor/jquery.gritter.min.js');
		$this->view->headScript()->appendFile(URL . '/js/app/app.js');
		$this->view->headScript()->appendFile(URL . '/js/app/perfil/index.js');

		$objUs = new Usuarios_Model_Usuario();
		$usuario = $objUs->obtener(array("id = {$_SESSION['usuario']->id}"));

		$this->view->nombre = "Perfil de " . $usuario->nombre;
		$this->view->usuario = $usuario;


	}
}