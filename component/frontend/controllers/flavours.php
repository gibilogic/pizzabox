<?php

/**
 * @version		    frontend/controllers/flavours.php 2013-07-07 1954:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.controller');

class PizzaboxControllerFlavours extends JControllerLegacy
{

    public $_controllerUrl = '';
    public $_model = NULL;

    public function __construct($default = array())
    {
        if (!JRequest::getCmd('view')) {
            JRequest::setVar('view', 'flavours');
        }

        parent::__construct($default);

        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/flavours.php' );
        $this->_model = & $this->getModel('flavours');
        $this->_controllerUrl = 'index.php?option=com_pizzabox&controller=flavours';
    }

    public function display($tpl = null)
    {
        $view = & $this->getView('flavours', 'html');
        $view->setModel($this->_model, true);
        $view->display($tpl);
    }

}
