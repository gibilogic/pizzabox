<?php

/**
 * @version		    frontend/views/orders/view.html.php 2013-12-08 12:55:00 UTC zanardi
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic <info@gibilogic.com>
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license		    GNU/GPLv2
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

/**
 * PizzaboxViewOrders
 */
class PizzaboxViewOrders extends JViewLegacy
{

    public $pagination = null;
    public $user = null;

    public function display($tpl = null)
    {
        $this->user = & JFactory::getUser();
        switch ($tpl) {
            case 'form':
                if (!$this->editItem()) {
                    JResponse::setHeader('HTTP/1.0 403', true);
                    JError::raiseWarning(403, JText::_('Access denied'));
                    return;
                };
                break;
            default :
                $this->listItems();
        }

        $document = & JFactory::getDocument();
        $document->addScript(JURI::base() . '/components/com_pizzabox/assets/helper.js');

        $params = & JComponentHelper::getParams('com_pizzabox');
        $this->assignRef('params', $params);

        parent::display($tpl);
    }

    public function listItems()
    {
        jimport('joomla.html.pagination');
        jimport('joomla.html.toolbar');
        JHTML::_('behavior.tooltip');
        $app = & JFactory::getApplication();
        $context = 'com_pizzabox.orders.';

        $is_admin = false;
        $user = & JFactory::getUser();
        if (in_array(8, $user->groups)) {
            $is_admin = true;
        }

        // get filter and search status
        if ($is_admin) {
            $filters['user'] = $app->getUserStateFromRequest($context . 'filter_user', 'filter_user', '', 'int');
        }
        else {
            $filters['user'] = $user->id;
        }
        $filters['status'] = $app->getUserStateFromRequest($context . 'filter_status', 'filter_status', '', 'int');
        $filters['order_date_from'] = $app->getUserStateFromRequest($context . 'filter_order_date_from', 'filter_order_date_from', '', 'string');
        $filters['order_date_to'] = $app->getUserStateFromRequest($context . 'filter_order_date_to', 'filter_order_date_to', '', 'string');
        $filters['delivery_date_from'] = $app->getUserStateFromRequest($context . 'filter_delivery_date_from', 'filter_delivery_date_from', '', 'string');
        $filters['delivery_date_to'] = $app->getUserStateFromRequest($context . 'filter_delivery_date_to', 'filter_delivery_date_to', '', 'string');
        if ($is_admin) {
            $filters['order'] = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'delivery', 'cmd');
            $filters['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        }
        else {
            $filters['order'] = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'id', 'cmd');
            $filters['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'desc', 'word');
        }
        $search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
        $search = JString::strtolower($search);

        // sanitize $filter_order
        if (!in_array($filters['order'], array('ordering', 'id', 'delivery'))) {
            $filters['order'] = 'ordering';
        }

        $model = $this->getModel();

        // default filters for admins - temporarily disabled because i have to find a way to reset filters
        /* if ( ( $filters['delivery_date_from'] == '' ) && ( $filters['delivery_date_to'] == '' ) ) {
          if ( $is_admin ) {
          list( $start_time, $end_time ) = $model->getNextSessionTimes();
          $filters['delivery_date_from'] = $start_time->format('Y-m-d H:i');
          $app->setUserState( $context.'filter_delivery_date_from', $filters['delivery_date_from'] );
          $filters['delivery_date_to'] = $end_time->format('Y-m-d H:i');
          $app->setUserState( $context.'filter_delivery_date_to', $filters['delivery_date_to'] );
          }
          } */

        $items = $this->get('items');

        $javascript = 'onchange="document.adminForm.submit();"';

        // get users list
        $users = $model->getUsersList();

        if ($is_admin) {
            $list = array();
            $list[] = JHTML::_('select.option', '', '-- ' . JText::_('user') . ' --', 'id', 'name');
            $list = array_merge($list, $users);
            $lists['users'] = $list;
            $filters['user'] = JHTML::_('select.genericlist', $list, 'filter_user', $javascript, 'id', 'name', $filters['user']);
        }
        else {
            $filters['user'] = '';
        }

        // get status list
        $statuses = $this->getList('status', false);

        $list = array();
        $list[] = JHTML::_('select.option', '', '-- ' . JText::_('PIZZABOX_STATUS_SELECT') . ' --', 'id', 'name');
        $list = array_merge($list, $statuses);

        $filters['status'] = JHTML::_('select.genericlist', $list, 'filter_status', $javascript, 'id', 'name', $filters['status']);
        $lists['status'] = JHTML::_('select.genericlist', $list, 'new_status', '', 'id', 'name', '');

        foreach ($items['rows'] as &$item) {
            $model->setId($item->id);

            if ($item->user_id) {
                $item->user = $users[$item->user_id]->name;
            }
            else {
                $item->user = '';
            }

            $item->order_total = $model->getTotal();

            if ($item->status_id && isset($statuses[$item->status_id])) {
                $item->status = $statuses[$item->status_id]->name;
                $item->lock = $statuses[$item->status_id]->lock;
            }
            else {
                $item->status = '';
                $item->lock = 0;
            }

            $item->parts = $model->getParts();
        }

        // ADMIN TOOLBAR
        if ($is_admin) {
            $bar = new JToolBar('containersToolbar');
            $bar->appendButton('Custom', '<a class="toolbar" href="javascript:window.print()">' . JText::_('PIZZABOX_ORDERS_PRINT') . '</a>', 'print');

            $lang = & JFactory::getLanguage();
            foreach ($statuses as $status) {
                if (!$status->published)
                {
                    continue;
                }

                // If there an exact relevant string for a button, use it; else use a default string "Set status " + status name
                if ($lang->hasKey('PIZZABOX_ORDERS_SET_' . strtr($status->name, ' ', '_'))) {
                    $button_label = JText::_('PIZZABOX_ORDERS_SET_' . strtr($status->name, ' ', '_'));
                }
                else {
                    $button_label = JText::_('PIZZABOX_ORDERS_SET') . $status->name;
                }

                $button_action = 'setStatus' . $status->id;
                $bar->appendButton('Standard', $button_action, $button_label, $button_action, true);
            }
            echo $bar->render();
        }

        $this->assignRef('filters', $filters);
        $this->assignRef('lists', $lists);
        $this->assignRef('is_admin', $is_admin);
        $this->assignRef('items', $items);
        $this->pagination = new JPagination($items['total'], $items['limitstart'], $items['limit']);
    }

    public function editItem()
    {
        JRequest::setVar('hidemainmenu', 1);

        $item = & $this->get('item');
        $user = & JFactory::getUser();
        if ((!in_array(8, $user->groups) ) && ( $user->id != $item['row']->user_id )) {
            return false;
        }

        $item['row']->isAdmin = false;
        if (in_array(8, $user->groups)) {
            $item['row']->isAdmin = true;
        }

        $model = $this->getModel();
        $users = $model->getUsersList();
        $item['row']->user = $users[$item['row']->user_id]->name;

        $model->setId($item['row']->id);
        $item['row']->order_total = $model->getTotal();

        $status = $this->getList('status', false);

        $list = array();
        $list[] = JHTML::_('select.option', '', '-- ' . JText::_('PIZZABOX_STATUS_SELECT') . ' --', 'id', 'name');
        $list = array_merge($list, $status);
        $lists['status'] = JHTML::_('select.genericlist', $list, 'status_id', '', 'id', 'name', $item['row']->status_id);

        $model->setId($item['row']->id);
        $parts = $model->getParts();

        $this->assign($item);
        $this->assignRef('parts', $parts);
        $this->assignRef('lists', $lists);

        return true;
    }

    public function getList($elements_type, $enable_limit = true)
    {
        require_once ( JPATH_COMPONENT_ADMINISTRATOR . '/models/' . $elements_type . '.php' );
        $class_name = "PizzaboxModel" . $elements_type;
        $model = new $class_name();
        $elements = $model->getItems($enable_limit);
        return ( $elements['rows'] );
    }

}
