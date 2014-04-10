<?php

error_reporting(E_ERROR | E_PARSE);

class Funcionarios_Model_Funcionario extends Zend_Db_Table_Abstract {

	protected $db;
	protected $memcache;

	public function  __construct() {

		parent::__construct($config = array());

		$config = new Zend_Config_Ini(DIR_CONFIG.'/oracle.ini');
		$this->db = Zend_Db::factory($config->db->oracle);
        $this->memcache = new Memcache();
    }

    public function buscar($q) 
    {
        $sql = "SELECT RUT_FUNCIONARIO, DV_FUNCIONARIO, NOMBRES, APELLIDO_PATERNO, APELLIDO_MATERNO, SEXO, EMAIL, ANEXO 
                FROM MEDISYN_1.CAC_FUNCIONARIOS 
                WHERE (VIGENCIA = 'S' AND TIPO_FUNCIONARIO = 'I') 
                AND (RUT_FUNCIONARIO LIKE '%$q%' OR UPPER(APELLIDO_PATERNO) LIKE UPPER('%$q%'))";
        
        $res = $this->db->fetchAll($sql);
        if(count($res)) {
            $lista = array();

            foreach($res as $val) {
                $obj = new stdClass;
                $obj->rut       = $val['RUT_FUNCIONARIO'].'-'.$val['DV_FUNCIONARIO'];
                $obj->rut_sin   = $val['RUT_FUNCIONARIO'];
                $obj->rut_dv    = $val['DV_FUNCIONARIO'];
                $obj->nombres   = $val['NOMBRES'];
                $obj->apaterno  = $val['APELLIDO_PATERNO'];
                $obj->amaterno  = $val['APELLIDO_MATERNO'];
                $obj->foto      = null;
                (empty($val['EMAIL'])) ? $obj->email = '<span class="label label-important">No tiene email</span>' : $obj->email = strtolower($val['EMAIL']);
                (empty($val['ANEXO'])) ? $obj->anexo = '<span class="label label-important">No tiene anexo</span>' : $obj->anexo = strtolower($val['ANEXO']);
                $rutdv          = $val['RUT_FUNCIONARIO'].$val['DV_FUNCIONARIO'];
                $foto           = $this->obtenerFotos($rutdv);

                if(count($foto)) {
                    $obj->foto = 'http://'.$_SERVER['HTTP_HOST'].'/images/fotos/'.$rutdv.'/'.$rutdv.'.jpg';
                }
                $lista[] = $obj;
            }
            return $lista;
        }
        return false;
    }

    public function obtener($rut = false) {

        if($rut) {

            $sql = "SELECT RUT_FUNCIONARIO, DV_FUNCIONARIO, NOMBRES, APELLIDO_PATERNO, APELLIDO_MATERNO, SEXO, EMAIL, ANEXO 
            FROM MEDISYN_1.CAC_FUNCIONARIOS 
            WHERE VIGENCIA = 'S' 
            AND TIPO_FUNCIONARIO = 'I' 
            AND RUT_FUNCIONARIO = '$rut'";
            $res = $this->db->fetchRow($sql);

            if($res) {
                $obj = new stdClass();
                $obj->rut = $res['RUT_FUNCIONARIO'] . '-' . $res['DV_FUNCIONARIO'];
                $obj->rut_sin = $res['RUT_FUNCIONARIO'];
                $obj->rut_dv = $res['DV_FUNCIONARIO'];
                $obj->nombres = $res['NOMBRES'];
                $obj->apaterno = $res['APELLIDO_PATERNO'];
                $obj->amaterno = $res['APELLIDO_MATERNO'];
                (empty($res['EMAIL'])) ? $obj->email = 'No tiene email' : $obj->email = strtolower($res['EMAIL']);
                (empty($res['ANEXO'])) ? $obj->anexo = 'No tiene anexo' : $obj->anexo = strtolower($res['ANEXO']);
                $rutdv = $val['RUT_FUNCIONARIO'].$val['DV_FUNCIONARIO'];
                $foto = $this->obtenerFotos($rutdv);
                // $foto = $this->obtenerFotos2($res['RUT_FUNCIONARIO']);
                if(count($foto)) {
                    $obj->foto = 'http://'.$_SERVER['HTTP_HOST'].'/images/fotos/'.$rutdv.'/'.$rutdv.'.jpg';
                } else {
                    $obj->foto = null;
                }
                return $obj;
            } else {
                $this->_error[] = "No se encontraron resultados";
                return false;    
            }

        } else {
            $this->_error[] = "No se envió ningún dato";
            return false;
        }
    }

    public function listar() {

        $lista = array();
        $foto = array();
        $mc = new Memcached();
        $mc->addServer("127.0.0.1", 11211);
        $res = $mc->get("funcionarios");

        if(!$res) {
            $sql = "SELECT RUT_FUNCIONARIO, DV_FUNCIONARIO, NOMBRES, APELLIDO_PATERNO, APELLIDO_MATERNO, SEXO, EMAIL, ANEXO 
            FROM MEDISYN_1.CAC_FUNCIONARIOS 
            WHERE VIGENCIA = 'S'
            AND TIPO_FUNCIONARIO = 'I'";
            $res = $this->db->fetchAll($sql);   
            $mc->set("funcionarios", $res, time() + (1*24*60*60)) or die ("No se pudo guardar los datos en el servidor Memcached");
        }

        if(count($res)) {
            foreach($res as $val) {
                $obj = new stdClass;
                $obj->rut = $val['RUT_FUNCIONARIO'] . '-' . $val['DV_FUNCIONARIO'];
                $obj->rut_sin = $val['RUT_FUNCIONARIO'];
                $obj->rut_dv = $val['DV_FUNCIONARIO'];
                $obj->nombres = $val['NOMBRES'];
                $obj->apaterno = $val['APELLIDO_PATERNO'];
                $obj->amaterno = $val['APELLIDO_MATERNO'];
                (empty($val['EMAIL'])) ? $obj->email = '<span class="label label-important">No tiene email</span>' : $obj->email = strtolower($val['EMAIL']);
                (empty($val['ANEXO'])) ? $obj->anexo = '<span class="label label-important">No tiene anexo</span>' : $obj->anexo = strtolower($val['ANEXO']);
                $rutdv = $val['RUT_FUNCIONARIO'].$val['DV_FUNCIONARIO'];
                $foto = $this->obtenerFotos($rutdv);
                // $foto = $this->obtenerFotos2($val['RUT_FUNCIONARIO']);
                if(count($foto)) {
                    $obj->foto = 'http://'.$_SERVER['HTTP_HOST'].'/images/fotos/'.$rutdv.'/'.$rutdv.'.jpg';
                } else {
                    $obj->foto = null;

                    $sinFoto[] = $obj;
                }
                $lista[] = $obj;
            }

            return $lista;
        } else {
            $this->_error[] = "No se encontró ningún funcionario en la base de datos";
            return false;
        }
    }

    protected function obtenerFotos($rutdv) {
        $dir = APPLICATION_PATH . '/../../images/fotos/' . $rutdv . '/';
        $imagen = glob($dir . $rutdv . ".jpg");
        return $imagen;
    }

    protected function obtenerFotos2($rut) {
    	$dir = APPLICATION_PATH . '/../../images/fotos/';
        $imagen = glob($dir . $rut . ".jpg");
        return $imagen;
    }
}