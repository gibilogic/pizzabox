<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version			  $Id: views/default/view.html.php 2012-08-13 14:27:00Z zanardi$
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */ 

jimport( 'joomla.application.component.view');

class PizzaboxViewDefault extends JView
{
	var $pagination = null ;

	function display( $tpl = null )
	{
		JToolBarHelper::title( JText::_('PIZZABOX'), 'pizzabox' );
		JToolBarHelper::preferences('com_pizzabox', '600');
		parent::display($tpl);
	}
}
