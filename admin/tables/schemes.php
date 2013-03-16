<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: tables/schemes.php 2012-08-14 14:48:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

class TableSchemes extends JTable
{
	var $id               = 0;	  /* @var int Unique identifier */
	var $container_id     = 0;	  /* @var int ID of parent container  */
	var $name             = '';		
	var $desc             = null; 
	var $image            = null;	/* @var string Full image path */
	var $price            = null;	/* @var string Item price */
	var $ordering         = null;
	var $checked_out      = null;
	var $checked_out_time = null;
	var $published        = 1;

    function __construct(& $db) {
        parent::__construct('#__pizzabox_schemes', 'id', $db);
    }
}
