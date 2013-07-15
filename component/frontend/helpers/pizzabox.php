<?php

/**
 * @version			  frontend/helpers/pizzabox.php 2013-07-07 19:56:00Z zanardi
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic <info@gibilogic.com>
 * @authorUrl		  http://www.gibilogic.com
 * @copyright		  Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut');

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
        if (!$price)
            return;

        if ($this->params->get('currency_symbol_position', 'before') == 'before') {
            return $this->params->get('currency_symbol') . " " . sprintf("%.2f", $price);
        }
        else {
            return sprintf("%.2f", $price) . " " . $this->params->get('currency_symbol');
        }
    }

    public function emailNotification($order_id)
    {
        require_once( JPATH_COMPONENT_ADMINISTRATOR . '/models/orders.php');
        $model = new PizzaboxModelOrders();
        $model->setId($order_id);
        $order = $model->getItem();

        $mailer = JFactory::getMailer();

        // Sender
        $config = JFactory::getConfig();
        $mailer->setSender($config->get('mailfrom', 'pizzabox@gibilogic.com'));

        // Recipient(s)
        $recipient_email = $this->params->get('email_address', '');
        if ($recipient_email) {
            $mailer->addRecipient($recipient_email);
        }
        else {
            foreach ($this->_getSystemEmailAddresses() as $recipient) {
                $mailer->addRecipient($recipient->email, $recipient->name);
            }
        }

        // Subject and body
        $link = JURI::root() . "/index.php?option=com_pizzabox&controller=orders&task=edit&id=$order_id";
        $mailer->setSubject(JText::_('PIZZABOX_EMAIL_NOTIFICATION_SUBJECT'));
        $mailer->setBody('<html><body>' . JText::sprintf('PIZZABOX_EMAIL_NOTIFICATION_BODY', $link) . '</body></html>');

        // Send message
        $mailer->IsHTML(true);
        $mailer->send();
    }

    protected function _getSystemEmailAddresses()
    {
        $recipients = array();
        $query = "SELECT `name`, `email` " .
                "FROM `#__users` " .
                "WHERE `sendEmail` = '1'";
        $db = & JFactory::getDBO();
        $db->setQuery($query);
        if ($result = $db->loadObjectList()) {
            $recipients = $result;
        }
    }

}
