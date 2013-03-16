<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version			  $Id: views/orders/view.html.php 2012-08-17 09:23:00Z zanardi$
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */ 

jimport( 'joomla.application.component.view');

class PizzaboxViewOrders extends JView
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
		$context = 'com_pizzabox.orders.';
		
		// get filter and search status
		$filters['user']				= $app->getUserStateFromRequest( $context.'filter_user', 'filter_user', '', 'int' );
		$filters['status']				= $app->getUserStateFromRequest( $context.'filter_status', 'filter_status', '', 'int' );
		$filters['order_date_from']		= $app->getUserStateFromRequest( $context.'filter_order_date_from', 'filter_order_date_from', '', 'string' );
		$filters['order_date_to']		= $app->getUserStateFromRequest( $context.'filter_order_date_to', 'filter_order_date_to', '', 'string' );
		$filters['delivery_date_from']	= $app->getUserStateFromRequest( $context.'filter_delivery_date_from', 'filter_delivery_date_from', '', 'string' );
		$filters['delivery_date_to']	= $app->getUserStateFromRequest( $context.'filter_delivery_date_to', 'filter_delivery_date_to', '', 'string' );
		$filters['order']				= $app->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'id', 'cmd' );
		$filters['order_Dir']			= $app->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', 'desc', 'word' );
		$search							= $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
		$search 						= JString::strtolower($search);
		
		// sanitize $filter_order
		if (!in_array($filters['order'], array('ordering','id'))) {
			$filters['order'] = 'ordering';
		}
		
		$model = $this->getModel();
		$items = $this->get('items');
		
		$javascript = 'onchange="document.adminForm.submit();"';

		// get users list
		$users = $model->getUsersList();

		$list = array();
		$list[] = JHTML::_('select.option',  '', '-- '. JText::_( 'JGLOBAL_USERNAME' ) .' --', 'id', 'name' );
		$list = array_merge( $list, $users );
		$lists['users'] = $list;
		
		$filters['user'] = JHTML::_('select.genericlist', $list, 'filter_user', $javascript, 'id', 'name', $filters['user'] );
		
		// get status list
		$status = $this->getList('status');
		
		$list = array();
		$list[] = JHTML::_('select.option',  '', '-- '. JText::_( 'PIZZABOX_STATUS_SELECT' ) .' --', 'id', 'name' );
		$list = array_merge( $list, $status );
		
		$filters['status'] = JHTML::_('select.genericlist', $list, 'filter_status', $javascript, 'id', 'name', $filters['status'] );
		$lists['status'] = JHTML::_('select.genericlist', $list, 'new_status', '', 'id', 'name', '' );
		
		foreach ( $items['rows'] as &$item ) {
			if ( $item->user_id ) { 
				$item->user = $users[$item->user_id]->name; 
			} else {
				$item->user = '';
			}
			
      $model->setId( $item->id );
      $item->order_total = $model->getTotal();
      
			if ( $item->status_id && isset( $status[$item->status_id] )) { 
				$item->status = $status[$item->status_id]->name; 
				$item->lock = $status[$item->status_id]->lock; 
			} else {
				$item->status = '--';
				$item->lock = 0;
			}
		}
		
		$this->assignRef( 'filters', $filters );
		$this->assignRef( 'lists', $lists );
		$this->assign( $items );
		$this->pagination = new JPagination($items['total'], $items['limitstart'], $items['limit']);

    JToolBarHelper::title( JText::_('PIZZABOX_ORDERS' ), 'pizzabox' );
    JToolBarHelper::publish();
    JToolBarHelper::unpublish();
    JToolBarHelper::customX( 'changestatus', 'edit', '', 'PIZZABOX_STATUS_CHANGE' );
    JToolBarHelper::editList();
    JToolBarHelper::deleteList( JText::_( 'PIZZABOX_CONFIRM_DELETE' ) );
    JToolBarHelper::preferences('com_pizzabox', '600');
	}

	function editItem()
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		$item =& $this->get('item');
		
		$model = $this->getModel();
		$users = $model->getUsersList();
		if ( $item['row']->user_id != 0 ) {;
			$item['row']->user = $users[$item['row']->user_id]->name;
		} else {
			$item['row']->user = '';
		}
		
    $model->setId( $item['row']->id );
    $item['row']->order_total = $model->getTotal();
    
		$status = $this->getList('status');
		
		$list = array();
		$list[] = JHTML::_('select.option',  '', '-- '. JText::_( 'PIZZABOX_STATUS_SELECT' ) .' --', 'id', 'name' );
		$list = array_merge( $list, $status );
		$lists['status'] = JHTML::_('select.genericlist', $list, 'status_id', '', 'id', 'name', $item['row']->status_id );	
		
		$model->setId( $item['row']->id );
		$parts = $model->getParts();
		
		$this->assign( $item );
		$this->assignRef( 'parts', $parts );
		$this->assignRef( 'lists', $lists );
		
    JToolBarHelper::title( JText::_('PIZZABOX_ORDER') . ' ' . $item['row']->id, 'pizzabox' );
    JToolBarHelper::save();
    JToolBarHelper::apply();
    JToolBarHelper::cancel();
	}
	
	function getList( $elements_type ) 
	{
		require_once ( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.$elements_type.'.php' );
		$class_name = "PizzaboxModel" . $elements_type;
		$model = new $class_name();
		$elements = $model->getItems();
		return ( $elements['rows'] );
	}
	
}
