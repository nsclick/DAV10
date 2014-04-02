<?php

class Usuarios_Model_Usuario extends Zend_Db_Table_Abstract {

	protected $_name = "usuarios";
    protected $_link = "";

    public function  __construct() {

		parent::__construct($config = array());

        $this->_link = @oci_connect("INTRANET",  "INTRA", "(DESCRIPTION=(FAILOVER=on)(LOAD_BALANCE=yes)(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=172.31.2.237)(PORT=1521))(ADDRESS=(PROTOCOL=TCP)(HOST=172.31.2.237)(PORT=1521)))(CONNECT_DATA=(FAILOVER_MODE=(TYPE=select)(METHOD=basic))(SERVICE_NAME=dav_web)))", "AL32UTF8");
    }

    public function listar($datos = array()) {

        $config = new Zend_Config_Ini(DIR_CONFIG . '/oracle.ini');
        $db = Zend_Db::factory($config->db->oracle);
        
        setlocale(LC_ALL, 'es_ES','es_ES.utf8','spanish');

        $sp = "BEGIN MEDISYN_1.CAC_INTRANET_PKG.PROC_CONS_FUNCIONARIOS_MASIVA"
            ." ( :NOMBRES, :APELLIDO_PATERNO, :APELLIDO_MATERNO, :EMAIL, :MES_CUMPLEANOS, :CARGO, :UNIDAD, :C_ENCONTRADOS, :V_MENSAJE, :C_FUNCIONARIOS );"
            ." COMMIT; END;";
            
        $stmt = oci_parse($this->_link, $sp);

        $vacio = null;
        $ap = 'BRA';
        
        oci_bind_by_name($stmt, ':NOMBRES',          $vacio);
        oci_bind_by_name($stmt, ':APELLIDO_PATERNO', $vacio);
        oci_bind_by_name($stmt, ':APELLIDO_MATERNO', $vacio);
        oci_bind_by_name($stmt, ':EMAIL',            $vacio);
        oci_bind_by_name($stmt, ':MES_CUMPLEANOS',   $vacio);
        oci_bind_by_name($stmt, ':CARGO',            $vacio);
        oci_bind_by_name($stmt, ':UNIDAD',           $vacio);
        oci_bind_by_name($stmt, ':C_ENCONTRADOS',    $vacio);
        oci_bind_by_name($stmt, ':V_MENSAJE',        $vacio);
        
        $funcionarios = oci_new_cursor($this->_link);
        oci_bind_by_name($stmt, ':C_FUNCIONARIOS', $funcionarios, -1, OCI_B_CURSOR);
        
        if(!@oci_execute($stmt)) {
            $this->_error = $this->errores(3);
            return false;
        }

        if($_encontrados == '' || $_encontrados == 'S') {
            if(!oci_execute($funcionarios)) {
                $this->_error = $this->errores(3);
                return false;
            }
            $personas = array();
            
            while ($row = oci_fetch_assoc($funcionarios)) {
            
                $row['FOTO'] = 'images/fotos/' . $row['RUT_FUNCIONARIO'] . '.jpg';
                list($dia, $mes, $ano) = explode("-", $row['FECHA_NACIMIENTO']);
                $ano = 1900+(int)$ano;

                switch(strtoupper($mes)) {
                    case 'JAN' : $mes = 1;  break;
                    case 'FEB' : $mes = 2;  break;
                    case 'MAR' : $mes = 3;  break;
                    case 'APR' : $mes = 4;  break;
                    case 'MAY' : $mes = 5;  break;
                    case 'JUN' : $mes = 6;  break;
                    case 'JUL' : $mes = 7;  break;
                    case 'AUG' : $mes = 8;  break;
                    case 'SEP' : $mes = 9;  break;
                    case 'OCT' : $mes = 10; break;
                    case 'NOV' : $mes = 11; break;
                    case 'DIC' : $mes = 12; break;
                }
                $FN_TIEMPO = $ano.'-'.$mes.'-'.$dia;
                $row['FN_TIEMPO'] = strtotime($FN_TIEMPO);
                $row['EMAIL'] = strtolower($row['EMAIL']);
                $personas[] = $row;
            }
            oci_free_statement( $funcionarios );
        } else {
            $this->_error = $_mensaje;
            return false;
        }
        
        oci_free_statement($stmt);

        _db($personas, true);
        return $personas;
    }

    private function errores($nro = 0){
        
        $errores = array(
            0 => 'La conexión de la base de datos MEDISYN no existe',
            1 => 'La consulta necesita parámetros para ser ejecutada',
            2 => 'Los parámetros de la consulta, deben tener al menos 3 caracteres y al menos uno de ellos debe estar presente',
            3 => 'La consulta no se pudo ejecutar',
            4 => 'El Rut es obligatorio en la consulta de funcionario',
            5 => 'Error, nombre de usuario no especificado',
            6 => 'Error, no se encontró el usuario, o bien su clave es incorrecta'
        );
        
        return $nro >= count($errores) ? 'Error Desconocido' : $errores[$nro];
    }
}
