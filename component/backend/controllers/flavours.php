<?php

/**
 * @version		    backend/controllers/flavours.php 2013-07-07 19:39:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaboxControllerFlavours
 */
class PizzaboxControllerFlavours extends PizzaboxControllerAbstract
{

    public function __construct($default = array())
    {
        parent::__construct($default);

        $this->_model_parts = & $this->getModel('parts');
    }

    protected function _display($tpl = null)
    {
        $view = & $this->getView($this->_controllerName, 'html');
        $view->setModel($this->_model, true);
        $view->setModel($this->_model_parts);
        $view->display($tpl);
    }

}
