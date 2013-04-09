<?php

/**
 * @version		    $Id: views/containers/view.html.php 2012-09-02 14:14:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */

defined('_JEXEC') or die('The way is shut');
jimport( 'joomla.application.component.view');

/**
 * PizzaboxViewContainers
 */
class PizzaboxViewContainers extends JView
{
	public function display($tpl = null)
	{
		$this->containers = $this->getModel()->getListNotEmpty();

		$this->loadHelper('pizzabox');
		$this->helper = new PizzaboxHelper();

		parent::display($tpl);
	}
}
