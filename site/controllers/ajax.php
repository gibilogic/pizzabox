<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: controllers/ajax.php 2012-08-13 15:37:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

jimport('joomla.application.component.controller');

class PizzaboxControllerAjax extends JController
{
	function display( $tpl=null )
	{
    echo JText::_( JRequest::getWord('string'), 'No translation' );
	}
}
