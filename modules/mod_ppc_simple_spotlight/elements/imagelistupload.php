<?php
/*
* Pixel Point Creative "Simple Spotlight" Module for Joomla!
* License: GNU General Public License version 2 http://www.gnu.org/copyleft/gpl.html
* Copyright (c) 2010 Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
* Review our terms/license here: http://pixelpointcreative.com/terms.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.parameter' );
class JFormFieldImageListUpload extends JFormField
{
	protected $_name = 'imagelistupload';
    
    public function getInput()
    {
        $session = JFactory::getSession();
        $options = array();

        $node = new node($this->element['directory'],$this->element['filter'],$this->element['exclude'],$this->element['stripext'], $this->element['hide_none'], $this->element['hide_default']);
       
        $filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$';
        //$node->addAttribute('filter', $filter);
        $elements = new Jparameter();
        //$elements->_elementPath = 'libraries/joomla/html/parameter/element';
        $elements->addElementPath(dirname(__FILE__));
        $e = $elements->loadElement('fileytlist');
        $str = '';	
		$str .= '<script language="javascript">';
		$str .= ' function Upload() {';	
		$str .= 'var Mywidth;';
		$str .= 'var Myheight;';
		$str .= 'Mywidth = (window.screen.width/2) - (75 + 10);';
		$str .= 'Myheight = (window.screen.height/2) - (100 + 50);';
		$str .= 'var win2 = window.open("../modules/mod_ppc_simple_spotlight/elements/upload_file.php","Window2","status=no,height=200,width=350,resizable=yes,left=" + Mywidth +",top=" + Myheight +",screenX=" + Mywidth +",screenY=" + Myheight +",toolbar=no,menubar=no,location=no,directories=no");';	
		$str .= 'win2.focus();';
		$str .= ' } ';
		$str .= '</script>';
		$str .= '<input type="button" value="Upload" style="background:#828627; color:#fff; cursor:pointer" onclick="javascript:Upload()">';
        return $e->fetchElement($this->name, $this->value, $node, '', '', $this->id).$str;
    }
}

class node
{
    var $directory = '';
    var $filter = '';
    var $exclude = '';
    var $stripext = '';
    var $hide_none = '';
    var $hide_default = '';
    
    function node($directory = '', $filter = '', $exclude = '', $stripext = '', $hide_none = '', $hide_default='')
    {
        $this->directory = $directory;
        $this->filter = $filter;
        $this->exclude = $exclude;
        $this->stripext = $stripext;
        $this->hide_none = $hide_none;
        $this->hide_default = $hide_default;
    }
    
    function attributes($attribute){        
        return $this->$attribute;
    }
    
}
