<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: models/orders.php 2012-09-10 14:55:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

require_once('abstract.php');

class PizzaboxModelOrders extends PizzaboxModelAbstract
{
    var $_error = '';
    var $_lastId = 0;

	function getHtmlList()
    {
		$db =& JFactory::getDBO();
		$query = 'SELECT `id`, `name` FROM #__pizzabox_orders';
		$db->setQuery( $query );
		$schemes_list = $db->loadObjectList('id');
		return $schemes_list;
	}
    
  function getItems( $enable_limit = true )
  {   
    $result = array();   		
    $app =& JFactory::getApplication();
    $context = 'com_pizzabox.orders.';
    $default_limit = $app->getCfg('list_limit');
        
    $user =& JFactory::getUser();
    $is_admin = false;
    if ( in_array( 8, $user->groups ) ) { $is_admin = true; }

        
    if ( $is_admin ) {
			$filter_user = $app->getUserStateFromRequest( $context.'filter_user', 'filter_user', '', 'int' );
		} else {
			$filter_user = $user->id;
		}	
		$filter_status				= $app->getUserStateFromRequest( $context.'filter_status', 'filter_status', '', 'int' );
	  $filter_order_date_from		= $app->getUserStateFromRequest( $context.'filter_order_date_from', 'filter_order_date_from', '', 'string' );
	  $filter_order_date_to		= $app->getUserStateFromRequest( $context.'filter_order_date_to', 'filter_order_date_to', '', 'string' );
		$filter_delivery_date_from	= $app->getUserStateFromRequest( $context.'filter_delivery_date_from', 'filter_delivery_date_from', '', 'string' );
		$filter_delivery_date_to	= $app->getUserStateFromRequest( $context.'filter_delivery_date_to', 'filter_delivery_date_to', '', 'string' );
        
    if ( $enable_limit ) {
			$limit      = $this->getState('limit');
			$limitstart = $this->getState('limitstart');
		} else {
			$limit 			= 0;
			$limitstart	= 0;
		}
		
    $order         = $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order', 'filter_order', 'id', 'cmd'));
    $order_dir     = $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order_dir', 'filter_order_Dir', 'desc', 'cmd'));

    $query = 'SELECT * FROM `#__pizzabox_orders` ';
    $where = array();
		if ( $filter_user ) {
			$where []= "`user_id` = '$filter_user'";
		}

		if ( $filter_status ) {
			$where []= "`status_id` = '$filter_status'";
		}

		if ( $filter_order_date_from ) {
			$where []= "`datetime` > '$filter_order_date_from 00:00:00'";
		}

		if ( $filter_order_date_to ) {
			$where []= "`datetime` <= '$filter_order_date_to 23:59:59'";
		}
		
		if ( $filter_delivery_date_from ) {
			$where []= "`delivery` > '$filter_delivery_date_from 00:00:00'";
		}

		if ( $filter_delivery_date_to ) {
			$where []= "`delivery` <= '$filter_delivery_date_to 23:59:59'";
		}
		
		if ( count( $where ) ) {
			$query .= " WHERE " . join( ' AND ', $where );
		}
		
		$query .= ' ORDER BY ' . $order . ' ' . strtoupper( $order_dir ) ;
		
    $rows = $this->_getList( $query , $limitstart, $limit );

    if ( !$this->_db->getErrorNum() ) {
      $result['total'] = $this->_getListCount( $query );;
      $result['limit'] = $limit; 
      $result['limitstart'] = $limitstart;
      $result['rows'] = $rows ;
      $result['order'] = $order ;
      $result['order_dir'] = $order_dir;          
    } else {
      JError::raiseWarning( 200, $this->_db->getErrorMsg() );
      return false;
    }

    return $result ;
  }

  function remove()
	{
		$cid = $this->getCid() ;
		$cids = implode( ',', $cid );
		$this->_db->setQuery( 'DELETE FROM `#__pizzabox_orders` WHERE `id` IN (' . $cids . ')' );
		
		if ( !$this->_db->query() ) {
			JError::raiseError( 500, $this->_db->getErrorMsg() );
			return false;
		}
		
		if (! $this->_removeParts( $cid ) ) {
			return false;
		}

		return true;
	}
	
	function _removeParts( $cid ) 
	{
		$cids = implode( ',', $cid );
		$this->_db->setQuery( 'DELETE FROM `#__pizzabox_orders_parts` WHERE `order_id` IN (' . $cids . ')' );
		
		if ( !$this->_db->query() ) {
			JError::raiseError( 500, $this->_db->getErrorMsg() );
			return false;
		}
		
		return true;
	}

