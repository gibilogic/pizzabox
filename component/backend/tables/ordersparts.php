<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: tables/ordersparts.php 2012-08-13 13:49:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

class TableOrdersParts extends JTable
{
	var $id               = 0;	  /* @var int Unique identifier */
	var $order_id         = 0;		/* @var int ID of parent order  */
	var $container_number = 0;	  /* @var int Sequence number of current container  */
	var $container_id     = 0;		/* @var int ID of selected container  */
	var $container_name   = '';	  /* @var string Name of selected container */
	var $scheme_id        = 0;		/* @var int ID of selected scheme  */
	var $scheme_name      = '';		/* @var string Name of selected scheme */
	var $part_id          = 0;		/* @var int ID of selected part  */
	var $part_name        = '';		/* @var string Name of selected part */
	var $flavour_id       = 0;		/* @var int ID of selected flavour  */
	var $flavour_name     = '';		/* @var string Name of selected flavour */
	var $ordering         = null;
	var $checked_out      = null;
	var $checked_out_time = null;
	var $published        = 1;

    function __construct(& $db) {
        parent::__construct('#__pizzabox_order_parts', 'id', $db);
    }
}
