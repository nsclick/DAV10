<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    
	protected function _initAutoload() {
        #autoloadertry {
        Zend_Session::start();
        $modelLoader = new Zend_Application_Module_Autoloader(array('namespace'=>'', 'basePath'=>APPLICATION_PATH.'/modules/default'));
        Zend_Loader::loadFile("c-online/misc.php");
        Zend_Loader::loadFile("PhpExcel/PHPExcel.php");
        Zend_Loader::loadFile("UploadHandler/UploadHandler.php");
        return $modelLoader;
    }
    
    protected function _initDoctype() {
        $view = new Zend_View();
        $view->doctype('HTML5');
        //$view->doctype('XHTML1_STRICT');
    }
    
    protected function _initView() {
        $view = new Zend_View();
        
        #meta
        $view->headMeta()->setCharset('utf-8')
						->appendHttpEquiv('X-UA-Compatible', 'IE=edge,chrome=1')
                        ->appendName('viewport', 'width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1')
                        ->appendName('author', 'daswort'); 
        #title
        $view->headTitle('Portal Davila // Personas')->setSeparator(', ');
        #stylesheet
		$view->headLink()->appendStylesheet(URL . '/css/bootstrap.min.css', false);
        $view->headLink()->appendStylesheet(URL . '/css/bootstrap-responsive.min.css', false);
        $view->headLink()->appendStylesheet(URL . '/css/jquery-ui.css', false);
		$view->headLink()->appendStylesheet(URL . '/css/uniform.css', false);
        $view->headLink()->appendStylesheet(URL . '/css/select2.css', false);
        $view->headLink()->appendStylesheet(URL . '/css/switchy.css', false);
        $view->headLink()->appendStylesheet(URL . '/css/estilo.css', false);

        
        if(!strstr(URI, '/ingresar')) {
            //$view->headLink()->appendStylesheet(URL . '/css/fullcalendar.css', false);
            $view->headLink()->appendStylesheet(URL . '/css/unicorn.main.css', false);
            $view->headLink()->appendStylesheet(URL . '/css/unicorn.grey.css', false, false, array('class'=>'skin-color'));
        }

        //$view->headLink()->appendStylesheet(URL . '/css/jquery-ui.css', false);
        
        #javascript
        $view->headScript()->appendFile(URL . '/js/jquery/1.8.1/jquery.min.js');
        //$view->headScript()->appendFile('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
        $view->headScript()->appendFile(URL . '/js/vendor/jquery-ui-1.10.1.custom.min.js');
        //$view->headScript()->appendFile(URL . '/js/vendor/jquery.ui.custom.js');
        $view->headScript()->appendFile(URL . '/js/vendor/bootstrap.min.js');
        $view->headScript()->appendFile(URL . '/js/vendor/jquery.animate-color.js');
        $view->headScript()->appendFile(URL . '/js/vendor/jquery.event.drag.js');
        $view->headScript()->appendFile(URL . '/js/vendor/jquery.uniform.js');
        $view->headScript()->appendFile(URL . '/js/vendor/select2.min.js');
        $view->headScript()->appendFile(URL . '/js/vendor/jquery.dataTables.min.js');
        $view->headScript()->appendFile(URL . '/js/vendor/switchy.js');
        $view->headScript()->appendFile(URL . '/js/vendor/unicorn.js');


        //$view->headScript()->appendFile(URL . '/js/vendor/excanvas.min.js'); 
        //$view->headScript()->appendFile(URL . '/js/vendor/jquery.flot.min.js');
        //$view->headScript()->appendFile(URL . '/js/vendor/jquery.flot.resize.min.js');


        
        if(!strstr(URI, '/ingresar')) {
            //$view->headScript()->appendFile(URL . '/js/vendor/jquery.peity.min.js');
            //$view->headScript()->appendFile(URL . '/js/app/fullcalendar.js');
            //$view->headScript()->appendFile(URL . '/js/app/app.js');
            //$view->headScript()->appendFile(URL . '/js/app/dashboard.js');
        }
    }
    
    protected function _initViewHelpers() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        
        $view = $layout->getView();
        $view->setHelperPath(APPLICATION_PATH . '/helpers', ''); 
    }
    
    protected function _initSiteRoutes() {
		$this->bootstrap("frontController");
		$front = $this->getResource("frontController");
        
		$router = new Zend_Controller_Router_Rewrite();
		$rutas = new Zend_Config_Ini(APPLICATION_PATH . "/configs/routes.ini");
		$router->addConfig($rutas, 'routes');
		$front->getRouter()->addConfig($rutas, "routes");
    }
}