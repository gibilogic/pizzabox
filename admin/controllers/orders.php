<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: controllers/orders.php 2012-08-14 13:26:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

require_once('abstract.php');

class PizzaboxControllerOrders extends PizzaboxControllerAbstract
{
	function display()
	{
		if ( JRequest::getVar('change_status') == TRUE ) {
			$this->changeStatus();
		}
		$this->_display();
	}

	function changeStatus()
	{
		JRequest::checkToken() or die( 'Invalid Token' );
		
		if ( $this->_model->changeStatus() ) {
			$msg = JText::sprintf('Successfully changed %s status', count($this->_model->getCid()));
			$this->setRedirect( $this->_controllerUrl, $msg );
		} else {
			$this->_setDefaultRedirect();
		}			
	}
}
