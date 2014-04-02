<?php

class LayoutController extends Zend_Controller_Action
{
    public function init()
    {
        
    }

    public function indexAction()
    {
        // action body
    }

    public function headerAction()
    {
        // action body
    }
	
	public function footerAction()
    {
        // action body
    }
	
	public function sidebarAction()
    {
        $arr = explode('/', URI);

        $helperSid = $this->view->getHelper('Sidebar');
        $this->view->sidebar = $helperSid->sidebar($arr);        
    }
	
	public function headerbodyAction()
    {

        $arr = explode('/', URI);

        $helperMig = $this->view->getHelper('Migas');
        $this->view->migas = $helperMig->migas($arr);
    }
	
}

