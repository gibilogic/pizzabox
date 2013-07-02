<?php
/**
 * @version		    pizzabox.php 2013-07-02 20:45:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic snc. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
 defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.helper');
jimport('joomla.filesystem.file');

// load requested controller if exists
$controller = JRequest::getWord('controller');
if (! $controller ) { $controller = JRequest::getWord('view'); }
if (! $controller ) { $controller = 'containers'; }
$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
if ( file_exists($path) )
{
  $params =& JComponentHelper::getParams('com_pizzabox');
  if( $params->get('include_basic_css',1) == 1 ) {
    $document =& JFactory::getDocument();
    $document->addStyleSheet( JURI::base() . 'components/com_pizzabox/assets/basic.css' );
  }
	require_once $path;
	$classname	= 'PizzaboxController'.ucfirst($controller);
	$controller	= new $classname( );
	$controller->execute( JRequest::getCmd( 'task' ) );
	$controller->redirect();
} else {
	echo "Controller not found";
}

