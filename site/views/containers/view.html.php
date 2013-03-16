<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version		    $Id: views/containers/view.html.php 2012-09-02 14:14:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */

jimport( 'joomla.application.component.view');

class PizzaboxViewContainers extends JView

{
	var $pagination = null ;
	var $user = null;

	function display( $tpl = null )
	{
		jimport( 'joomla.html.pagination' );
		JHTML::_('behavior.tooltip');
		$app 		=& JFactory::getApplication();
		$this->user	=& JFactory::getUser();
		$context = 'com_pizzabox.containers.';
		
		// get filter and search status
		$filters['order']		= $app->getUserStateFromRequest( $context.'filter_order', 'filter_order', 'ordering', 'cmd' );
		$filters['order_Dir']	= $app->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$search					= $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
		$search 				= JString::strtolower($search);
		
		// sanitize $filter_order
		if (!in_array($filters['order'], array('ordering', 'id'))) {
			$filters['order'] = 'ordering';
		}
		
		$model = $this->getModel();
		$items = $this->get('items');
		$this->assign( $items );
		$this->pagination = new JPagination($items['total'], $items['limitstart'], $items['limit']);

		$params =& JComponentHelper::getParams('com_pizzabox');
		$this->assignRef('params' , $params );

		parent::display($tpl);
	}
}
