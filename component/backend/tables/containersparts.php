<?php

/**
 * @version		    tables/containersparts.php 2013-07-07 19:49:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

/**
 * TableContainersParts
 */
class TableContainersParts extends JTable
{
	public $id = 0;
	public $container_id = 0;
	public $part_id = 0;
	public $minimum = 1;
	public $maxmimum = 1;

	/**
	 * Class constructor.
	 *
	 * @param JDatabase $db
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__pizzabox_containers_parts', 'id', $db);
	}
}
