<?php

/**
 * @version		    frontend/models/parts.php 2013-07-07 19:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.model');

/**
 * PizzaBoxModelParts
 */
class PizzaboxModelParts extends JModelLegacy
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
		$db = & JFactory::getDBO();
		$query = 'SELECT `id`, `name` FROM #__pizzabox_parts';
		$db->setQuery($query);
		$html_list = $db->loadObjectList('id');
		return $html_list;
	}

	public function getItems()
	{
		$result = array();
		$app = & JFactory::getApplication();
		$session = & JFactory::getSession();
		$context = 'com_pizzabox.parts.';
		$default_limit = $app->getCfg('list_limit');

		$limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $default_limit, 'int');
		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');
		$filter_containers = $session->get('com_pizzabox.container.id');
		$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$order = $this->_db->quote($app->getUserStateFromRequest($context . 'order', 'filter_order', 'ordering', 'cmd'));
		$order_dir = $this->_db->quote($app->getUserStateFromRequest($context . 'order_dir', 'filter_order_Dir', 'asc', 'cmd'));

		$query = 'SELECT * FROM `#__pizzabox_parts` ';

		$where = array();
		$where[] = "`published` = '1'";
		if ($search) {
			$search = $this->_db->quote('%' . trim(strtolower($search)) . '%');
			$where [] = " `name` LIKE $search";
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
		$user = JFactory::getUser();
		$session =& JFactory::getSession();
		$params =& JComponentHelper::getParams('com_pizzabox');

		if ($params->get('registered_users_only', 0) == 1 && $user->guest) {
			return false;
		}

		$order_id = $session->get('com_pizzabox.order.id', 0);
		$container_number = $session->get('com_pizzabox.container.number', 1);

		if ($order_id) {
			$this->_db->setQuery("SELECT COUNT(*) FROM `#__pizzabox_orders` WHERE `id` = '$order_id'");
			if (!$this->_db->loadResult()) {
				return false;
			}

			$container_number++;
		}
		else {
			$date = date('Y-m-d H:i:s');
			$this->_db->setQuery("INSERT INTO #__pizzabox_orders (`user_id`, `status_id`, `datetime`) VALUES ('$user->id', '0', '$date')");
			$this->_db->execute();

			$order_id = $this->_db->insertid();
			$session->set('com_pizzabox.order.id', $order_id);
		}

		$container_id = $session->get('com_pizzabox.container.id');
		$container_name = $this->getElementName('containers', $container_id);

		$values = array();
		foreach (JRequest::getVar('parts', array()) as $part_id) {
			$part_name = $this->getElementName('parts', $part_id);
			foreach (JRequest::getVar('flavours_' . $part_id, array()) as $flavour_id) {
				$flavour_name = $this->getElementName('flavours', $flavour_id);
				$values[] = "('$order_id', '$container_number', '$container_id', '$container_name', '$part_id', '$part_name', '$flavour_id', '$flavour_name')";
			}
		}

		$query = "INSERT INTO #__pizzabox_orders_parts (`order_id`, `container_number`, `container_id`, `container_name`, `part_id`, `part_name`, `flavour_id`, `flavour_name`) VALUES ";
		$query .= join(",", $values);
		$this->_db->setQuery($query);
		$this->_db->execute();

		$session->set('com_pizzabox.container.number', $container_number);
	}

	public function getElementName($element_type, $id)
	{
		if ($element_type == 'parts') {
			$this->setId($id);
			$element = $this->getItem();
			$element_name = $element['row']->name;
		}
		else {
			require_once ( JPATH_COMPONENT_ADMINISTRATOR .  '/models/'  . $element_type . '.php' );
			$class_name = 'PizzaboxModel' . $element_type;
			$model = new $class_name();
			$model->setId($id);
			$element = $model->getItem();
			$element_name = $element['row']->name;
		}
		return ( $element_name );
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

	public function getMinMaxByContainer($container_id, $index = '') {
		$query = "SELECT part_id, minimum, maximum FROM #__pizzabox_containers_parts WHERE container_id = $container_id";
		$this->_db->setQuery($query);

		return $this->_db->loadAssocList($index);
	}
}
