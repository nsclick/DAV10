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
 * @package    Zend_Db
 * @subpackage Cursor
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Exception.php 18951 2010-01-12 16:26:19Z brunitto $
 */
 
/**
 * Database cursors exception class.
 *
 * @package    Zend_Db
 * @subpackage Cursor
 * @author     brunitto
 */
class Zend_Db_Cursor_Exception extends Zend_Exception
{
}
 
 
 
 
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
 * @package    Zend_Db
 * @subpackage Cursor
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Row.php 18951 2010-01-12 16:26:19Z brunitto $
 */
 
/**
 * Database cursors row class.
 *
 * @package    Zend_Db_Cursor
 * @subpackage Cursor
 * @author     brunitto
 */
class Zend_Db_Cursor_Row implements ArrayAccess
{
    /**
     * Data array as column => value.
     *
     * @var array
     */
    protected $_data = array();
     
    public function __get($column)
    {
        $column = strtolower($column);
        if (!array_key_exists($this->_data, $column)) {
            require_once 'Zend/Db/Cursor/Exception.php';
            throw new Zend_Db_Cursor_Exception('Invalid column.');
        }
         
        return $this->_data[$column];
    }
     
    public function __set($column, $value)
    {
        $column               = strtolower($column);
        $this->_data[$column] = $value;
    }
     
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }
     
    public function offsetGet($offset)
    {
        return $this->_data[$offset];
    }
     
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }
     
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }
}