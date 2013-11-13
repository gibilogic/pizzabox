<?php

/**
 * @version		    backend/models/status.php 2013-07-07 19:33:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaBoxModelStatus
 */
class PizzaboxModelStatus extends PizzaboxModelAbstract
{

    public function getItems($enable_limit = true)
    {
        $result = array();
        $app = & JFactory::getApplication();
        $context = 'com_pizzabox.status.';
        $default_limit = $app->getCfg('list_limit');

        if ($enable_limit) {
            $limit = $this->getState('limit');
            $limitstart = $this->getState('limitstart');
        }
        else {
            $limit = 0;
            $limitstart = 0;
        }
        $search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
        $order = $app->getUserStateFromRequest($context . 'order', 'filter_order', 'ordering', 'cmd');
        $order_dir = $app->getUserStateFromRequest($context . 'order_dir', 'filter_order_Dir', 'asc', 'cmd');
        if (!in_array($order_dir, array('asc', 'desc')))
        {
            $order_dir = 'asc';
        }

        $query = 'SELECT * FROM `#__pizzabox_status` ';
        $where = '';
        if ($search) {
            $search = $this->_db->quote('%' . trim(strtolower($search)) . '%');
            $where .= " WHERE `name` LIKE $search";
        }

        $query .= $where . ' ORDER BY `' . $order . '` ' . strtoupper($order_dir);

        $this->_db->setQuery($query, $limitstart, $limit);
        $rows = $this->_db->loadObjectList('id');

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

    public function save()
    {
        $row = & $this->getRow();

        $isNew = true;
        if (!empty($row->id)) {
            $isNew = false;
        }

        if (!$row->bind(JRequest::get('post'))) {
            JError::raiseWarning(200, $row->getError());
            return false;
        }

        if ($isNew)
            $row->ordering = $row->getNextOrder();

        $lock = JRequest::getVar('lock', FALSE);
        $row->lock = $lock ? 1 : 0;

        if (!$row->check() || in_array(false, $result, true)) {
            JError::raiseWarning(200, $row->getError());
            return false;
        }

        if (!$row->store()) {
            JError::raiseWarning(200, $row->getError());
            return false;
        }

        $row->checkin();

        $this->_lastId = $row->id;

        return true;
    }

    public function remove()
    {
        $cid = $this->getCid();
        $cids = implode(',', $cid);
        $this->_db->setQuery('DELETE FROM `#__pizzabox_status` WHERE id IN (' . $cids . ')');

        if (!$this->_db->query()) {
            JError::raiseError(500, $this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

}
