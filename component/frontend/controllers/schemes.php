<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: controllers/schemes.php 2012-09-10 15:20:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

jimport('joomla.application.component.controller');

class PizzaboxControllerSchemes extends JController
{
	var $_controllerUrl = '';
	var $_model = NULL;

	function __construct( $default = array() )
	{
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'schemes' );
		}

		parent::__construct( $default );

		$this->_model =& $this->getModel('schemes');
		$this->_PizzaboxControllerUrl = 'index.php?option=com_pizzabox&controller=containers';
		$this->_controllerUrl = 'index.php?option=com_pizzabox&controller=schemes';
		$this->_partsControllerUrl = 'index.php?option=com_pizzabox&controller=parts';
	}
	
	function display( $tpl=null )
	{
    $params =& JComponentHelper::getParams('com_pizzabox');
    if( $params->get('registered_users_only',1) == 1 ) { 
      $user = JFactory::getUser();
      if ( $user->guest ) {
        $msg = JText::_('PIZZABOX_REGISTERED_ONLY');
        $link = JRoute::_('index.php?option=com_users&task=register', false);
        $this->setRedirect ( $link, $msg, 'error' );
      }
    }
		
		$session = JFactory::getSession();
		$container_id = $session->get('com_pizzabox.container.id');
		if ( ! $container_id ) {
			$this->setRedirect( $this->_PizzaboxControllerUrl );
			return false;
		}
		
		$view = & $this->getView('schemes', 'html');
		$view->setModel($this->_model, true);
		$view->display($tpl);
	}
	
	function save()
	{
		JRequest::checkToken() or die( 'PIZZABOX_INVALID_TOKEN' );
		$result = $this->_model->save();
		if ( false === $result ) {
		  	$msg = JText::_( 'PIZZABOX_SAVE_ERROR' );
		    $this->setRedirect( $this->_controllerUrl, $msg, 'error' );
		} else {
		    $this->setRedirect( $this->_partsControllerUrl );
		}
	}
}
