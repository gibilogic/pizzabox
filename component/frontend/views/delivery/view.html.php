<?php

/**
 * @version		    frontend/views/delivery/view.html.php 2013-07-07 20:01:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */
defined('_JEXEC') or die('The way is shut');
jimport('joomla.application.component.view');

/**
 * PizzaboxViewDelivery
 */
class PizzaboxViewDelivery extends JViewLegacy
{

    public $user = null;

    public function display($tpl = null)
    {
        jimport('joomla.html.pagination');
        JHTML::_('behavior.tooltip');
        $this->user = & JFactory::getUser();

        $this->loadHelper('pizzabox');
        $this->helper = new PizzaboxHelper();
        $this->params = & JComponentHelper::getParams('com_pizzabox');

        $session = & JFactory::getSession();
        $date = $session->get('com_pizzabox.delivery.date');
        $time = $session->get('com_pizzabox.delivery.time');
        $order_id = $session->get('com_pizzabox.order.id');
        $this->assignRef('order_id', $order_id);

        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/orders.php' );
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

        if ($tpl == 'delivery_name') {
            $user_id = JFactory::getUser()->id;
            $addresses = $this->getModel('addresses')->getAllByUser($user_id);

            $this->assignRef('datets', strtotime($date));
            $this->assignRef('time', $time);
            $this->assignRef('user_id', $user_id);
            $this->assignRef('addresses', $addresses);
        }
        else if ($tpl == 'delivery_time') {
            $times = $this->getTimesList($date);
            $datets = strtotime($date);
            $this->assignRef('datets', $datets);
            $this->assignRef('times', $times);
        }
        else if ($tpl == 'delivery_date') {
            $dates = $this->getDatesList();
            $this->assignRef('dates', $dates);
        }
//        else {
//            $this->order_total = $orderModel->getTotal();
//        }

        // Load order template from common folder
        $this->addTemplatePath(JPATH_COMPONENT_SITE . '/layouts');
        $this->setLayout('');
        $sublayout['order_details'] = $this->loadTemplate('order_details');
        $this->assignRef('sublayout', $sublayout);
        $this->setLayout('default');
        parent::display($tpl);

        if ($tpl == 'confirmed') {
            $session->clear('com_pizzabox.order.id');
            $session->clear('com_pizzabox.container.number');
        }
    }

    public function getDatesList()
    {
        $dates = array();
        $date = new DateTime();

        $next_days = $this->params->get('next_days_number', 7);
        for ($i = 1; $i <= $next_days; $i++) {

            //skip add for first item
            if ($i != 1) {
                $date->modify('+1 days');
            }

            $date_value = $date->format('Y-m-d');

            if (method_exists('DateTime', 'getTimestamp')) {
                $dates[$date_value] = $date->getTimestamp();
            }
            else {
                $dates[$date_value] = $date->format('U');
            }
        }

        return ( $dates );
    }

    public function getTimesList($date)
    {
        $times = array();
        $model = $this->getModel();

        $start_time = new DateTime($this->params->get('delivery_start'));
        $end_time = new DateTime($this->params->get('delivery_end'));
        $interval = $this->params->get('delivery_times_interval');
        $delivery_date = new DateTime($date);

        $times = $this->getSessionTimesList($start_time, $end_time, $interval, $delivery_date);

        // If there is a second delivery session we get another list of times
        if ($this->params->get('second_delivery_start', '') && $this->params->get('second_delivery_end', '')) {
            $second_start_time = new DateTime($this->params->get('second_delivery_start'));
            $second_end_time = new DateTime($this->params->get('second_delivery_end'));
            $times = array_merge($times, $this->getSessionTimesList($second_start_time, $second_end_time, $interval, $delivery_date));
        }

        $orders_per_interval = $model->getOrdersPerInterval($date);
        $this->params = & JComponentHelper::getParams('com_pizzabox');
        $max_orders = $this->params->get('max_orders_per_interval');

        foreach ($times as $key => $time) {
            $datetime = "$date $time:00";
            if (isset($orders_per_interval[$datetime]) && ( $orders_per_interval[$datetime]->orders >= $max_orders )) {
                unset($times[$key]);
            }
        }
        return $times;
    }

