<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Digits.php 22668 2010-07-25 14:50:46Z thomas $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_Rut extends Zend_Validate_Abstract
{
	const NOT_RUT   	= 'notRut';
    const STRING_EMPTY 	= 'rutStringEmpty';
    const INVALID      	= 'rutInvalid';

	/**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
       	self::NOT_RUT   	=> "'%value%' debe tener el formato 11.111.111-k",
       	self::STRING_EMPTY 	=> "Debe ingresar un Rut",
       	self::INVALID      	=> "Rut ingresado es inválido",
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a valid rut
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {

    	$err = false;
    	$rt = trim($value);

    	/***************************************************************************/
    	if ('' === $rt) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }

        $this->_setValue((string) $rt);

        /***************************************************************************/
    	$perm = "1234567890kK-.";
		for ($i = 0; $i < strlen($this->_value); $i++){ 
            if (strpos($perm, substr($this->_value, $i, 1)) === false) $err = true;
        }
        if($err) {
        	$this->_error(self::NOT_RUT);
        	return false;
        }
        
        /****************************************************************************/
        if(strpos($this->_value, "-") == false) {
			$RUT[0] = substr($this->_value, 0, -1);
			$RUT[1] = substr($this->_value, -1);
		} else {
			$RUT = explode("-", trim($this->_value));
		}

		$elRut = str_replace(".", "", trim($RUT[0]));
		$factor = 2;
		$suma = 0;

		for($i = strlen($elRut)-1; $i >= 0; $i--) {
			$factor = $factor > 7 ? 2 : $factor;
			$suma += $elRut{$i} * $factor++;
		}

		$resto = $suma % 11;
		$dv = 11 - $resto;

		if($dv == 11){
			$dv = 0;
		} else if($dv == 10){
			$dv = "k";
		} else {
			$dv = $dv;
		}

		if($dv != trim(strtolower($RUT[1]))) {
			$this->_error(self::INVALID);
        	return false;
		}

        return true;
    }
}