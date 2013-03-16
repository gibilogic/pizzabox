<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: controllers/flavours.php 2012-09-02 14:18:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

jimport('joomla.application.component.controller');

class PizzaboxControllerFlavours extends JController
{
	var $_controllerUrl = '';
	var $_model = NULL;

	function __construct( $default = array() )
	{
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'flavours' );
		}
    
		parent::__construct( $default );

    require_once ( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'flavours.php' );
		$this->_model =& $this->getModel('flavours');
		$this->_controllerUrl = 'index.php?option=com_pizzabox&controller=flavours';
	}
	
	function display( $tpl=null )
	{
		$view = & $this->getView('flavours', 'html');
		$view->setModel($this->_model, true);
		$view->display($tpl);
	}
}
