<?php

class Usuarios_IndexController extends Zend_Controller_Action {
    
    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $msj = $this->_helper->flashMessenger->getMessages();
        if(!empty($msj)) {
            if($msj[0] == "true") {
                $this->view->msj = $msj[1];
            } else {
                $this->view->err = $msj[1];
            }
        }
        
        $this->view->headScript()->appendFile(URL . '/js/app/app.js');
        $this->view->headScript()->appendFile(URL . '/js/app/usuarios/index.js');
        $this->view->nombre = "Módulo Usuarios";

        $objUs = new Usuarios_Model_Usuario();
        $this->view->usuarios = $objUs->listar();
    }

    public function crearAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $this->view->headScript()->appendFile(URL . '/js/app/app.js');
        $this->view->headScript()->appendFile(URL . '/js/app/usuarios/crear.js');
        $this->view->nombre = "Módulo Usuarios // Crear Usuario";

        if($this->_hasParam('usuario')){
            $usuario = $this->_getParam('usuario');
            $helperHash = $this->view->getHelper('Hash');
            $objUs = new Usuarios_Model_Usuario();
            $res = $objUs->crear($usuario, $helperHash);
            
            if(!$res) {
                $this->view->err = $objUs->_error;
                $this->view->resp = $objUs->_resp;
            } else {
                $this->view->msj = $objUs->_msj;
            }
        }
    }

    public function editarAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $up = true;
        $idUs = $this->_getParam('id');

        $objUs = new Usuarios_Model_Usuario();
        $this->view->usuario = $objUs->obtener(array("id = '{$idUs}'"), true);

        $this->view->headScript()->appendFile(URL . '/js/app/app.js');
        $this->view->headScript()->appendFile(URL . '/js/app/usuarios/editar.js');
        $this->view->nombre = "Módulo Usuarios // Editar Usuario";

        if($this->_hasParam('usuario')){
            $usuario = $this->_getParam('usuario');
            if(isset($usuario['contrasena'])) {
                $up = false;
            } else {
                foreach($this->view->usuario as $key=>$val) {
                    if($usuario[$key] != $this->view->usuario->{$key}){
                        $up = false;
                    }
                }
            }

            if(!$up) {
                $hash = $this->view->getHelper('Hash');
                $res = $objUs->editar($usuario, $hash);
                if(!$res) {
                    $this->view->err = $objUs->_error;
                    $this->view->resp = $objUs->_resp;
                } else {
                    $this->view->msj = $objUs->_msj;
                    $this->view->resp = $objUs->_resp;
                }
            } else {
                $this->view->adv = "Ningún dato ha sido modificado.";
            }
        }

    }

    public function eliminarAction() {
        
        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);

        $objUs = new Usuarios_Model_Usuario();
        $idUs = $this->_getParam('id');

        if($objUs->eliminar($idUs)) {            
            $this->_helper->flashMessenger("true");
            $this->_helper->flashMessenger($objUs->_msj);
            $this->_redirect(URL . '/usuarios/');
        } else {
            $this->_helper->flashMessenger("false");
            if(isset($objUs->_error)) {
                $this->_helper->flashMessenger($objUs->_error);    
            } else if($objUs->_adv) {
                $this->_helper->flashMessenger($objUs->_adv);
            }
            $this->_redirect(URL . '/usuarios/');
        }

    }
}