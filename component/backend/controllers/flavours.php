<?php

/**
 * @version		    $Id: controllers/flavours.php 2012-08-14 13:26:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');
require_once('abstract.php');

/**
 * PizzaboxControllerFlavours
 */
class PizzaboxControllerFlavours extends PizzaboxControllerAbstract
{
	public function __construct($default = array())
	{
		parent::__construct($default);

		$this->_model_parts =& $this->getModel('parts');
	}

	protected function _display($tpl = null)
	{
		$view =& $this->getView($this->_controllerName, 'html');
		$view->setModel($this->_model, true);
		$view->setModel($this->_model_parts);
		$view->display($tpl);
	}
}
