<?php

/**
 * @version		    views/parts/view.html.php 2013-07-02 20:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.view');

/**
 * PizzaboxViewParts
 */
class PizzaboxViewParts extends JView
{

    var $pagination = null;
    var $user = null;

    function display($tpl = null)
    {
        jimport('joomla.html.pagination');
        JHTML::_('behavior.tooltip');
        $app = & JFactory::getApplication();
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

        $session = & JFactory::getSession();
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

        $lists['flavours'] = $this->getList('flavours', false);

        $params = & JComponentHelper::getParams('com_pizzabox');
        $this->assignRef('params', $params);
        $this->assignRef('lists', $lists);

        $document = & JFactory::getDocument();
        $document->addScript(JURI::base() . '/components/com_pizzabox/assets/helper.js');

        $this->loadHelper('pizzabox');
        $this->helper = new PizzaboxHelper();

        parent::display($tpl);
    }

    function getHtmlList($elements_type)
    {
        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        $class_name = "PizzaboxModel" . $elements_type;
        $model = new $class_name();
        return ( $model->getHtmlList() );
    }

    function getList($elements_type, $enable_limit = true)
    {
        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        $class_name = "PizzaboxModel" . $elements_type;
        $model = new $class_name();
        $elements = $model->getItems($enable_limit);
        return ( $elements['rows'] );
    }

}
