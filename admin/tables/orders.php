<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: tables/orders.php 2012-09-18 09:54:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

class TableOrders extends JTable
{
    var $id               = 0;	  /* @var int Unique identifier */
    var $user_id          = 0;		/* @var int ID of user who placed the order */
    var $status_id        = 0;		/* @var string Status of the order */
    var $datetime         = null;	/* @var datetime Date / time in which the order was placed */
    var $delivery         = null;	/* @var datetime Requested delivery date */
    var $name             = null;	/* @var name Name associated with order */
    var $ordering         = null;
    var $checked_out      = null;
    var $checked_out_time = null;
    var $published        = 1;

    function __construct(& $db) {
        parent::__construct('#__pizzabox_orders', 'id', $db);
    }
}
