<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version			  $Id: views/default/tmpl/default.php 2012-08-13 14:27:00Z zanardi$
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */ 
?>
<ul>
	<li><a href="index.php?option=com_pizzabox&controller=containers"><?php echo JText::_('PIZZABOX_MANAGE_CONTAINERS') ?></a></li>
	<li><a href="index.php?option=com_pizzabox&controller=schemes"><?php echo JText::_('PIZZABOX_MANAGE_SCHEMES') ?></a></li>
	<li><a href="index.php?option=com_pizzabox&controller=parts"><?php echo JText::_('PIZZABOX_MANAGE_PARTS') ?></a></li>
	<li><a href="index.php?option=com_pizzabox&controller=flavours"><?php echo JText::_('PIZZABOX_MANAGE_FLAVOURS') ?></a></li>
	<li><a href="index.php?option=com_pizzabox&controller=status"><?php echo JText::_('PIZZABOX_MANAGE_STATUS') ?></a></li>
	<li><a href="index.php?option=com_pizzabox&controller=orders"><?php echo JText::_('PIZZABOX_MANAGE_ORDERS') ?></a></li>
</ul>
