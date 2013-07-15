<?php

/**
 * @version		    tables/flavours.php 2013-07-07 19:49:00Z zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
defined('_JEXEC') or die('The way is shut!');

/**
 * TableFlavours
 */
class TableFlavours extends JTable
{
	public $id = 0;
	public $name = '';
	public $desc = null;
	public $image = null;
	public $price = null;
	public $parts = '[]';
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
		parent::__construct('#__pizzabox_flavours', 'id', $db);
	}
}
