<?php

/**
 * @version		    frontend/views/containers/view.html.php 2013-11-23 22:51:00 UTC zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */
defined('_JEXEC') or die('The way is shut');
jimport('joomla.application.component.view');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/models/orders.php');

/**
 * PizzaboxViewContainers
 */
class PizzaboxViewContainers extends JViewLegacy
{

    public function display($tpl = null)
    {
        $this->containers = $this->getModel()->getListNotEmpty();

        $this->loadHelper('pizzabox');
        $this->helper = new PizzaboxHelper();

        $order_id = JFactory::getSession()->get('com_pizzabox.order.id');
        $this->assignRef('order_id', $order_id);

        $orderModel = new PizzaboxModelOrders();
        $orderModel->setId($order_id);
        $this->order = $orderModel->getItem();
        $parts = $orderModel->getParts();
        foreach ($parts as &$part) {
            $part->container_image = $this->getElementImage('containers', $part->container_id);
            $part->part_image = $this->getElementImage('parts', $part->part_id);
            $part->flavour_image = $this->getElementImage('flavours', $part->flavour_id);
        }

        $this->assignRef('parts', $parts);
        $this->assignRef('tpl', $tpl);
        $this->order_total = $orderModel->getTotal();

        // Load order template from common folder
        $this->addTemplatePath(JPATH_COMPONENT_SITE . '/layouts');
        $this->setLayout('');
        $sublayout['order_details'] = $this->loadTemplate('order_details');
        $this->assignRef('sublayout', $sublayout);
        $this->setLayout('default');
        parent::display($tpl);
    }

    private function getElementImage($elements_type, $id)
    {
        $class_name = "PizzaboxModel" . $elements_type;
        if (!class_exists($class_name)) {
            require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        }

        $model = new $class_name();
        $model->setId($id);
        $element = $model->getItem();
        return ( $element['row']->image );
    }

}
