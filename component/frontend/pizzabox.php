<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: pizzabox.php 2012-09-09 16:20:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

jimport('joomla.application.component.helper');
jimport('joomla.filesystem.file');

// load requested controller if exists
$controller = JRequest::getWord('controller');
if (! $controller ) { $controller = JRequest::getWord('view'); }
if (! $controller ) { $controller = 'containers'; }
$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
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