  function changeStatus()
	{
		$cid = $this->getCid() ;
		$cids = implode( ',', $cid );
		
		$new_status = JRequest::getInt('new_status');
				
		if ( ! $new_status ) {
			return false;
		}
		
		$this->_db->setQuery( "UPDATE `#__pizzabox_orders` SET `status_id` = '$new_status' WHERE id IN (" . $cids . ")" );
		
		if ( !$this->_db->query() ) {
			JError::raiseError( 500, $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}
	
	function getUsersList() 
  {
		$users = array();
		$db =& JFactory::getDBO();
		$query = 	"SELECT DISTINCT o.`user_id` as id, u.`name` " .
					"FROM `#__pizzabox_orders` o " .
					"LEFT JOIN `#__users` u " . 
					"ON o.`user_id` = u.`id` ";
		$db->setQuery( $query );
		if ( $result = $db->loadObjectList('id') ) {
			$users = $result;
		}
		return ( $users );
 	}

	function getParts() 
  {
		$parts = array();
		$db =& JFactory::getDBO();
		$query = 	"SELECT * FROM `#__pizzabox_orders_parts` " .
					"WHERE `order_id` = '" . $this->_lastId . "' " .
					"ORDER BY `container_number` ";
				
		$db->setQuery( $query );
		if ( $result = $db->loadObjectList() ) {
			$parts = $result;
		}
		return ( $parts );
 	}
  
  function getTotal()
  {
    $total = 0;
    
    if( ! class_exists( 'PizzaboxModelContainers' ) ) require_once( JPATH_COMPONENT.DS.'models'.DS.'containers.php' );
    $containers_model = new PizzaboxModelContainers;
    if( ! class_exists( 'PizzaboxModelSchemes' ) ) require_once( JPATH_COMPONENT.DS.'models'.DS.'schemes.php' );
    $schemes_model = new PizzaboxModelSchemes;
    if( ! class_exists( 'PizzaboxModelParts' ) ) require_once( JPATH_COMPONENT.DS.'models'.DS.'parts.php' );
    $parts_model = new PizzaboxModelParts;
    if( ! class_exists( 'PizzaboxModelDlavours' ) ) require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'flavours.php' );
    $flavours_model = new PizzaboxModelFlavours;
    
    $container_number = 0;
    foreach ( $this->getParts() as $part ) {
      if ( $part->container_number != $container_number ) {
        $container_number = $part->container_number;
        $containers_model->setId ( $part->container_id );
        $total += $containers_model->getPrice();
        $schemes_model->setId ( $part->scheme_id );
        $total += $schemes_model->getPrice();
      }
      $parts_model->setId( $part->id );
      $total += $parts_model->getPrice();
      $flavours_model->setId( $part->flavour_id );
      $total += $flavours_model->getPrice();
    }
        
    return $total;
  }
  
 	
 	static function getNextSessionTimes() 
  {
		$params =& JComponentHelper::getParams('com_pizzabox');

		$start_time = new DateTime( $params->get('delivery_start') );
		$end_time = new DateTime ( $params->get('delivery_end') );
		$now = new DateTime();
		
		// If we're BEFORE or IN in first delivery session, return session start and end
		if ( $now < $end_time ) {
			return array( $start_time, $end_time );
		}
		
		$second_start_time = new DateTime( $params->get('second_delivery_start') );
		$second_end_time = new DateTime ( $params->get('second_delivery_end') );
		
		// If there is a second delivery session and we're BEFORE or IN this session, return session start and end
		if ( $second_start_time && $second_end_time && ( $now < $second_end_time ) ) {
			return array( $second_start_time, $second_end_time );
		}
		
		// If not return tomorrow first session start and end
		$start_time->modify('+24 hours');
		$end_time->modify('+24 hours');
		return array( $start_time, $end_time );
	}
  
  function setDefaultStatus()
  {
    $query = "UPDATE `#__pizzabox_orders` SET `status_id` = '1' WHERE `id` = '" . $this->_lastId ."'";
    $this->_db->setQuery( $query );
    $this->_db->query();
  }
  
  function setEmptyDelivery()
  {
    $query = "UPDATE `#__pizzabox_orders` SET `delivery` = NULL WHERE `id` = '" . $this->_lastId ."'";
    $this->_db->setQuery( $query );
    $this->_db->query();
  }
  
  function copy()
  {
    if(! $new_id = parent::copy() ) return false;
    $query = "SELECT * FROM `#__pizzabox_orders_parts` WHERE `order_id` = '" . $this->_lastId . "'";
    $this->_db->setQuery( $query );
    $result = $this->_db->loadObjectList();
    if( count( $result ) ) {
      foreach( $result as $row ) {
        $values []= "(
          '$new_id', 
          '$row->container_number', 
          '$row->container_id', 
          '$row->container_name', 
          '$row->scheme_id', 
          '$row->scheme_name', 
          '$row->part_id', 
          '$row->part_name', 
          '$row->flavour_id', 
          '$row->flavour_name'
        )";
      }
      $query = "INSERT INTO `#__pizzabox_orders_parts`" .
      " (`order_id`,`container_number`,`container_id`,`container_name`,`scheme_id`,`scheme_name`,`part_id`,`part_name`,`flavour_id`,`flavour_name`)" .
      " VALUES " . join(',', $values );
      $this->_db->setQuery( $query );
      $this->_db->query();
    }
    return $new_id;
  }
}
