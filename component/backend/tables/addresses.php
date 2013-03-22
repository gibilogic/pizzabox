<?php

/**
 * @version		    $Id: tables/addresses.php 2013-03-22 09:00:00Z matteo $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');

/**
 * TableAddresses
 *
 * @see JTable
 */
class TableAddresses extends JTable
{
	public $id = 0;
	public $name = '';
	public $street = '';
	public $zip = '';
	public $city = '';
	public $state = '';
	public $country = '';
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
		parent::__construct('#__pizzabox_addresses', 'id', $db);
	}
}
