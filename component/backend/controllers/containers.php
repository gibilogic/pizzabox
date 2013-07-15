<?php

/**
 * @version		    backend/controllers/containers.php 2013-07-07 19:39:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaboxControllerContainers
 */
class PizzaboxControllerContainers extends PizzaboxControllerAbstract
{

    public function __construct($default = array())
    {
        parent::__construct($default);

        $this->_model_parts = & $this->getModel('parts');
    }

    public function save()
    {
        JRequest::checkToken() or die('Invalid Token');
        if (false === $this->_model->save()) {
            $this->edit();
        }
        else {
            $row = & $this->_model->getRow();
            $this->saveParts($row->id);

            if ('apply' == $this->getTask()) {
                $this->_controllerUrl .= '&task=edit&id=' . $row->id;
            }
            $msg = JText::sprintf('PIZZABOX_SAVED', $row->ref);
            $this->setRedirect($this->_controllerUrl, $msg);
        }
    }

    protected function _display($tpl = null)
    {
        $view = & $this->getView($this->_controllerName, 'html');
        $view->setModel($this->_model, true);
        $view->setModel($this->_model_parts);
        $view->display($tpl);
    }

    private function saveParts($container_id)
    {
        $parts_ids = JRequest::getVar('parts_ids');
        $parts_minimums = JRequest::getVar('parts_minimums');
        $parts_maximums = JRequest::getVar('parts_maximums');

        $this->_model->cleanParts($container_id, $parts_ids);
        $this->_model->addParts($container_id, $parts_ids, $parts_minimums, $parts_maximums);
    }

}
