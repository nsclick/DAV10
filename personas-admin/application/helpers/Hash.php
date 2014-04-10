<?php

class Zend_View_Helper_Hash extends Zend_View_Helper_Abstract {

    /**
    *
    * @param string $algo el algoritmo (md5, sha1, whirlpool, etc)
    * @param string $dato datos a codificar
    * @param string $salt ingrediente secreto
    * @return string el hash final
    */
    public static function crear($algo, $dato, $salt){

        $contexto = hash_init($algo, HASH_HMAC, $salt);
        hash_update($contexto, $dato);
        return (string) hash_final($contexto);
    }
}