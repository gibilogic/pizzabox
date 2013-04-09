<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version			  $Id: views/containers/view.html.php 2012-08-13 14:34:00Z zanardi$
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

jimport( 'joomla.application.component.view');

class PizzaboxViewContainers extends JView
{
	var $pagination = null ;
	var $user = null;

	function display( $tpl = null )
	{
		$this->user = & JFactory::getUser();

		switch ($tpl) {
			case 'form':
				$this->editItem();
				break;
			default :
				$this->listItems();

		}
		$params =& JComponentHelper::getParams('com_pizzabox');
		$this->assignRef('params' , $params );

		parent::display($tpl);
	}

  function listItems()
	{
		jimport( 'joomla.html.pagination' );
		JHTML::_('behavior.tooltip');
		$app =& JFactory::getApplication();
		$context = 'com_pizzabox.containers.';

		// get filter and search status
		$filters['order']		= $app->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'ordering', 'cmd' );
		$filters['order_Dir']	= $app->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd' );
		$search					= $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
		$search 				= JString::strtolower($search);

		// sanitize $filter_order
		if (!in_array($filters['order'], array('name','published','ordering','id'))) {
			$filters['order'] = 'ordering';
		}

		$model = $this->getModel();
		$items = $this->get('items');
		$this->assign( $items );
		$this->pagination = new JPagination($items['total'], $items['limitstart'], $items['limit']);

    JToolBarHelper::title( JText::_('PIZZABOX_CONTAINERS' ), 'pizzabox' );
    JToolBarHelper::publish();
    JToolBarHelper::unpublish();
    JToolBarHelper::addNew();
    JToolBarHelper::editList();
    JToolBarHelper::deleteList( JText::_( 'PIZZABOX_CONFIRM_DELETE' ) );
		JToolBarHelper::preferences('com_pizzabox', '600');
	}

	function editItem()
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		$item =& $this->get('item');
		$this->assign($item);
		$this->parts = $this->getModel()->getPartsByContainer($item['row']->id);
		$this->parts_list = $this->getModel('parts')->getItems(false);

		$this->parts_table = array();
		foreach ($this->parts_list['rows'] as $part) {
			if (isset($this->parts[$part->id])) {
				$part_data = $this->parts[$part->id];
				$this->parts_table[] = array(
					'id' => $part->id,
					'name' => $part->name,
					'enabled' => true,
					'minimum' => $part_data->minimum,
					'maximum' => $part_data->maximum
				);
			}
			else {
				$this->parts_table[] = array(
					'id' => $part->id,
					'name' => $part->name,
					'enabled' => false,
					'minimum' => 0,
					'maximum' => 0
				);
			}
		}

    JToolBarHelper::title( JText::_('PIZZABOX_CONTAINER' ), 'pizzabox' );
    JToolBarHelper::save() ;
    JToolBarHelper::apply() ;
    JToolBarHelper::cancel() ;
	}

}
