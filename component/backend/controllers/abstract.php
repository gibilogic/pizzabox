<?php

/**
 * @version		    backend/controllers/abstract.php 2013-07-07 19:39:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.controller');

/**
 * PizzaboxControllerAbstract
 */
class PizzaboxControllerAbstract extends JControllerLegacy
{

    protected $_controllerName = '';
    protected $_controllerUrl = '';
    protected $_model = NULL;

    public function __construct($default = array())
    {
        $this->_controllerName = JRequest::getCmd('controller');
        if (!JRequest::getCmd('view')) {
            JRequest::setVar('view', $this->_controllerName);
        }
        parent::__construct($default);
        $this->registerTask('apply', 'save');
        $this->registerTask('add', 'edit');
        $this->_model = & $this->getModel($this->_controllerName);
        $this->_controllerUrl = 'index.php?option=com_pizzabox&controller=' . $this->_controllerName;
    }

    public function display()
    {
        $this->_display();
    }

    public function edit()
    {
        if ($this->_model->checkout()) {
            $this->_display('form');
        }
        else {
            $this->_setDefaultRedirect();
        }
    }

    public function save()
    {
        JRequest::checkToken() or die('Invalid Token');
        if (false === $this->_model->save()) {
            $this->edit();
        }
        else {
            $row = & $this->_model->getRow();
            if ('apply' == $this->getTask()) {
                $this->_controllerUrl .= '&task=edit&id=' . $row->id;
            }
            $msg = JText::sprintf('PIZZABOX_SAVED', $row->ref);
            $this->setRedirect($this->_controllerUrl, $msg);
        }
    }

    public function cancel()
    {
        $this->_setDefaultRedirect();
    }

    public function remove()
    {
        JRequest::checkToken() or die('Invalid Token');

        if ($this->_model->remove()) {
            $msg = JText::sprintf('Successfully removed ', count($this->_model->getCid()));
            $this->setRedirect($this->_controllerUrl, $msg);
        }
        else {
            $this->_setDefaultRedirect();
        }
    }

    public function unpublish()
    {
        $this->_publish(false);
    }

    public function publish()
    {
        $this->_publish(true);
    }

    protected function _publish($bool)
    {
        if ($this->_model->publish($bool)) {
            $state = $bool ? 'PIZZABOX_PUBLISHED' : 'PIZZABOX_UNPUBLISHED';
            $msg = JText::sprintf('PIZZABOX_PUBLISH_SUCCESS', count($this->_model->getCid())) . " " . JText::_($state);
            $this->setRedirect($this->_controllerUrl, $msg);
        }
        else {
            $this->_setDefaultRedirect();
        }
    }

    public function orderdown()
    {
        $this->_order(1);
    }

    public function orderup()
    {
        $this->_order(-1);
    }

    protected function _order($inc)
    {
        $this->_model->order($inc);
        $this->_setDefaultRedirect();
    }

    protected function _setDefaultRedirect()
    {
        $this->setRedirect($this->_controllerUrl);
    }

    protected function _display($tpl = null)
    {
        $view = & $this->getView($this->_controllerName, 'html');
        $view->setModel($this->_model, true);
        $view->display($tpl);
    }

}
