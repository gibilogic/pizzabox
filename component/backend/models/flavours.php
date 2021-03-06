<?php

/**
 * @version       backend/models/flavours.php 2013-12-08 11:55:00 UTC zanardi
 * @package       GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright     (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license       GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaboxModelFlavours
 */
class PizzaboxModelFlavours extends PizzaboxModelAbstract
{
    public function getHtmlList()
    {
        $db = & JFactory::getDBO();
        $query = 'SELECT `id`, `name` FROM #__pizzabox_flavours';
        $db->setQuery($query);
        $html_list = $db->loadObjectList('id');
        return $html_list;
    }

    public function getItems($enable_limit = true, $published_only = false)
    {
        $result = array();
        $app = JFactory::getApplication();
        $context = 'com_pizzabox.flavours.';
        $default_limit = $app->getCfg('list_limit');

        if ($enable_limit)
        {
            $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $default_limit, 'int');
            $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
        }
        else
        {
            $limit = 0;
            $limitstart = 0;
        }

        $query = $this->_db->getQuery(true)->select("*")->from("`#__pizzabox_flavours`");

        $search = trim(strtolower($app->getUserStateFromRequest($context . 'search', 'search', '', 'string')));
        if ($search) {
            $query->where("LOWER(`name`) LIKE " . $this->_db->quote('%' . $search . '%'));
        }
        if ($published_only) {
            $query->where("`published` = 1");
        }

        list($order, $order_dir) = $this->getOrdering($context);
        $query->order('`' . $order . '` ' . strtoupper($order_dir));

        $rows = $this->_getList($query, $limitstart, $limit);
        if (!$this->_db->getErrorNum())
        {

            $result['limitstart'] = $limitstart;
            $result['limit'] = $limit;
            $result['total'] = $this->_getListCount($query);
            $result['rows'] = $rows;
            $result['search'] = $search;
            $result['order'] = $order;
            $result['order_dir'] = $order_dir;
        }
        else
        {
            JError::raiseWarning(200, $this->_db->getErrorMsg());
            return false;
        }

        return $result;
    }

    public function remove()
    {
        $cid = $this->getCid();
        $cids = implode(',', $cid);
        $this->_db->setQuery('DELETE FROM `#__pizzabox_flavours` WHERE id IN (' . $cids . ')');

        if (!$this->_db->query())
        {
            JError::raiseError(500, $this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    protected function _getList($query, $limitstart = 0, $limit = 0)
    {
        $results = parent::_getList($query, $limitstart, $limit);
        foreach ($results as $result)
        {
            $result->parts_list = json_decode($result->parts ? $result->parts : '[]');
        }

        return $results;
    }

    public function save()
    {
        $parts_ids = JRequest::getVar('parts_ids');
        JRequest::setVar('parts', isset($parts_ids) ? json_encode($parts_ids) : '[]');

        return parent::save();
    }

    public function getListbyPart($part_id)
    {
        $like = '%"' . $part_id . '"%';
        $this->_db->setQuery("SELECT * FROM `#__pizzabox_flavours` WHERE parts LIKE '$like' ");
        $list = $this->_db->loadObjectList('id');

        return $list ? $list : array();
    }

}
