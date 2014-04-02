<?php

class Default_Model_Index extends Zend_Db_Table_Abstract {
	
	protected $_name = "usuarios";

    public function  __construct() {
    	
		parent::__construct($config = array());        
    }

    public function recuperar($email = false, $hash) {

        $res = false;

        if($email && $hash) {

            $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from("usuarios", "*")
                    ->where("estado = 1 AND email = '$email'");

            $aux = $this->fetchRow($sql);

            if($aux){
                $nombre = $aux['nombre'];

                $contr = $this->_generarContrasena();
                $hashContr = $hash->crear('sha256', $contr, HASH_PASSWORD);
                $this->_name = "usuarios";
                $up = $this->update(array("contrasena"=>$hashContr), "email = '$email'");
                if($up) {

                    $config = array('auth'=>'login', 'username'=>'davila@c-host.cl', 'password'=>'J@*&^[TT9sVi');
                    $transport = new Zend_Mail_Transport_Smtp('mail.c-host.cl', $config);

                    $mail = new Zend_Mail();
                    $mail->setBodyHtml('<p>Su nueva contrase&ntilde;a es: <strong>' . $contr . '</strong>, recuerde modificarla por una m&aacute;s legible.</p>');
                    $mail->setFrom('jacob@c-online.com', utf8_decode('Administración Profesionales Clínica Dávila'));
                    $mail->addTo($email, $nombre);
                    $mail->setSubject(utf8_decode('Cambio de contraseña'));
                    if($mail->send($transport)) {
                        $res = true;
                        $this->_msg = "Su contraseña ha sido modificada exitosamente, revise su correo";
                    } else {
                        $res = false;
                        $this->_error = "Algo salió mal, intente nuevamente";
                    }
                    
                } else {
                    $res = false;
                    $this->_error = "Algo salió mal, intente nuevamente";
                }
            } else {
                $res = false;
                $this->_error = "El email ingresado no está registrado";
            }

        } else {
            $res = false;
            $this->_error = "No se ha ingresado ningún email";
        }

        return $res;
    }

    public function countUsuarios() {

    	$res = false;

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from("usuarios", array("cantUs"=>"COUNT(*)"));

        $res = $this->fetchRow($sql);
        if($res) {
            $obj = new stdClass();
            $obj->cant_us = $res['cantUs'];
            $res = $obj;
        } else {
            $res = false;
        }

        return $res;
    }

    public function countMedicos() {

    	$res = false;

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from("medicos", array("cantMe"=>"COUNT(*)"));

        $res = $this->fetchRow($sql);
        if($res) {
            $obj = new stdClass();
            $obj->cant_me = $res['cantMe'];
            $res = $obj;
        } else {
            $res = false;
        }

        return $res;
    }

    public function countAreas() {

    	$res = false;

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from("areas", array("cantAr"=>"COUNT(*)"));

        $res = $this->fetchRow($sql);
        if($res) {
            $obj = new stdClass();
            $obj->cant_ar = $res['cantAr'];
            $res = $obj;
        } else {
            $res = false;
        }

        return $res;
    }

    public function countSubareas() {

    	$res = false;

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from("subareas", array("cantSu"=>"COUNT(*)"));

        $res = $this->fetchRow($sql);
        if($res) {
            $obj = new stdClass();
            $obj->cant_su = $res['cantSu'];
            $res = $obj;
        } else {
            $res = false;
        }

        return $res;
    }

    public function countEspecialidades() {

    	$res = false;

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from("especialidades", array("cantEs"=>"COUNT(*)"));

        $res = $this->fetchRow($sql);
        if($res) {
            $obj = new stdClass();
            $obj->cant_es = $res['cantEs'];
            $res = $obj;
        } else {
            $res = false;
        }

        return $res;
    }
	
    private function _generarContrasena() {

        $i = 0;
        $contrasena = "";
        $largo = 8;
        $porBajo = 50;
        $porAlto = 122;
        $noUsar = array (58,59,60,61,62,63,64,73,79,91,92,93,94,95,96,108,111);

        while ($i < $largo) {
            mt_srand ((double)microtime() * 1000000);
            $randNum = mt_rand ($porBajo, $porAlto);
            if (!in_array ($randNum, $noUsar)) {
                $contrasena = $contrasena . chr($randNum);
                $i++;
            }
        }

        return $contrasena;
    }
}
