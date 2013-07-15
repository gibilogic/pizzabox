<?php

/**
 * @version		    frontend/views/containers/view.html.php 2013-07-07 20:01:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */
defined('_JEXEC') or die('The way is shut');
jimport('joomla.application.component.view');

/**
 * PizzaboxViewContainers
 */
class PizzaboxViewContainers extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->containers = $this->getModel()->getListNotEmpty();

		$this->loadHelper('pizzabox');
		$this->helper = new PizzaboxHelper();

		parent::display($tpl);
	}
}
