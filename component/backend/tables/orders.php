<?php

/**
 * @version		    $Id: tables/orders.php 2012-09-18 09:54:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');

/**
 * TableOrders
 */
class TableOrders extends JTable
{
	public $id = 0;
	public $user_id = 0;
	public $status_id = 0;
	public $datetime = null;
	public $delivery = null;
	public $name = null;
	public $ordering = null;
	public $checked_out = null;
	public $checked_out_time = null;
	public $published = 1;

	/**
	 * Class constructor.
	 *
	 * @param JDatabase $db
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__pizzabox_orders', 'id', $db);
	}
}
