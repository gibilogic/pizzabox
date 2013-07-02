<?php

/**
 * @version		    controllers/orders.php 2013-07-02 20:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

require_once('abstract.php');

class PizzaboxControllerOrders extends PizzaboxControllerAbstract
{

    public function __construct($default = array())
    {
        parent::__construct($default);

        $this->_model_address = & $this->getModel('addresses');
    }

    public function display()
    {
        if (JRequest::getVar('change_status') == TRUE) {
            $this->changeStatus();
        }
        $this->_display();
    }

    public function changeStatus()
    {
        JRequest::checkToken() or die('Invalid Token');

        if ($this->_model->changeStatus()) {
            $msg = JText::sprintf('Successfully changed %s status', count($this->_model->getCid()));
            $this->setRedirect($this->_controllerUrl, $msg);
        }
        else {
            $this->_setDefaultRedirect();
        }
    }

    public function _display($tpl = null)
    {
        $view = & $this->getView($this->_controllerName, 'html');
        $view->setModel($this->_model, true);
        $view->setModel($this->_model_address);
        $view->display($tpl);
    }

}
