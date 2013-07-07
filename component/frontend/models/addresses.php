<?php

/**
 * @version		    frontend/models/addresses.php 2013-07-07 19:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.model');

/**
 * PizzaBoxModelAddresses
 */
class PizzaboxModelAddresses extends JModelLegacy
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
        $table = & $this->getTable();
        if ($table->load($this->getId())) {
            return $table;
        }
        else {
            return false;
        }
    }

    public function getAllByUser($user_id)
    {
        $query = "SELECT * FROM #__pizzabox_addresses WHERE published = 1 AND user_id = $user_id";

        $db = & JFactory::getDBO();
        $db->setQuery($query);
        $results = $db->loadObjectList('id');

        return $results ? $results : array();
    }

    public function create()
    {
        $row = & $this->getRow();

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

    public function isAddressValid($address_id)
    {
        $this->setId($address_id);
        $address = $this->getRow();
        if (false === $address) {
            return false;
        }

        return $address->user_id == JFactory::getUser()->id;
    }

    public function createAndLink($order_id)
    {
        if ($this->create()) {
            return $this->linkTo($order_id, $this->_lastId);
        }
    }

    public function linkTo($order_id, $address_id)
    {
        $query = "UPDATE #__pizzabox_orders SET address_id = $address_id WHERE id = $order_id";

        $db = & JFactory::getDBO();
        $db->setQuery($query);
        return $db->execute();
    }

}
