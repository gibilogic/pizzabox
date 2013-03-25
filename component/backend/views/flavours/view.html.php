<?php

/**
 * @version			  $Id: views/flavours/view.html.php 2012-08-13 14:27:00Z zanardi$
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.view');

/**
 * PizzaboxViewFlavours
 */
class PizzaboxViewFlavours extends JView
{
	public $pagination = null;
	public $user = null;

	public function display($tpl = null)
	{
		$this->user = & JFactory::getUser();

		switch ($tpl) {
			case 'form':
				$this->editItem();
				break;
			default :
				$this->listIems();
		}
		$params = & JComponentHelper::getParams('com_pizzabox');
		$this->assignRef('params', $params);

		parent::display($tpl);
	}

	public function listIems()
	{
		jimport('joomla.html.pagination');
		JHTML::_('behavior.tooltip');
		$app = & JFactory::getApplication();
		$context = 'com_pizzabox.flavours.';

		// get filter and search status
		$filters['order'] = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering', 'cmd');
		$filters['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
		$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$search = JString::strtolower($search);

		// sanitize $filter_order
		if (!in_array($filters['order'], array('name', 'published', 'ordering', 'id'))) {
			$filters['order'] = 'ordering';
		}

		$items = $this->get('items');
		$this->assign($items);
		$this->pagination = new JPagination($items['total'], $items['limitstart'], $items['limit']);

		JToolBarHelper::title(JText::_('PIZZABOX_FLAVOURS'), 'pizzabox');
		JToolBarHelper::publish();
		JToolBarHelper::unpublish();
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('PIZZABOX_CONFIRM_DELETE'));
		JToolBarHelper::preferences('com_pizzabox', '600');
	}

	public function editItem()
	{
		JRequest::setVar('hidemainmenu', 1);

		$item =& $this->get('item');
		$this->assign($item);

		$partsModel = $this->getModel('parts');
		$this->parts_list = $partsModel->getItems(false);
		$this->selected_parts = $item['row']->parts ? json_decode($item['row']->parts) : array();

		JToolBarHelper::title(JText::_('PIZZABOX_FLAVOUR'), 'pizzabox');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	}

}
