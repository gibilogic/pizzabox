<?php

/**
 * @version		    controllers/ajax.php 2013-07-02 20:58:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.controller');

class PizzaboxControllerAjax extends JController
{
	public function display( $tpl=null )
	{
    echo JText::_( JRequest::getWord('string'), 'No translation' );
	}
}
