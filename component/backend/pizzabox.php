<?php defined('_JEXEC') or die('Restricted access');
/**
 * @version		$Id: pizzabox.php 2012-09-28 14:22:00Z zanardi $
 * @package		PizzaBox
 * @copyright	Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		GNU/GPLv2
 */

jimport('joomla.application.component.helper');

// load requested controller if exists
$controller = JRequest::getWord('controller','default');
$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

$document =& JFactory::getDocument();
$document->addStyleDeclaration( '.icon-16-pizzabox{ background-image: url("/components/com_pizzabox/assets/icon-16.png") }' );
$document->addStyleDeclaration( '.icon-48-pizzabox{ background-image: url("/components/com_pizzabox/assets/icon-48.png") }' );
$document->addStyleDeclaration( '#lock{ background: none !important;width: auto !important;height: auto !important }' );

JSubMenuHelper::addEntry(JText::_('pizzabox_manage_containers'), 'index.php?option=com_pizzabox&amp;view=containers');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_parts'), 'index.php?option=com_pizzabox&amp;view=parts');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_flavours'), 'index.php?option=com_pizzabox&amp;view=flavours');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_orders'), 'index.php?option=com_pizzabox&amp;view=orders');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_status'), 'index.php?option=com_pizzabox&amp;view=status');

if ( file_exists($path) ) {
	require_once $path;
	$classname	= 'PizzaboxController'.ucfirst($controller);
	$controller	= new $classname( );
	$controller->execute( JRequest::getCmd( 'task' ) );
	$controller->redirect();
} else {
	echo "Controller not found";
}
