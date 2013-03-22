<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: controllers/ajax.php 2012-08-14 13:27:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

jimport('joomla.application.component.controller');

class PizzaboxControllerAjax extends JController
{
	function getSchemes()
	{
		require_once(JPATH_COMPONENT.DS.'models'.DS.'schemes.php');
		$model = new PizzaboxModelSchemes();
		$array = $model->getItems();
		echo json_encode( $array['rows'] );
	}
}
