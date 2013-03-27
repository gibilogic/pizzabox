<?php

/**
 * @version	      $Id: views/parts/view.html.php 2012-09-02 14:56:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */

defined('_JEXEC') or die('The way is shut!');
jimport('joomla.application.component.view');

/**
 * PizzaboxViewParts
 */
class PizzaboxViewParts extends JView
{
	var $pagination = null;
	var $user = null;

	function display($tpl = null)
	{
		jimport('joomla.html.pagination');
		JHTML::_('behavior.tooltip');
		$app =& JFactory::getApplication();
		$this->user =& JFactory::getUser();
		$context = 'com_pizzabox.parts.';

		// get filter and search status
		$filters['order'] = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering', 'cmd');
		$filters['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');

		if (!in_array($filters['order'], array('ordering', 'id'))) {
			$filters['order'] = 'ordering';
		}
		$app->setUserState($context . 'order', 'ordering');
		$app->setUserState($context . 'order_dir', 'asc');

		$session = JFactory::getSession();
		$ranges = $this->getModel()->getMinMaxByContainer($session->get('com_pizzabox.container.id'));
		$this->ranges = json_encode($ranges);

		$items = $this->get('items');
		foreach ($items['rows'] as &$part) {
			$part->minimum = $ranges[$part->id]['minimum'];
			$part->maximum = $ranges[$part->id]['maximum'];
		}
		$this->assign($items);

		$lists['flavours'] = $this->getList('flavours', false);

		$params =& JComponentHelper::getParams('com_pizzabox');
		$this->assignRef('params', $params);
		$this->assignRef('lists', $lists);

		$document = & JFactory::getDocument();
		$document->addScript(JURI::base() . DS . 'components' . DS . 'com_pizzabox' . DS . 'assets' . DS . 'helper.js');

		parent::display($tpl);
	}

	function getHtmlList($elements_type)
	{
		require_once ( JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS . $elements_type . '.php' );
		$class_name = "PizzaboxModel" . $elements_type;
		$model = new $class_name();
		return ( $model->getHtmlList() );
	}

	function getList($elements_type, $enable_limit = true)
	{
		require_once ( JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS . $elements_type . '.php' );
		$class_name = "PizzaboxModel" . $elements_type;
		$model = new $class_name();
		$elements = $model->getItems($enable_limit);
		return ( $elements['rows'] );
	}
}
