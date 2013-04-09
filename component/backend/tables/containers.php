<?php

/**
 * @version		    $Id: tables/containers.php 2012-08-14 14:48:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');

/**
 * TableContainers
 */
class TableContainers extends JTable
{
	public $id = 0;
	public $name = '';
	public $desc = null;
	public $image = null;
	public $price = null;
	public $ordering = null;
	public $checked_out = null;
	public $checked_out_time = null;
	public $published = 1;

	/**
	 * Class constructor.
	 *
	 * @param JDatabase $db
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__pizzabox_containers', 'id', $db);
	}
}
