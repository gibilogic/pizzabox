<?php

/**
 * @version     pizzabox.php 2013-11-24 18:07:00 UTC zanardi
 * @package     GiBi PizzaBox
 * @author      GiBiLogic <info@gibilogic.com>
 * @authorUrl   http://www.gibilogic.com
 * @copyright   (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license     GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.helper');
jimport('joomla.log.log');
JLog::addLogger(array('text_file' => 'com_pizzabox.log.php'),JLog::ALL,'com_pizzabox');

// load requested controller if exists
$controller = JRequest::getWord('controller', 'default');
$path = JPATH_COMPONENT . '/controllers/' . $controller . '.php';

$document = & JFactory::getDocument();
$document->addStyleDeclaration('.icon-16-pizzabox{ background-image: url("/components/com_pizzabox/assets/icon-16.png") }');
$document->addStyleDeclaration('.icon-48-pizzabox{ background-image: url("/components/com_pizzabox/assets/icon-48.png") }');
$document->addStyleDeclaration('#lock{ background: none !important;width: auto !important;height: auto !important }');

JSubMenuHelper::addEntry(JText::_('pizzabox_manage_containers'), 'index.php?option=com_pizzabox&amp;view=containers');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_parts'), 'index.php?option=com_pizzabox&amp;view=parts');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_flavours'), 'index.php?option=com_pizzabox&amp;view=flavours');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_orders'), 'index.php?option=com_pizzabox&amp;view=orders');
JSubMenuHelper::addEntry(JText::_('pizzabox_manage_status'), 'index.php?option=com_pizzabox&amp;view=status');

if (file_exists($path)) {
    require_once $path;
    $classname = 'PizzaboxController' . ucfirst($controller);
    $controller = new $classname( );
    $controller->execute(JRequest::getCmd('task'));
    $controller->redirect();
}
else {
    echo "Controller not found";
}
