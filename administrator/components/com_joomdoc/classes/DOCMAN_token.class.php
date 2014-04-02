<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: DOCMAN_token.class.php 612 2008-02-18 18:50:40Z mjaz $
 * @package JoomDOC
 * @copyright (C) 2003-2008 The DOCman Development Team
 *            Improved to JoomDOC by Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

if (defined('_DOCMAN_token')) {
    return true;
} else {
    define('_DOCMAN_token', 1);
}

/**
 * Utility class to work with form tokens
 *
 * @example:
 * In a form:
 * <code>
 * <?php echo DOCMAN_token::render();?>
 * </code>
 * Where the form is submitted:
 * <code>
 * <?php DOCMAN_token::check() or die('Invalid Token'); ?>
 * </code>
 *
 * @static
 */
class DOCMAN_Token
{
    /**
     * Generate new token and store it in the session
     *
     * @see render()
     * @return	string	Token
     */
    function get($forceNew = false)
    {
        static $token;

        if($forceNew || !isset($token))
        {
            @session_start();

            $token = md5(uniqid(rand(), TRUE));
            $_SESSION['docman.token'] = $token;
        }

        return $token;
    }

    /**
     * Render the hidden input field with the token
     *
     * @return	string	Html
     */
    function render()
    {
    	return '<input type="hidden" name="'.DOCMAN_Token::get().'" value="1" />';
    }

    /**
     * Check if a valid token was submitted
     *
     * @todo	When all forms are updated to fully use $_POST, so should this
     *
     * @return	boolean	True on success
     */
    function check()
    {
        @session_start();

        if(!isset($_SESSION['docman.token']))
        {
        	return false;
        }

        $token = $_SESSION['docman.token'];

        if(isset($_REQUEST[$token]) && $_REQUEST[$token])
        {
            return true;
        }
        
        return false;
    }


}