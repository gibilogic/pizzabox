<?php

/**
 * @version			  frontend/helpers/pizzabox.php 2014-07-23 09:36:00 UTC zanardi
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic <info@gibilogic.com>
 * @authorUrl		  http://www.gibilogic.com
 * @copyright		  Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
defined('_JEXEC') or die();
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/models/orders.php');

/**
 * PizzaboxHelper
 */
class PizzaboxHelper
{

    public function __construct()
    {
        $this->params = & JComponentHelper::getParams('com_pizzabox');
    }

    public function formatPrice($price)
    {
        if (!is_numeric($price))
        {
            return '';
        }

        if ($this->params->get('currency_symbol_position', 'before') == 'before')
        {
            return $this->params->get('currency_symbol') . " " . sprintf("%.2f", $price);
        }
        else
        {
            return sprintf("%.2f", $price) . " " . $this->params->get('currency_symbol');
        }
    }

    public function emailNotification($order_id)
    {
        $this->params = JComponentHelper::getParams('com_pizzabox');

        require_once( JPATH_COMPONENT_ADMINISTRATOR . '/models/orders.php');
        $model = new PizzaboxModelOrders();
        $model->setId($order_id);
        $order = $model->getItem();

        $mailer = JFactory::getMailer();

        // Sender
        $config = JFactory::getConfig();
        $mailer->setSender($config->get('mailfrom', 'pizzabox@gibilogic.com'));

        // Recipient(s)
        foreach ($this->getAdminRecipients() as $email => $name)
        {
            $mailer->addRecipient($email, $name);
        }
        $user = JFactory::getUser();
        $mailer->addRecipient($user->email, $user->name);

        // Subject and body
        $mailer->setSubject(JText::_('PIZZABOX_EMAIL_NOTIFICATION_SUBJECT'));
        $mailer->setBody('<html><body><p>' . JText::_('PIZZABOX_EMAIL_NOTIFICATION_BODY') . $this->buildOrderBody($order_id) . "</p></body></html>");

        // Send message
        $mailer->IsHTML(true);
        $mailer->send();
    }

    /**
     * Build the order body to include in email notification
     *
     * @param int $order_id
     * @return string
     */
    private function buildOrderBody($order_id)
    {
        if (!$this->params->get("email_includes_order", 0))
        {
            $link = JURI::root() . JRoute::_("index.php?option=com_pizzabox&controller=orders&task=edit&id=$order_id");
            return "<a href=\"$link\">" . JText::_('PIZZABOX_EMAIL_NOTIFICATION_BODY_ORDER_DETAIL') . "</a>";
        }

        $orderModel = new PizzaboxModelOrders();
        $orderModel->setId($order_id);
        $this->order = $orderModel->getItem();
        $parts = $orderModel->getParts();

        foreach ($parts as &$part)
        {
            $part->container_image = JURI::root() . $this->getElementImage('containers', $part->container_id);
            $part->part_image = JURI::root() . $this->getElementImage('parts', $part->part_id);
            $part->flavour_image = JURI::root() . $this->getElementImage('flavours', $part->flavour_id);
        }

        $this->order_total = $orderModel->getTotal();
        $this->parts = $parts;
        $this->orderData = $this->convertOrderRows($parts);
        $this->tpl = "confirmed";
        $this->helper = new PizzaboxHelper();

        ob_start();
        include JPATH_COMPONENT_SITE . '/layouts/_order_details.php';
        return "<div>".ob_get_clean()."</div>";
    }

    /**
     * Get all admin recipients which should receive the notification
     */
    private function getAdminRecipients()
    {
        if (!$this->params->get('email_notification', 0))
        {
            return array();
        }

        $recipients = array();
        if ($this->params->get('email_address', ''))
        {
            $recipients[$this->params->get('email_address')] = $this->params->get('email_address');
        }
        else
        {
            foreach ($this->_getSystemEmailAddresses() as $recipient)
            {
                $recipients[$recipient->email] = $recipient->name;
            }
        }

        return $recipients;
    }

    public function convertOrderRows($rows)
    {
        $results = array();

        foreach ($rows as $row)
        {
            if (!array_key_exists($row->container_number, $results))
            {
                $results[$row->container_number] = array(
                    'id' => $row->container_id,
                    'name' => $row->container_name,
                    'image' => empty($row->container_image) ? null : $row->container_image,
                    'rowspan' => 0,
                    'parts' => array()
                );
            }

            if (!array_key_exists($row->part_id, $results[$row->container_number]['parts']))
            {
                $results[$row->container_number]['parts'][$row->part_id] = array(
                    'id' => $row->part_id,
                    'name' => $row->part_name,
                    'image' => empty($row->part_image) ? null : $row->part_image,
                    'flavours' => array()
                );
            }

            if (!array_key_exists($row->flavour_id, $results[$row->container_number]['parts'][$row->part_id]['flavours']))
            {
                $results[$row->container_number]['rowspan'] += 1;
                $results[$row->container_number]['parts'][$row->part_id]['flavours'][$row->flavour_id] = array(
                    'id' => $row->flavour_id,
                    'name' => $row->flavour_name,
                    'image' => empty($row->flavour_image) ? null : $row->flavour_image,
                    'count' => 0
                );
            }

            $results[$row->container_number]['parts'][$row->part_id]['flavours'][$row->flavour_id]['count'] += 1;
        }

        return $results;
    }

    protected function _getSystemEmailAddresses()
    {
        $recipients = array();
        $query = "SELECT `name`, `email` " .
                "FROM `#__users` " .
                "WHERE `sendEmail` = '1'";
        $db = & JFactory::getDBO();
        $db->setQuery($query);
        if ($result = $db->loadObjectList())
        {
            $recipients = $result;
        }
    }

    /**
     * Get image for a given element type and id
     *
     * @param string $elements_type
     * @param int $id
     * @return string
     */
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
