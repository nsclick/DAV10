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
 * @version    $Id: Abstract.php 18951 2010-01-12 16:26:19Z brunitto $
 */
 
/**
 * Abstract database cursors.
 *
 * @package    Zend_Db
 * @subpackage Cursor
 * @author     brunitto
 */
abstract class Zend_Db_Cursor_CursorAbstract implements Countable, Iterator
{
    /**
     * Database specific cursor.
     *
     * @var resource
     */
    protected $_cursor = null;
     
    /**
     * Cursor rows.
     *
     * @var array
     */
    protected $_rows   = array();
     
    /**
     * Rows offset. Useful for Iterator interface methods.
     *
     * @var integer
     */
    protected $_offset = 0;
     
    /**
     * Return the rows count. Required by the Iterator implementation.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_rows);    
    }
     
    /**
     * Return the current row. Required by Iterator implementation.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_rows[$this->_offset];
    }
     
    /**
     * Return the current row key. Required by Iterator implementation.
     *
     * @return integer
     */
    public function key()
    {
        return $this->_offset;
    }
     
    /**
     * Increment the rows offset.
     *
     * @return void
     */
    public function next()
    {
        ++$this->_offset;
    }
     
    /**
     * Rewind the rows offset.
     *
     * @return void
     */
    public function rewind()
    {
        $this->_offset = 0;
    }
     
    /**
     * Return if the current row is valid.
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->_rows[$this->_offset]);
    }
     
    /**
     * Pop a row from cursor.
     *
     * @return mixed
     */
    public function popRow()
    {
        return array_pop($this->_rows);
    }
     
    /**
     * Push a row to cursor.
     *
     * @param  Zend_Db_Cursor_Row $row 
     * @return boolean
     */
    public function pushRow(Zend_Db_Cursor_Row $row)
    {
        return array_push($this->_rows, $row);
    }
     
    /**
     * Free a cursor.
     *
     * @return void
     */
    public function free()
    {
        // Method body defined in specific classes   
    }
     
    /**
     * Execute the cursor.
     *
     * @return void
     */
    public function execute()
    {
        // Method body defined in specific classes
    }
     
    /**
     * Get the database cursor.
     *
     * @return resource
     */
    public function getRawCursor()
    {
        return $this->_cursor;
    }
}