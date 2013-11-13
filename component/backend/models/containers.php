<?php

/**
 * @version		    backend/models/containers.php 2013-07-07 19:35:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaboxModelContainers
 */
class PizzaboxModelContainers extends PizzaboxModelAbstract
{

    public function getHtmlList()
    {
        $query = 'SELECT `id`, `name` FROM #__pizzabox_containers';
        $this->_db->setQuery($query);
        $containers_list = $this->_db->loadObjectList('id');
        return $containers_list;
    }

    public function getItems($enable_limit = true)
    {
        $result = array();
        $app = & JFactory::getApplication();
        $context = 'com_pizzabox.containers.';
        $default_limit = $app->getCfg('list_limit');

        if ($enable_limit) {
            $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $default_limit, 'int');
            $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
        }
        else {
            $limit = 0;
            $limitstart = 0;
        }

        $query = 'SELECT * FROM `#__pizzabox_containers` ';
        $where = '';

        $search = trim(strtolower($app->getUserStateFromRequest($context . 'search', 'search', '', 'string')));
        if ($search) {
            $where .= "WHERE LOWER(`name`) LIKE " . $this->_db->quote('%' . $search . '%');
        }

        list($order, $order_dir) = $this->getOrdering($context);
        $query .= $where . ' ORDER BY `' . $order . '` ' . strtoupper($order_dir);

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
        $this->_db->setQuery('DELETE FROM `#__pizzabox_containers` WHERE id IN (' . $cids . ')');

        if (!$this->_db->query()) {
            JError::raiseError(500, $this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    public function getPartsByContainer($container_id)
    {
        $query = "
			SELECT p.id, p.name, cp.minimum, cp.maximum
			FROM #__pizzabox_parts p
			LEFT JOIN #__pizzabox_containers_parts cp ON p.id = cp.part_id
			WHERE cp.container_id = $container_id";

        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList('id');

        return $list ? $list : array();
    }

    public function addParts($container_id, $parts_ids, $parts_minimums, $parts_maximums)
    {
        $query = "INSERT INTO #__pizzabox_containers_parts (container_id, part_id, minimum, maximum) VALUES";
        foreach ($parts_ids as $key => $value) {
            $query .= sprintf(' (%d, %d, %d, %d),', $container_id, $value, $parts_minimums[$key], $parts_maximums[$key]);
        }

        $this->_db->setQuery(substr($query, 0, -1) . " ON DUPLICATE KEY UPDATE minimum = VALUES(minimum), maximum = VALUES(maximum)");

        return $this->_db->execute();
    }

    public function cleanParts($container_id, $parts_ids)
    {
        if (count($parts_ids) == 0)
        {
            return true;
        }

        $string_ids = implode(',', $parts_ids);
        $query = "DELETE FROM #__pizzabox_containers_parts WHERE container_id = $container_id AND part_id NOT IN ($string_ids)";

        $this->_db->setQuery($query);

        return $this->_db->execute();
    }
}
