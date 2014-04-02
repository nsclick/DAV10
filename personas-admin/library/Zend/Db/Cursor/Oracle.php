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
 * @version    $Id: Oracle.php 18951 2010-01-12 16:26:19Z brunitto $
 */
 
/**
 * Oracle database cursors.
 *
 * @package    Zend_Db
 * @subpackage Cursor
 * @author     brunitto
 */
class Zend_Db_Cursor_Oracle extends Zend_Db_Cursor
{
    /**
     * Constructor.
     *
     * Receive a database adapter and try to allocate a new OCI cursor.
     *
     * @param  Zend_Db_Adapter_Oracle $db 
     * @throws Zend_Db_Cursor_Exception
     */
    public function __construct(Zend_Db_Adapter_Oracle $db)
    {
        // Get the OCI connection resource and a cursor
        $conn = $db->getConnection();
        $cursor = oci_new_cursor($conn);
         
        // Check if the cursor is a valid resource
        if (!is_resource($cursor)) {
            require_once 'Zend/Db/Cursor/Exception.php';
            throw new Zend_Db_Cursor_Exception('Can\'t allocate a cursor.');
        }
         
        // Setup $_cursor property
        $this->_cursor = $cursor;
    }
     
    public function execute()
    {    
        if (!oci_execute($this->_cursor)) {
            require_once 'Zend/Db/Cursor/Exception.php';
            throw new Zend_Db_Cursor_Exception('Couldn\'t execute the cursor.');
        }
         
        // Cursor is ready to go
        while ($data = oci_fetch_assoc($this->_cursor)) {
            require_once 'Zend/Db/Cursor/Row.php';
            $row = new Zend_Db_Cursor_Row();
             
            foreach ($data as $key => $value) {
                $row->$key = $value;
            }
             
            $this->pushRow($row);
        }
    }
     
    public function free()
    {
        if (!oci_free_statement($this->_cursor)) {
            require_once 'Zend/Db/Cursor/Exception.php';
            throw new Zend_Db_Cursor_Exception('Couldn\'t free the cursor.');
        }
         
        // Reset the cursor
        $this->_cursor = null;
    }
}