    // Given a start / end session time, build all available times for that session depending on interval
    // Also checks if we're not past current session opening time (for Session Mode)
    // or that we have enough time to prepare the order (in Preparation Mode)
    public function getSessionTimesList($start_time, $end_time, $interval, $delivery_date)
    {
        $session_times_list = array();

        // SESSION MODE
        if ($this->params->get('delay_mode', 'S') == 'S') {
            $now = new DateTime();
            // If requested delivery date is today and opening time is already past, session is not available
            if (!( ( $now->format('Y-m-d') == $delivery_date->format('Y-m-d') ) && ( $now->format('H:i') > $start_time->format('H:i') ) )) {
                while ($start_time->format('H:i') < $end_time->format('H:i')) {
                    $session_times_list [] = $start_time->format('H:i');
                    $start_time->modify('+' . $interval . ' minutes');
                }
            }
        }
        else {
            // PREPARATION MODE
            $min_start_time = $this->getMinStartTime();
            while ($start_time->format('H:i') < $end_time->format('H:i')) {
                if ($delivery_date->format('Y-m-d') . ' ' . $start_time->format('H:i') >= $min_start_time->format('Y-m-d H:i')) { // preparation time check
                    $session_times_list [] = $start_time->format('H:i');
                }
                $start_time->modify('+' . $interval . ' minutes');
            }
        }
        return ( $session_times_list );
    }

    /*
     * @return        DateTime object - min delivery time
     * @description   This complicated function tries to calculate delivery delay (preparation time) by taking into
     *                account opening hours only
     */

    public function getMinStartTime()
    {
        // get params
        $delivery_delay = $this->params->get('min_delivery_delay');
        $first_start_time = new DateTime($this->params->get('delivery_start'));
        $first_end_time = new DateTime($this->params->get('delivery_end'));
        $second_start_time = new DateTime($this->params->get('second_delivery_start'));
        $second_end_time = new DateTime($this->params->get('second_delivery_end'));

        // initialize
        $min_start_time = new DateTime();
        $current_session = 1;
        $start_time = clone $first_start_time;
        $end_time = clone $first_end_time;

        while (true) {
            if ($min_start_time->getTimestamp() < $start_time->getTimestamp()) { // before opening time
                $min_start_time = clone $start_time;
                $min_start_time->modify('+' . $delivery_delay . ' minutes');
                if ($min_start_time->getTimestamp() <= $end_time->getTimestamp()) {
                    return $min_start_time;
                }
                else {
                    $delivery_delay = round(( $min_start_time->getTimestamp() - $end_time->getTimestamp() ) / 60);
                    $min_start_time->modify('-' . $delivery_delay . ' minutes');
                }
            }
            else if ($min_start_time->getTimestamp() < $end_time->getTimestamp()) { // after opening time but before end time
                $min_start_time->modify('+' . $delivery_delay . ' minutes');
                if ($min_start_time->getTimestamp() <= $end_time->getTimestamp()) {
                    return $min_start_time;
                }
                else {
                    $delivery_delay = round(( $min_start_time->getTimestamp() - $end_time->getTimestamp() ) / 60);
                    $min_start_time->modify('-' . $delivery_delay . ' minutes');
                }
            }
            if (!( $second_start_time && $second_end_time )) { // There is no second session
                $start_time->modify('+ 1 days');
                $end_time->modify('+ 1 days');
            }
            else {
                if ($current_session == 1) {
                    $start_time = clone $second_start_time;
                    $end_time = clone $second_end_time;
                    $current_session = 2;
                }
                else {
                    $first_start_time->modify('+ 1 days');
                    $first_end_time->modify('+ 1 days');
                    $second_start_time->modify('+ 1 days');
                    $second_end_time->modify('+ 1 days');
                    $start_time = clone $first_start_time;
                    $end_time = clone $first_end_time;
                    $current_session = 1;
                }
            }
            continue;
        }
    }

    // Get available images
    public function getElementImage($elements_type, $id)
    {
        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        $class_name = "PizzaboxModel" . $elements_type;
        $model = new $class_name();
        $model->setId($id);
        $element = $model->getItem();
        return ( $element['row']->image );
    }

}
