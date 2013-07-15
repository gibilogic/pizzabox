<?php

/**
 * @version		    frontend/models/delivery.php 2013-07-07 19:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.model');

/**
 * PizzaboxModelDelivery
 */
class PizzaboxModelDelivery extends JModelLegacy
{

    public function save()
    {
        $session = & JFactory::getSession();
        $order_id = $session->get('com_pizzabox.order.id');
        $delivery_date = $session->get('com_pizzabox.delivery.date');
        $delivery_time = $session->get('com_pizzabox.delivery.time');
        $delivery_name = JRequest::getVar('delivery_name');
        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:s');

        // Check that order is set and exists in db
        if (!$order_id) {
            return false;
        }
        else {
            $query = "SELECT COUNT(*) FROM `#__pizzabox_orders` WHERE `id` = '$order_id'";
            $this->_db->setQuery($query);
            if (!$this->_db->loadResult()) {
                return false;
            }
        }

        $query = "UPDATE `#__pizzabox_orders` SET " .
                "`datetime` = '$date', " .
                "`delivery` = '$delivery_date $delivery_time', " .
                "`name` = '$delivery_name', " .
                "`status_id` = '1' " .
                "WHERE `id` = '" . $order_id . "' ";
        $this->_db->setQuery($query);
        $this->_db->query();

        return $order_id;
    }

    public function getOrdersPerInterval($date)
    {
        $orders = array();
        $start_date = "$date 00:00:00";
        $end_date = "$date 23:59:59";

        $db = & JFactory::getDBO();
        $query = "SELECT `delivery`, COUNT(`id`) as orders " .
                "FROM #__pizzabox_orders " .
                "WHERE `delivery` > '$start_date' AND `delivery` < '$end_date' " .
                "GROUP BY `delivery` ";
        $db->setQuery($query);
        if ($result = $db->loadObjectList('delivery')) {
            $orders = $result;
        }
        return $orders;
    }

    public function removeContainer($order_id, $container_number)
    {
        $query = "DELETE FROM #__pizzabox_orders_parts WHERE order_id = $order_id AND container_number = $container_number";
        $this->_db->setQuery($query);

        return $this->_db->execute();
    }

}
