<?php

/**
 * @version		    backend/models/parts.php 2013-07-07 19:34:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaBoxModelParts
 */
class PizzaboxModelParts extends PizzaboxModelAbstract
{

    public function getItems($enable_limit = true)
    {
        $result = array();
        $app = & JFactory::getApplication();
        $context = 'com_pizzabox.parts.';
        $default_limit = $app->getCfg('list_limit');

        $filter_containers = $app->getUserStateFromRequest($context . 'filter_containers', 'filter_containers', '', 'int');
        if ($enable_limit) {
            $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $default_limit, 'int');
            $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
        }
        else {
            $limit = 0;
            $limitstart = 0;
        }

        $query = 'SELECT * FROM `#__pizzabox_parts` ';
        $where = array();

        $search = trim(strtolower($app->getUserStateFromRequest($context . 'search', 'search', '', 'string')));
        if ($search) {
            $where [] = "LOWER(`name`) LIKE " . $this->_db->quote('%' . $search . '%');
        }

        if ($filter_containers) {
            $where [] = "`container_id` = '$filter_containers'";
        }

        if (count($where)) {
            $query .= " WHERE " . join(' AND ', $where);
        }

        list($order, $order_dir) = $this->getOrdering($context);
        $query .= ' ORDER BY `' . $order . '` ' . strtoupper($order_dir);

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
        $this->_db->setQuery('DELETE FROM `#__pizzabox_parts` WHERE id IN (' . $cids . ')');

        if (!$this->_db->query()) {
            JError::raiseError(500, $this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

}
