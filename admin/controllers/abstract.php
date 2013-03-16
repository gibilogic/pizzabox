<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: controllers/abstract.php 2012-08-14 13:26:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

jimport('joomla.application.component.controller');

class PizzaboxControllerAbstract extends JController
{
	var $_controllerName = '';
  var $_controllerUrl = '';
	var $_model = NULL;

	function __construct( $default = array() )
	{
    $this->_controllerName = JRequest::getCmd('controller');
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', $this->_controllerName );
		}
		parent::__construct( $default );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'add', 'edit' );
		$this->_model =& $this->getModel( $this->_controllerName );
		$this->_controllerUrl = 'index.php?option=com_pizzabox&controller=' . $this->_controllerName;
	}
		
	function display()
	{
		$this->_display();
	}
	
	function edit()
	{
		if ( $this->_model->checkout() ){
			$this->_display('form');
		} else {
			$this->_setDefaultRedirect();
		}
	}
	
	function save()
	{
		JRequest::checkToken() or die( 'Invalid Token' );
		if ( false ===  $this->_model->save() ) {
		    $this->edit();
		} else {
			$row =& $this->_model->getRow();
		  	if ( 'apply' == $this->getTask() ) {
		  		$this->_controllerUrl .= '&task=edit&id=' . $row->id ;
		  	}
		  	$msg = JText::sprintf( 'PIZZABOX_SAVED', $row->ref ) ;
		    $this->setRedirect( $this->_controllerUrl  , $msg );
		}
	}
	
	function cancel()
	{
		$this->_setDefaultRedirect();
	}
	
	function remove()
	{
		JRequest::checkToken() or die( 'Invalid Token' );
		
		if ( $this->_model->remove() ) {
			$msg = JText::sprintf('Successfully removed ', count($this->_model->getCid()));
			$this->setRedirect( $this->_controllerUrl, $msg );
			
		} else {
			$this->_setDefaultRedirect();
		}	
	}
	
	function unpublish()
	{
		$this->_publish(false);
	}
	
	function publish()
	{
		$this->_publish(true);
	}

	function _publish($bool)
	{
		if ( $this->_model->publish($bool) ) {
			$state = $bool ? 'PIZZABOX_PUBLISHED' : 'PIZZABOX_UNPUBLISHED' ;
			$msg = JText::sprintf('PIZZABOX_PUBLISH_SUCCESS', count($this->_model->getCid())) . " " . JText::_( $state );
			$this->setRedirect( $this->_controllerUrl , $msg );
		} else {
			$this->_setDefaultRedirect();
		}
	}
		
	function orderdown()
	{
		$this->_order(1);
	}
	
	function orderup()
	{
		$this->_order(-1);
	}
	
  function _order( $inc )
	{
		$this->_model->order($inc);
		$this->_setDefaultRedirect();
	}
	
	function _setDefaultRedirect()
	{
		$this->setRedirect( $this->_controllerUrl );
	}

	function _display( $tpl=null )
	{
		$view = & $this->getView( $this->_controllerName, 'html');
		$view->setModel($this->_model, true);
		$view->display($tpl);
	}
}
