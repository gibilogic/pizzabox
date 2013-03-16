<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: tables/status.php 2012-08-13 13:49:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

class TableStatus extends JTable
{
    var $id               = 0;	    /* @var int Unique identifier */
    var $name             = '';		  /* @var string Status name */
    var $desc             = null;	  /* @var string Status description */
    var $lock             = 0;		  /* @var bool If true, orders in this status are locked */
    var $image            = null;	  /* @var string Full path of an image for the status */
    var $ordering         = null;
    var $checked_out      = null;
    var $checked_out_time = null;
    var $published        = 1;

    function __construct(& $db) {
        parent::__construct('#__pizzabox_status', 'id', $db);
    }
}
