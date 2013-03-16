<?php defined('_JEXEC') or die('Restricted access');
/**
 * @version		$Id: controllers/default.php 2011-10-13 09:31:00Z zanardi $
 * @package		PizzaBox
 * @copyright	Copyright (C) 2011 GiBiLogic. All rights reserved.
 * @license		GNU/GPLv2
 */

jimport('joomla.application.component.controller');

class PizzaboxControllerDefault extends JController
{
	function __construct( $default = array() )
	{
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'default' );
		}
		parent::__construct( $default );
	}
}
