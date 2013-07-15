<?php

/**
 * @version		    frontend/models/containers.php 2013-07-07 19:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.model');

/**
 * PizzaboxModelContainers
 */
class PizzaboxModelContainers extends JModelLegacy
{

    public $_error = '';
    public $_lastId = 0;

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
        $table->load($this->getId());
        return $table;
    }

    public function getHtmlList()
    {
        $this->_db->setQuery('SELECT `id`, `name` FROM #__pizzabox_containers');
        $html_list = $this->_db->loadObjectList('id');

        return $html_list ? $html_list : array();
    }

    public function getListNotEmpty()
    {
        $this->_db->setQuery('
			SELECT * FROM #__pizzabox_containers c WHERE c.id IN (
				SELECT DISTINCT cp.container_id FROM #__pizzabox_containers_parts cp
			)'
        );
        $result = $this->_db->loadObjectList('id');

        return $result ? $result : array();
    }

    public function getItems()
    {
        $result = array();
        $app = & JFactory::getApplication();
        $context = 'com_pizzabox.containers.';
        $default_limit = $app->getCfg('list_limit');

        $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $default_limit, 'int');
        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
        $search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
        $order = $this->_db->quote($app->getUserStateFromRequest($context . 'order', 'filter_order', 'ordering', 'cmd'));
        $order_dir = $this->_db->quote($app->getUserStateFromRequest($context . 'order_dir', 'filter_order_Dir', 'asc', 'cmd'));

        $query = 'SELECT * FROM `#__pizzabox_containers`';
        $where = array();
        $where [] = "`published` = '1'";
        if ($search) {
            $search = $this->_db->quote(trim(strtolower($search)));
            $where [] = "`name` LIKE '%$search%'";
        }
        if (count($where)) {
            $query .= ' WHERE ' . join(' AND ', $where);
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

    public function getItem()
    {
        $result = array();
        $row = & $this->getRow();
        $result['row'] = $row;
        return $result;
    }

    public function save()
    {
        $element = JRequest::getVar('element');
        $id = JRequest::getInt('containers');

        if (( $element == 'container' ) && $id) {
            $session = JFactory::getSession();
            $session->set('com_pizzabox.container.id', $id);
            return true;
        }
        else {
            return false;
        }
    }

    public function getPrice()
    {
        $price = null;
        $table = $this->getTable();
        $query = "SELECT `price` FROM `" . $table->getTableName() . "` WHERE `id` = '" . $this->_lastId . "'";
        $this->_db->setQuery($query);
        if ($result = $this->_db->loadResult()) {
            $price = $result;
        }
        return $price;
    }

}
