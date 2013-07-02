<?php

/**
 * @version		    controllers/delivery.php 2013-07-02 20:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.controller');

/**
 * PizzaboxControllerDelivery
 */
class PizzaboxControllerDelivery extends JControllerLegacy
{
	public $_controllerUrl = '';
	public $_model = NULL;

	public function __construct($default = array())
	{
		if (!JRequest::getCmd('view')) {
			JRequest::setVar('view', 'delivery');
		}

		parent::__construct($default);

		$this->_model = & $this->getModel('delivery');
		$this->_model_address = & $this->getModel('addresses');
		$this->_controllerUrl = 'index.php?option=com_pizzabox&controller=delivery';
	}

	public function display($tpl = 'delivery_date')
	{
		$params =& JComponentHelper::getParams('com_pizzabox');
		if ($params->get('registered_users_only', 1) == 1) {
			$user = JFactory::getUser();
			if ($user->guest) {
				$msg = JText::_('PIZZABOX_REGISTERED_ONLY');
				$link = JRoute::_('index.php?option=com_users&task=register', false);
				$this->setRedirect($link, $msg, 'error');
			}
		}

		$session = JFactory::getSession();

		if ($tpl != 'confirmed') {
			$order_id = $session->get('com_pizzabox.order.id');
			if (!$order_id) {
				echo "No order selected, cannot proceed";
				return false;
			}
			if ($params->get('time_enabled', 1) == 0) {
				$session->set('com_pizzabox.delivery.date', '');
				$session->set('com_pizzabox.delivery.time', '');
				$tpl = 'delivery_name';
			}
		}

		$element = JRequest::getWord('element');
		$delivery_date = JRequest::getVar('delivery_date', '');
		$delivery_time = JRequest::getVar('delivery_time', '');

		if (( $element == 'delivery_date' ) && $delivery_date) {
			$session->set('com_pizzabox.delivery.date', $delivery_date);
			$tpl = 'delivery_time';
		}

		if (( $element == 'delivery_time' ) && $delivery_time) {
			$session->set('com_pizzabox.delivery.time', $delivery_time);
			$tpl = 'delivery_name';
		}

		$view =& $this->getView('delivery', 'html');
		$view->setModel($this->_model, true);
		$view->setModel($this->_model_address);
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
			$address_id = JRequest::getInt('old_address');
			if ($address_id != 0 && $this->_model_address->isAddressValid($address_id)) {
				$this->_model_address->linkTo($result, $address_id);
			}
			else {
				$this->_model_address->createAndLink($result);
			}

			$this->emailNotification();
			$msg = JText::_('PIZZABOX_SAVE_SUCCESS');
			$this->display('confirmed');
		}
	}

	public function emailNotification()
	{
		$params = & JComponentHelper::getParams('com_pizzabox');
		if ($params->get('email_notification', 0) == 1) {
			$session = & JFactory::getSession();
			$order_id = $session->get('com_pizzabox.order.id');
			require_once( JPATH_COMPONENT . '/helpers/pizzabox.php');
			$this->helper = new PizzaboxHelper();
			$this->helper->emailNotification($order_id);
		}
	}

	public function selectdate()
	{
		$session = JFactory::getSession();
		$order_id = $session->get('com_pizzabox.order.id');
		if (!$order_id) {
			echo "No order selected, cannot proceed";
			return false;
		}
		else {
			$this->display('delivery_date');
		}
	}

	public function selecttime()
	{
		$session = JFactory::getSession();
		$order_id = $session->get('com_pizzabox.order.id');
		if (!$order_id) {
			echo "No order selected, cannot proceed";
			return false;
		}
		else {
			$this->display('delivery_time');
		}
	}

	public function leave()
	{
		$order_id = JRequest::getInt('id');
		JRequest::setVar('cid', array($order_id));
		require_once ( JPATH_COMPONENT_ADMINISTRATOR.'/models/orders.php' );
		$order_model = new PizzaboxModelOrders();
		$order_model->remove();

		$session = JFactory::getSession();
		$session->clear('com_pizzabox.order.id');

		$this->setRedirect(JRoute::_('index.php'));
	}

	public function removerow() {
		$order_id = JFactory::getSession()->get('com_pizzabox.order.id');
		$container_number = JRequest::getInt('container');

		if ($this->_model->removeContainer($order_id, $container_number)) {
			echo "Ok";
			jexit();
		}
		else {
			echo "Error";
			jexit();
		}
	}
}
