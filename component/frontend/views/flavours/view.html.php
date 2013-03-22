<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version		    $Id: views/flavours/view.html.php 2012-09-02 14:14:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */

jimport( 'joomla.application.component.view');

class PizzaboxViewFlavours extends JView

{
	var $pagination = null ;
	var $user = null;

	function display( $tpl = null )
	{
		jimport( 'joomla.html.pagination' );
		JHTML::_('behavior.tooltip');
		$app =& JFactory::getApplication();
		$context 	= 'com_pizzabox.flavours.';
		
		// get filter and search status
		$filters['order']	= $app->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'ordering', 'cmd' );
		$filters['order_Dir']	= $app->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$search	= $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
		$search = JString::strtolower($search);
		
		// sanitize $filter_order
		if (!in_array($filters['order'], array('ordering', 'id'))) {
			$filters['order'] = 'ordering';
		}
		
		$model = $this->getModel();
		$app->setUserState( $context.'order', 'ordering' );
		$app->setUserState( $context.'order_dir', 'asc' );
		$items = $this->get('items');
		$this->assignRef( 'items', $items['rows'] );
		
		$params =& JComponentHelper::getParams('com_pizzabox');
		$this->assignRef('params' , $params );
				
		$document =& JFactory::getDocument();
		$document->addScript( JURI::root().'/components/com_pizzabox/assets/helper.js' );

		parent::display($tpl);
	}
	
	function getHtmlList( $elements_type ) {
		require_once ( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.$elements_type.'.php' );
		$class_name = "PizzaboxModel" . $elements_type;
		$model = new $class_name();
		return ( $model->getHtmlList() );
	}
	
	function getList( $elements_type, $enable_limit = true ) 
	{
		require_once ( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.$elements_type.'.php' );
		$class_name = "PizzaboxModel" . $elements_type;
		$model = new $class_name();
		$elements = $model->getItems( $enable_limit );
		return ( $elements['rows'] );
	}
}
