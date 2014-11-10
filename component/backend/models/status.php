<?php

/**
 * @version		    backend/models/status.php 2014-11-10 17:40:00 UTC zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2014 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v3 or later
 */
defined('_JEXEC') or die();
require_once('abstract.php');

/**
 * PizzaBoxModelStatus
 */
class PizzaboxModelStatus extends PizzaboxModelAbstract
{
    private $valid_ordering_values = array("id", "name", "desc", "ordering");

    public function getItems($enable_limit = true)
    {
        $result = array();
        $app = JFactory::getApplication();
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

        $query = 'SELECT * FROM `#__pizzabox_status` ';
        $where = '';

        $search = trim(strtolower($app->getUserStateFromRequest($context . 'search', 'search', '', 'string')));
        if ($search) {
            $where .= "WHERE LOWER(`name`) LIKE " . $this->_db->quote('%' . $search . '%');
        }

        list($order, $order_dir) = $this->getOrdering($context);
        if ( ! in_array($order, $this->valid_ordering_values))
        {
            $order = 'ordering';
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
