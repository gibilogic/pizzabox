<?php

/**
 * @version		    backend/models/abstract.php 2013-07-07 19:35:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.model');

/**
 * PizzaboxModelAbstract
 */
class PizzaboxModelAbstract extends JModelLegacy
{
    protected $_error = '';
    protected $_id = 0;
    protected $_pagination = null;
    protected $_total = null;
    protected $_lastId = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        // Get pagination request variables
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        if ($limit != 0)
        {
            $limitstart = floor($limitstart / $limit) * $limit;
        }
        else
        {
            $limitstart = 0;
        }

        // Set model state
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function getId()
    {
        if ($this->_lastId > 0)
        {
            return $this->_lastId;
        }

        $cid = $this->getCid();

        if (empty($cid[0]))
        {
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

    public function getItem()
    {
        $result = array();
        $row = & $this->getRow();
        $result['row'] = $row;
        return $result;
    }

    public function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_pagination;
    }

    public function getTotal()
    {
        if (empty($this->_total))
        {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    public function getPrice()
    {
        $price = null;
        $table = $this->getTable();
        $query = "SELECT `price` FROM `" . $table->getTableName() . "` WHERE `id` = '" . $this->_lastId . "'";
        $this->_db->setQuery($query);
        if ($result = $this->_db->loadResult())
        {
            $price = $result;
        }
        return $price;
    }

    public function order($inc)
    {
        $row = & $this->getRow();
        $row->move($inc);
    }

    public function publish($bool)
    {
        $cid = $this->getCid();
        $user = & JFactory::getUser();
        $uid = $user->get('id');
        $publish = (int) $bool;

        $table = & $this->getTable();

        if (!$table->publish($cid, $publish, $uid))
        {
            JError::raiseWarning(200, $table->getError());
            return false;
        }

        return true;
    }

    public function checkout()
    {
        $user = JFactory::getUser();
        if ($user->guest)
        {
            return false;
        }

        $row = $this->getRow();
        if ($row->isCheckedOut($user->get('id'), $row->checked_out))
        {
            return false;
        }

        $row->checkout($user->get('id'));

        return true;
    }

    public function save()
    {
        $row = & $this->getRow();

        if (!empty($row->id))
        {
            $isNew = false;
        }
        else
        {
            $isNew = true;
        }

        if (!$row->bind(JRequest::get('post')))
        {
            JError::raiseWarning(200, $row->getError());
            return false;
        }

        if ($isNew)
        {
            $row->ordering = $row->getNextOrder();
        }

        if (!$row->check())
        {
            JError::raiseWarning(200, $row->getError());
            return false;
        }

        if (!$row->store())
        {
            JError::raiseWarning(200, $row->getError());
            return false;
        }

        $row->checkin();
        $this->_lastId = $row->id;
        return true;
    }

    // get a record data and insert it as a new record
    public function copy()
    {
        $item = $this->getItem();
        // abort if record not found
        if ($item['row']->id == 0)
        {
            return false;
        }
        // now create a new record and save it
        $item['row']->id = 0;
        $item['row']->ordering = $item['row']->getNextOrder();
        if (!$item['row']->store())
        {
            return false;
        }
        return $item['row']->id;
    }

    protected function getOrdering($context)
    {
        $app = JFactory::getApplication();

        $order = $app->getUserStateFromRequest($context . 'order', 'filter_order', 'ordering', 'cmd');
        $order_dir = $app->getUserStateFromRequest($context . 'order_dir', 'filter_order_Dir', 'asc', 'cmd');
        if (!in_array($order_dir, array('asc', 'desc')))
        {
            $order_dir = 'asc';
        }

        return array($order, $order_dir);
    }
}
