<?php

/**
 * @version		    frontend/views/parts/view.html.php 2013-12-08 12:10:00 UTC zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2 or later
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.view');
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/models/orders.php');

/**
 * PizzaboxViewParts
 */
class PizzaboxViewParts extends JViewLegacy
{

    public $pagination = null;
    public $user = null;

    function display($tpl = null)
    {
        jimport('joomla.html.pagination');
        JHTML::_('behavior.tooltip');
        $app = JFactory::getApplication();
        $this->user = & JFactory::getUser();
        $context = 'com_pizzabox.parts.';

        // get filter and search status
        $filters['order'] = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering', 'cmd');
        $filters['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');

        if (!in_array($filters['order'], array('ordering', 'id'))) {
            $filters['order'] = 'ordering';
        }
        $app->setUserState($context . 'order', 'ordering');
        $app->setUserState($context . 'order_dir', 'asc');

        $session = JFactory::getSession();
        $container_id = $session->get('com_pizzabox.container.id');
        $this->ranges = json_encode($this->getModel()->getMinMaxByContainer($container_id));

        $items = $this->get('items');
        $ranges = $this->getModel()->getMinMaxByContainer($container_id, 'part_id');
        foreach ($items['rows'] as &$part) {
            if (isset($ranges[$part->id])) {
                $part->minimum = $ranges[$part->id]['minimum'];
                $part->maximum = $ranges[$part->id]['maximum'];
            }
            else {
                $part->minimum = 0;
                $part->maximum = 0;
            }
        }
        $this->assign($items);
        $this->container_id = $container_id;

        $lists['flavours'] = $this->getList('flavours', false, true);

        $params = & JComponentHelper::getParams('com_pizzabox');
        $this->assignRef('params', $params);
        $this->assignRef('lists', $lists);

        $document = & JFactory::getDocument();
        $document->addScript(JURI::base() . '/components/com_pizzabox/assets/helper.js');

        $this->loadHelper('pizzabox');
        $this->helper = new PizzaboxHelper();

        $order_id = $session->get('com_pizzabox.order.id');
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
        $this->assignRef('orderData', $this->helper->convertOrderRows($parts));
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

    function getHtmlList($elements_type)
    {
        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        $class_name = "PizzaboxModel" . $elements_type;
        $model = new $class_name();
        return ( $model->getHtmlList() );
    }

    function getList($elements_type, $enable_limit = true, $published_only = false)
    {
        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        $class_name = "PizzaboxModel" . $elements_type;
        $model = new $class_name();
        $elements = $model->getItems($enable_limit, $published_only);
        return ( $elements['rows'] );
    }

    private function getElementImage($elements_type, $id)
    {
        $class_name = "PizzaboxModel" . $elements_type;
        if (!class_exists($class_name))
        {
            require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        }

        $model = new $class_name();
        $model->setId($id);
        $element = $model->getItem();
        return ( $element['row']->image );
    }
}
