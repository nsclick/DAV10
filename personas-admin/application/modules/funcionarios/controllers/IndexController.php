<?php

class Funcionarios_IndexController extends Zend_Controller_Action {
    
    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

    	$this->view->headScript()->appendFile(URL . '/js/app/app.js');
        $this->view->headScript()->appendFile(URL . '/js/app/funcionarios/index.js');
        if(!strstr(URI, "/editar-foto")) {
            $this->view->nombre = "Módulo Funcionarios // Listar";
        } else {
            $this->view->nombre = "Módulo Funcionarios // Editar foto";
        }

        $func = new Funcionarios_Model_Funcionario();
        $this->view->funcionarios = $func->listar();
    }

    public function editarAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $this->view->headLink()->appendStylesheet(URL . '/css/file-upload/style.css');
        $this->view->headLink()->appendStylesheet('http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css');
        $this->view->headLink()->appendStylesheet(URL . '/css/file-upload/jquery.fileupload-ui.css');

        $this->view->headScript()->appendFile(URL . '/js/app/app.js');
        $this->view->headScript()->appendFile(URL . '/js/app/funcionarios/editar.js');
        $this->view->nombre = "Módulo Funcionarios // Editar";

        if($this->_hasParam('rut')) {
            $rut = $this->_getParam('rut');
            $func = new Funcionarios_Model_Funcionario();
            $this->view->funcionario = $func->obtener($rut);
        }
    }

    public function cargarFotosAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);

        $rutdv = $this->_getParam('rutdv');

        $upload = new UploadHandler(array(
            'accept_file_types' => '/\.(jpg)$/i',
            'upload_dir'        => UPLOAD_DIR,
            'upload_url'        => UPLOAD_URL,
            'user_dirs'         => true,
            'name_user_dir'     => $rutdv,
            'script_url'        => URL.'/funcionarios/cargar-fotos/'.$rutdv .'/',
            'rename_file'       => $rutdv.'.jpg',
        ));
    }

    public function cargaMasivaFotosAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $this->view->headLink()->appendStylesheet(URL . '/css/file-upload/style.css');
        $this->view->headLink()->appendStylesheet('http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css');
        $this->view->headLink()->appendStylesheet(URL . '/css/file-upload/jquery.fileupload-ui.css');

        $this->view->headScript()->appendFile(URL . '/js/app/app.js');
        $this->view->headScript()->appendFile(URL . '/js/app/carga-masiva-fotos/index.js');

        $this->view->nombre = "Módulo Carga masiva de fotos //";
    }

    public function cargaMasivaFotosCargarAction() {

        if(!isset($_SESSION['usuario'])) {
            $this->_redirect(URL);
            exit;
        }

        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);

        $upload = new UploadHandler(array(
            'accept_file_types' => '/\.(jpg)$/i',
            'upload_dir'        => UPLOAD_DIR,
            'upload_url'        => UPLOAD_URL,
            'script_url'        => URL.'/carga-masiva-fotos/cargar/',
            'read_folders'      => true,
            'print_result'      => false,
        ));
    }

    public function buscarAction() 
    {
        $this->view->headScript()->appendFile(URL.'/js/app/app.js');
        $this->view->headScript()->appendFile(URL.'/js/app/funcionarios/buscar.js');
        $this->view->nombre = "Módulo Funcionarios // Buscar";

        if($this->_request->isPost() && $this->_hasParam('busqueda')) {
            $q = $this->_getParam('busqueda');
            if(!empty($q)) {
                $this->view->busqueda = $q;
                $model = new Funcionarios_Model_Funcionario();
                $lista = $model->buscar($q);
                if($lista){
                    $this->view->lista = $lista;
                    return true;
                }                
                $this->view->nombre = "Módulo Funcionarios // Resultado del término: ".$q;
                $this->view->err = "No se encontró ningún resultado con el término ".$q;
                return false;
            }

            $this->view->err = "Debe ingresar un término para realizar la búsqueda";
            return false;
        }
    }

    public function resultadoAction() 
    {
        $this->view->headScript()->appendFile(URL.'/js/app/app.js');
        $this->view->headScript()->appendFile(URL.'/js/app/funcionarios/resultado.js');
        $this->view->nombre = "Módulo Funcionarios // Resultado del término: ".$string;
    }

    /*public function auxAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);

        $rt = array();
        $i = 0;
        $obj = new Funcionarios_Model_Funcionario();
        $dir = "/var/www/html/portal/images/fotos/";
        $fotos = scandir($dir);
        $funcionarios = $obj->listar();
        
        foreach ($funcionarios as $funcionario) {
            foreach ($fotos as $foto) {
                if($funcionario->rut_sin.".jpg" === $foto) {
                    $fotoAntigua = $foto;
                    $fotoNueva = $funcionario->rut_sin.$funcionario->rut_dv.".jpg";
                    //if(rename($dir.$fotoAntigua, $dir.$fotoNueva)){
                    //    echo "Foto [".$i."] Se renombro ".$fotoAntigua." => ".$fotoNueva."<br>";    
                    //} else {
                    //    echo "Foto [".$i."] No se pudo renombrar ".$fotoAntigua." => ".$fotoNueva."<br>";
                    //}
                    $rt[] = $foto;
                    $i++;
                }
            }
        }
    }*/
}