<?php

/**
 * @version		    $Id: models/flavours.php 2012-08-16 14:10:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
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
		$db =& JFactory::getDBO();
		$query = 'SELECT `id`, `name` FROM #__pizzabox_flavours';
		$db->setQuery( $query );
		$html_list = $db->loadObjectList('id');
		return $html_list;
	}

	public function getItems($enable_limit = true)
	{
		$result = array();
		$app = & JFactory::getApplication();
		$context = 'com_pizzabox.flavours.';
		$default_limit = $app->getCfg('list_limit');

		if ($enable_limit) {
			$limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $default_limit, 'int');
			$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
		}
		else {
			$limit = 0;
			$limitstart = 0;
		}
		$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$order = $this->_db->getEscaped($app->getUserStateFromRequest($context . 'order', 'filter_order', 'ordering', 'cmd'));
		$order_dir = $this->_db->getEscaped($app->getUserStateFromRequest($context . 'order_dir', 'filter_order_Dir', 'asc', 'cmd'));

		$query = 'SELECT * FROM `#__pizzabox_flavours` ';
		$where = '';
		if ($search) {
			$search = $this->_db->getEscaped(trim(strtolower($search)));
			$where .= " WHERE `name` LIKE '%$search%' ";
		}

		$query .= $where . ' ORDER BY ' . $order . ' ' . strtoupper($order_dir);
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
		$this->_db->setQuery('DELETE FROM `#__pizzabox_flavours` WHERE id IN (' . $cids . ')');

		if (!$this->_db->query()) {
			JError::raiseError(500, $this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		$results = parent::_getList($query, $limitstart, $limit);
		foreach ($results as $result) {
			$result->parts_list = json_decode($result->parts ? $result->parts : '[]');
		}

		return $results;
	}

	public function save()
	{
		JRequest::setVar('parts', json_encode(JRequest::getVar('parts_ids')));

		return parent::save();
	}

	public function getListbyPart($part_id) {
		$like = '%"'.$part_id.'"%';
		$this->_db->setQuery("SELECT * FROM `#__pizzabox_flavours` WHERE parts LIKE '$like' ");
		$list = $this->_db->loadObjectList('id');

		return $list ? $list : array();
	}
}
