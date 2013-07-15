<?php

/**
 * @version		    backend/models/addresses.php 2013-07-07 19:35:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaboxModelAddresses
 */
class PizzaboxModelAddresses extends PizzaboxModelAbstract
{

    public function getItems($enable_limit = true)
    {
        $result = array();
        $app = & JFactory::getApplication();
        $context = 'com_pizzabox.addresses.';
        $default_limit = $app->getCfg('list_limit');

        $filter_users = $app->getUserStateFromRequest($context . 'filter_users', 'filter_users', '', 'int');
        if ($enable_limit) {
            $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $default_limit, 'int');
            $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
        }
        else {
            $limit = 0;
            $limitstart = 0;
        }
        $search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
        $order = $this->_db->quote($app->getUserStateFromRequest($context . 'order', 'filter_order', 'ordering', 'cmd'));
        $order_dir = $this->_db->quote($app->getUserStateFromRequest($context . 'order_dir', 'filter_order_Dir', 'asc', 'cmd'));

        $query = 'SELECT * FROM `#__pizzabox_addresses` ';
        $where = array();
        if ($search) {
            $search = $this->_db->quote(trim(strtolower($search)));
            $where [] = "`name` LIKE '%$search%'";
        }
        if ($filter_users) {
            $where [] = "`user_id` = '$filter_users'";
        }

        if (count($where)) {
            $query .= " WHERE " . join(' AND ', $where);
        }

        $query .= ' ORDER BY ' . $order . ' ' . strtoupper($order_dir);
        $rows = $this->_getList($query, $limitstart, $limit);

        if (!$this->_db->getErrorNum()) {

            $result['limitstart'] = $limitstart;
            $result['limit'] = $limit;
            $result['total'] = $this->_getListCount($query);
            $result['rows'] = $rows;
            $result['search'] = $search;
            $result['order'] = $order;
            $result['order_dir'] = $order_dir;
        }
        else {
            JError::raiseWarning(200, $this->_db->getErrorMsg());
            return false;
        }

        return $result;
    }

    public function remove()
    {
        $cid = $this->getCid();
        $cids = implode(',', $cid);
        $this->_db->setQuery('DELETE FROM `#__pizzabox_addresses` WHERE id IN (' . $cids . ')');

        if (!$this->_db->query()) {
            JError::raiseError(500, $this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    public function getUsers()
    {
        $users = array();
        $query = "SELECT id, username FROM #__users WHERE published = 1";
        $this->_db->setQuery($query);
        if ($result = $this->_db->loadObjectList()) {
            $users = $result;
        }
        return $users;
    }

}
