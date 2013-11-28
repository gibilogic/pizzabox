<?php

/**
 * @version		    frontend/controllers/containers.php 2013-07-07 1954:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.controller');

/**
 * PizzaboxControllerContainers
 */
class PizzaboxControllerContainers extends JControllerLegacy
{

    public $_controllerUrl = '';
    public $_model = NULL;

    public function __construct($default = array())
    {
        if (!JRequest::getCmd('view')) {
            JRequest::setVar('view', 'containers');
        }

        parent::__construct($default);

        $this->_model = & $this->getModel('containers');
        $this->_controllerUrl = JRoute::_('index.php?option=com_pizzabox&controller=containers', false);
        $this->_partsControllerUrl = JRoute::_('index.php?option=com_pizzabox&controller=parts', false);
    }

    public function display($tpl = null)
    {
        $params = & JComponentHelper::getParams('com_pizzabox');
        if ($params->get('registered_users_only', 1) == 1) {
            $user = JFactory::getUser();
            if ($user->guest) {
                $msg = JText::_('PIZZABOX_REGISTERED_ONLY');
                $link = JRoute::_('index.php?option=com_users&task=register', false);
                $this->setRedirect($link, $msg, 'error');
            }
        }

        $view = & $this->getView('containers', 'html');
        $view->setModel($this->_model, true);
        $view->display($tpl);
    }

    public function save()
    {
        JRequest::checkToken() or die('PIZZABOX_INVALID_TOKEN');
        $result = $this->_model->save();
        if (false === $result) {
            $msg = JText::_('PIZZABOX_SAVE_ERROR');
            $this->setRedirect($this->_controllerUrl, $msg, 'error');
        }
        else {
            $this->setRedirect($this->_partsControllerUrl);
        }
    }

}
