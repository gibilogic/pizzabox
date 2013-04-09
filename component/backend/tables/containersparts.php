<?php

/**
 * @version		    $Id: tables/containersparts.php 2013-03-22 17:24:00Z matteo $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
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
