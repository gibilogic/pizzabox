<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version			  $Id: views/schemes/view.html.php 2012-09-09 16:04:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */ 

jimport( 'joomla.application.component.view');

class PizzaboxViewSchemes extends JView
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
		$context = 'com_pizzabox.schemes.';
		
		// get filter and search status
		$filter_containers = $app->getUserStateFromRequest( $context.'filter_containers', 'filter_containers', '', 'int' );
		$filters['order'] = $app->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'ordering', 'cmd' );
		$filters['order_Dir'] = $app->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd' );
		$search = $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
		$search = JString::strtolower($search);
		
		// sanitize $filter_order
		if (!in_array($filters['order'], array('name','published','ordering','id','container_name'))) {
			$filters['order'] = 'ordering';
		}
		
		$model = $this->getModel();
		$items = $this->get('items');
		
		$javascript = 'onchange="document.adminForm.submit();"';

		// get containers list
		require_once ( JPATH_COMPONENT.DS.'models'.DS.'containers.php' );
		$container_model = new PizzaboxModelContainers();
		$containers_list = $container_model->getHtmlList();

		$list = array();
		$list[] = JHTML::_('select.option',  '', '-- '. JText::_( 'PIZZABOX_CONTAINER_SELECT' ) .' --', 'id', 'name' );
		$list = array_merge( $list, $containers_list );
		
		$filters['containers'] = JHTML::_('select.genericlist', $list, 'filter_containers', $javascript, 'id', 'name', $filter_containers );

		foreach ( $items['rows'] as &$item ) {
			$item->container_name = $containers_list[$item->container_id]->name;
		}
		
		$this->assignRef( 'filters', $filters );
		$this->assign( $items );
		$this->pagination = new JPagination($items['total'], $items['limitstart'], $items['limit']);

    JToolBarHelper::title( JText::_( 'PIZZABOX_SCHEMES' ), 'pizzabox' );
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
		
		// get containers list
		require_once ( JPATH_COMPONENT.DS.'models'.DS.'containers.php' );
		$container_model = new PizzaboxModelContainers();
		$containers_list = $container_model->getHtmlList();

		$list = array();
		$list[] = JHTML::_('select.option',  '', '--' . JText::_( 'PIZZABOX_CONTAINER_SELECT' ) . '--', 'id', 'name' );
		$list = array_merge( $list, $containers_list );
		$lists['containers'] = JHTML::_('select.genericlist', $list, 'container_id', '', 'id', 'name', $item['row']->container_id );
		
		$this->assignRef( 'lists', $lists );
		$this->assign( $item );
		
    JToolBarHelper::title( JText::_( 'PIZZABOX_SCHEME' ), 'pizzabox' );
    JToolBarHelper::save() ;
    JToolBarHelper::apply() ;
    JToolBarHelper::cancel() ;
	}
}
