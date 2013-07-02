<?php

/**
 * @version     controllers/default.php 2012-07-02 20:33:00Z zanardi
 * @package     GiBi PizzaBox
 * @author      GiBiLogic <info@gibilogic.com>
 * @authorUrl   http://www.gibilogic.com
 * @copyright   Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license     GNU/GPL v2
 */
defined('_JEXEC') or die('The way is shut!');

jimport('joomla.application.component.controller');

class PizzaboxControllerDefault extends JControllerLegacy
{

    public function __construct($default = array())
    {
        if (!JRequest::getCmd('view')) {
            JRequest::setVar('view', 'default');
        }
        parent::__construct($default);
    }

}
