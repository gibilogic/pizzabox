<?php

/**
 * @version		    controllers/orders.php 2013-07-02 20:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.controller');

class PizzaboxControllerOrders extends JControllerLegacy
{
	public $_controllerUrl = '';
	public $_model = NULL;

	public function __construct( $default = array() )
	{
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'orders' );
		}

		parent::__construct( $default );

		$this->registerTask( 'setStatus1', 'setStatus' );
		$this->registerTask( 'setStatus2', 'setStatus' );
		$this->registerTask( 'setStatus3', 'setStatus' );
		$this->registerTask( 'setStatus4', 'setStatus' );
		$this->registerTask( 'setStatus5', 'setStatus' );
		$this->registerTask( 'setStatus6', 'setStatus' );
		$this->registerTask( 'setStatus7', 'setStatus' );
		$this->registerTask( 'setStatus8', 'setStatus' );
		$this->registerTask( 'setStatus9', 'setStatus' );

		require_once ( JPATH_COMPONENT_ADMINISTRATOR.'/models/orders.php' );
		$this->_model = new PizzaboxModelOrders();
		$this->_controllerUrl = 'index.php?option=com_pizzabox&controller=orders';
    $this->_deliveryControllerUrl = 'index.php?option=com_pizzabox&controller=delivery';
	}

	public function display( $tpl=null )
	{
    $params =& JComponentHelper::getParams('com_pizzabox');
    if( $params->get('registered_users_only',1) == 1 ) {
      $user = JFactory::getUser();
      if ( $user->guest ) {
        $msg = JText::_('PIZZABOX_REGISTERED_ONLY');
        $link = JRoute::_('index.php?option=com_users&task=register', false);
        $this->setRedirect ( $link, $msg, 'error' );
      }
    }

		if ( JRequest::getVar('change_status') == TRUE ) {
			$this->changeStatus();
		}

		$view = & $this->getView('orders', 'html');
		$view->setModel($this->_model, true);
		$view->display($tpl);
	}

	public function edit()
	{
		if ( $this->_model->checkout() ){
			$this->display('form');
		} else {
			$this->_setDefaultRedirect();
		}
	}

	public function changeStatus()
	{
		JRequest::checkToken() or die( 'PIZZABOX_INVALID_TOKEN' );

		if ( $this->_model->changeStatus() ) {
			$msg = JText::sprintf('PIZZABOX_CHANGE_STATUS_SUCCESS', count($this->_model->getCid()));
			$this->setRedirect( $this->_controllerUrl, $msg );
		} else {
			$msg = JText::_('PIZZABOX_CHANGE_STATUS_ERROR');
			$this->setRedirect( $this->_controllerUrl, $msg, 'error' );
		}
	}

	public function setStatus()
	{
		$task = JRequest::getVar('task');
		$status_id = str_replace('setStatus','',$task);
		JRequest::setVar('new_status', $status_id);
		$this->changeStatus();
	}

	public function save()
	{
		JRequest::checkToken() or die( 'PIZZABOX_INVALID_TOKEN' );
		$result = $this->_model->save();
		if ( false === $result ) {
		  	$msg = JText::_( 'PIZZABOX_SAVE_ERROR' );
		  	$type = 'error';
		} else {
			$msg = JText::_( 'PIZZABOX_SAVE_OK' );
			$type = '';
		}
		$this->setRedirect( $this->_controllerUrl  , $msg, $type );
	}

	public function remove()
	{
		JRequest::checkToken() or die( 'PIZZABOX_INVALID_TOKEN' );

		if ( $this->_model->remove() ) {
			$msg = JText::sprintf('PIZZABOX_REMOVE_SUCCESS', count($this->_model->getCid()));
			$this->setRedirect( $this->_controllerUrl, $msg );
		} else {
			$msg = JText::_('PIZZABOX_REMOVE_ERROR');
			$this->setRedirect( $this->_controllerUrl, $msg, 'error' );
		}
	}

  public function repeat()
  {
    $id = JRequest::getInt('id');
    if(! $id ) {
      $msg = JText::_('PIZZABOX_REPEAT_ERROR_NO_ID');
      $this->setRedirect( $this->_controllerUrl, $msg, 'error' );
      return;
    }
    $this->_model->setId( $id );
    $new_id = $this->_model->copy();
    if(! $new_id ) {
      $msg = JText::_('PIZZABOX_REPEAT_ERROR_COPY');
      $this->setRedirect( $this->_controllerUrl, $msg, 'error' );
    } else {
      // set default status and empty delivery for new order
      $this->_model->setId( $new_id );
      $this->_model->setDefaultStatus();
      $this->_model->setEmptyDelivery();

      // set session values
      $session = JFactory::getSession();
      $session->set( 'com_pizzabox.order.id', $new_id );

      // redirect to delivery date selection
      $msg = JText::_('PIZZABOX_REPEAT_OK');
      $this->setRedirect( $this->_deliveryControllerUrl, $msg );
    }
  }
}
