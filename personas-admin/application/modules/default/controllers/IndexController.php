<?php

class IndexController extends Zend_Controller_Action {
    
	
	public function init() {
        
    }

    public function indexAction(){

  		if(!isset($_SESSION['usuario'])) {
			$this->_redirect(URL."/ingresar/");
			exit;
		}

        $this->_redirect(URL."/funcionarios/buscar/");

		//$this->view->headScript()->appendFile(URL . '/js/app/app.js');
		//$this->view->nombre = "Escritorio";

	}

	public function ingresarAction(){

		if(isset($_SESSION['usuario'])) {
			$this->_redirect(URL);
			exit;
		}
		
		$this->view->headLink()->appendStylesheet(URL.'/css/login.css');
		$this->view->headScript()->appendFile(URL.'/js/app/login/login.js');

		if($this->_hasParam('usuario')) {
			$usuario = $this->_getParam('usuario');

            foreach($this->hardUsers() as $usuarioReg) {
                if($usuarioReg['nombre_usuario'] == $usuario['nombre_usuario'] && 
                    $usuarioReg['contrasena'] == $usuario['contrasena']) {

                    $_SESSION['usuario'] = $usuarioReg;
                    
                    if(isset($_SESSION['usuario'])) {
                        $this->_redirect(URL.'/funcionarios/buscar/');
                    }
                    break;
                }
            }

			unset($_SESSION['usuario']);
			$this->view->err = "Nombre de usuario o contraseña incorrecta";
		}
	}

	public function salirAction() {

		unset($_SESSION['usuario']);
		session_destroy();
		$this->_redirect(URL . '/ingresar/');
		exit;
	}

	public function recuperarAction() {

		$this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);

        if($this->_hasParam('email')) {
        	
        	$email = $this->_getParam('email');
        	$obj = new Default_Model_Index();
        	$hash = $this->view->getHelper('Hash');
        	

        	if($obj->recuperar($email, $hash)) {
        		$this->_helper->flashMessenger("true");
        		$this->_helper->flashMessenger($obj->_msg);
        	} else {
        		$this->_helper->flashMessenger("false");
        		$this->_helper->flashMessenger($obj->_error);
        	}
        	$this->_redirect(URL);
        }
	}



	public function escritorioAction(){
		
        //if(!isset($_SESSION['usuario'])) {
        //    $this->_redirect(URL."/ingresar/");
        //    exit;
        //}

        $this->_redirect(URL."/ingresar/");
	}

    private function hardUsers() {

        return array(
            array(
                'nombre_usuario'    => 'daswort',
                'contrasena'        => 'tragedia',
                'nombre'            => 'Administrador'
            ),
            array(
                'nombre_usuario'    => 'admin',
                'contrasena'        => 'PortalDavila3026',
                'nombre'            => 'Administrador'
            ),
            array(
                'nombre_usuario'    => 'Pvasquez',
                'contrasena'        => 'pvasquez',
                'nombre'            => 'Pamela Vásquez Correa'
            ),
            array(
                'nombre_usuario'    => 'Pparada',
                'contrasena'        => 'pparada',
                'nombre'            => 'Pamela Parada Sepúlveda'
            ),
            array(
                'nombre_usuario'    => 'Nmonsalve',
                'contrasena'        => 'nmonsalve',
                'nombre'            => 'Natalia Monsalve'
            ),
            array(
                'nombre_usuario'    => 'Yahumada',
                'contrasena'        => 'yahumada',
                'nombre'            => 'Yoice Ahumada'
            ),
            array(
                'nombre_usuario'    => 'Cwatson',
                'contrasena'        => 'cwatson',
                'nombre'            => 'Claudia Watson'
            ),
        );
    }
}