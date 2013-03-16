<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version			  $Id: views/addresses/view.html.php 2013-03-01 14:27:00Z zanardi$
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */ 

jimport( 'joomla.application.component.view');

class PizzaboxViewAddresses extends JView
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
		$context = 'com_pizzabox.addresses.';
		
		// get filter and search status
		$filter_users = $app->getUserStateFromRequest( $context.'filter_users', 'filter_users', '', 'int' );
		$filters['order']		= $app->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'ordering', 'cmd' );
		$filters['order_Dir']	= $app->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd' );
		$search					= $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
		$search = JString::strtolower($search);
		
		// sanitize $filter_order
		if (!in_array($filters['order'], array('name','published','ordering','id'))) {
			$filters['order'] = 'ordering';
		}
		
		$model = $this->getModel();
		$items = $this->get('items');
		
		$javascript = 'onchange="document.adminForm.submit();"';

		// get users list
		$users_list = $model->getUsersList();
		$list = array();
		$list[] = JHTML::_('select.option',  '', '-- '. JText::_( 'PIZZABOX_USER_SELECT' ) .' --', 'id', 'name' );
		$list = array_merge( $list, $users_list );
		
		$filters['schemes'] = JHTML::_('select.genericlist', $list, 'filter_users', $javascript, 'id', 'name', $filter_users );

		foreach ( $items['rows'] as &$item ) {
			$item->user_name = $users_list[$item->user_id]->name;
		}
		
		$this->assignRef( 'filters', $filters );
		$this->assign( $items );
		$this->pagination = new JPagination($items['total'], $items['limitstart'], $items['limit']);

    JToolBarHelper::title( JText::_( 'PIZZABOX_PARTS' ), 'pizzabox' );
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
		
		$users_list = $this->getUsersList();

		$list = array();
		$list[] = JHTML::_('select.option',  '', '--'. JText::_( 'PIZZABOX_USER_SELECT' ) .'--', 'id', 'name' );
		$list = array_merge( $list, $users_list );
		$lists['users'] = JHTML::_('select.genericlist', $list, 'user_id', '', 'id', 'name', $item['row']->user_id );
		
		$this->assignRef( 'lists', $lists );
		$this->assign( $item );
		
    JToolBarHelper::title( JText::_( 'PIZZABOX_PART' ), 'pizzabox' );
    JToolBarHelper::save() ;
    JToolBarHelper::apply() ;
    JToolBarHelper::cancel() ;
	}
}
