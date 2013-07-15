<?php
/**
 * @version			  views/parts/view.html.php 2013-07-02 20:52:00Z zanardi
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic <info@gibilogic.com>
 * @authorUrl		  http://www.gibilogic.com
 * @copyright		  (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
 defined('_JEXEC') or die('The way is shut!');

jimport( 'joomla.application.component.view');

class PizzaboxViewParts extends JViewLegacy
{
	public $pagination = null ;
	public $user = null;

	public function display( $tpl = null )
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

	public function listItems()
	{
		jimport( 'joomla.html.pagination' );
		JHTML::_('behavior.tooltip');
		$app =& JFactory::getApplication();
		$context = 'com_pizzabox.parts.';

		// get filter and search status
		$filters['order'] = $app->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'ordering', 'cmd' );
		$filters['order_Dir'] = $app->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd' );
		$search = JString::strtolower($app->getUserStateFromRequest( $context.'search', 'search', '', 'string' ));

		if (!in_array($filters['order'], array('name','published','ordering','id'))) {
			$filters['order'] = 'ordering';
		}
		$this->assignRef( 'filters', $filters );

		$items = $this->get('items');
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

	public function editItem()
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		$item =& $this->get('item');
		$this->assign( $item );

    JToolBarHelper::title( JText::_( 'PIZZABOX_PART' ), 'pizzabox' );
    JToolBarHelper::save() ;
    JToolBarHelper::apply() ;
    JToolBarHelper::cancel() ;
	}

	// This function and the next one should be merged sooner or later
	public function getHtmlList( $elements_type )
	{
		require_once ( JPATH_COMPONENT.'/models/'.$elements_type.'.php' );
		$class_name = "PizzaboxModel" . $elements_type;
		$model = new $class_name();
		return ( $model->getHtmlList() );
	}

	public function getList( $elements_type )
	{
		require_once ( JPATH_COMPONENT.'/models/'.$elements_type.'.php' );
		$class_name = "PizzaboxModel" . $elements_type;
		$model = new $class_name();
		$elements = $model->getItems();
		return ( $elements['rows'] );
	}
}
