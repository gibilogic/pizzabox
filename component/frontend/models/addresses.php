<?php

/**
 * @version			  $Id: models/addresses.php 2013-03-22 09:00:00Z matteo $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut');
jimport('joomla.application.component.model');

class PizzaboxModelAddresses extends JModel
{
	private $_error = '';
	private $_lastId = 0;

	public function getId()
	{
		if ($this->_lastId > 0) {
			return $this->_lastId;
		}
		$cid = $this->getCid();
		if (empty($cid[0])) {
			return JRequest::getInt('id', 0);
		}
		return $cid[0];
	}

	public function setId($id)
	{
		$this->_lastId = $id;
	}

	public function getCid()
	{
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		JArrayHelper::toInteger($cid, array(0));
		return $cid;
	}

	public function &getRow()
	{
		$table =& $this->getTable();
		$table->load($this->getId());
		return $table;
	}

	public function getAllByUser($user_id) {
		$query = "SELECT * FROM #__pizzabox_addresses WHERE published = 1 AND user_id = $user_id";

		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$results = $db->loadObjectList('id');

		return $results ? $results : array();
	}

  public function create()
	{
		$row =& $this->getRow();

		if (!empty($row->id)) {
			$isNew = false;
		}
		else {
			$isNew = true;
		}

		$address_data = JRequest::getVar('address');
		if (!$address_data || count($address_data) == 0) {
			return false;
		}
		foreach ($address_data as $key => $value) {
			if (!is_string($value) || $value == '') {
				return false;
			}
		}

		if (!$row->bind($address_data)) {
			JError::raiseWarning(200, $row->getError());
			return false;
		}

		if ($isNew) {
			$row->ordering = $row->getNextOrder();
		}

		if (!$row->store()) {
			JError::raiseWarning(200, $row->getError());
			return false;
		}

		$row->checkin();
		$this->_lastId = $row->id;
		return true;
	}

	public function createAndLink($order_id) {
		if ($this->create()) {
			$last_id = $this->_lastId;
			$query = "UPDATE #__pizzabox_orders SET address_id = $last_id WHERE id = $order_id";

			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$db->execute();
		}
	}
}
