<?php

/**
 * @version		    tables/ordersparts.php 2013-07-07 19:49:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

/**
 * TableOrdersParts
 */
class TableOrdersParts extends JTable
{
	public $id = 0;
	public $order_id = 0;
	public $container_number = 0;
	public $container_id = 0;
	public $part_id = 0;
	public $flavour_id = 0;
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
		parent::__construct('#__pizzabox_order_parts', 'id', $db);
	}
}
