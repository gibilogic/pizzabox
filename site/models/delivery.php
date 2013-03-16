<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: models/delivery.php 2012-09-18 09:11:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

jimport('joomla.application.component.model');

class PizzaboxModelDelivery extends JModel
{
	function save()
	{
		$session =& JFactory::getSession();
		$order_id = $session->get( 'com_pizzabox.order.id'  );
		$delivery_date = $session->get( 'com_pizzabox.delivery.date' );
		$delivery_time = $session->get( 'com_pizzabox.delivery.time' );
		$delivery_name = JRequest::getVar( 'delivery_name' );
		$date = new DateTime();
		$date = $date->format('Y-m-d H:i:s');
		
		// Check that order is set and exists in db
		if ( ! $order_id ) { 
			return false; 
		} else {
			$query = "SELECT COUNT(*) FROM `#__pizzabox_orders` WHERE `id` = '$order_id'";
			$this->_db->setQuery( $query );
			if (! $this->_db->loadResult() ) { return false; }
		}
		
		$query = 	"UPDATE `#__pizzabox_orders` SET " .
					"`datetime` = '$date', ".
					"`delivery` = '$delivery_date $delivery_time', ".
          "`name` = '$delivery_name', ".
					"`status_id` = '1' ".
					"WHERE `id` = '" . $order_id . "' ";
		$this->_db->setQuery( $query );
		$this->_db->query();
	}
	
	function getOrdersPerInterval( $date ) 
	{
		$orders = array();
		$start_date = "$date 00:00:00";
		$end_date = "$date 23:59:59";
		
		$db =& JFactory::getDBO();
		$query = 	"SELECT `delivery`, COUNT(`id`) as orders " . 
					"FROM #__pizzabox_orders " .
					"WHERE `delivery` > '$start_date' AND `delivery` < '$end_date' " .
					"GROUP BY `delivery` ";
		$db->setQuery( $query );
		if ( $result = $db->loadObjectList('delivery') ) {
			$orders = $result;
		}
		return $orders;
	}
}
