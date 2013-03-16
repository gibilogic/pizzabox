<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: models/status.php 2012-08-13 13:59:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

require_once('abstract.php');

class PizzaboxModelStatus extends PizzaboxModelAbstract
{
  var $_error = '';
  var $_lastId = 0;
        
  function getItems( $enable_limit = true )
  {   
    $result = array();   
    $app =& JFactory::getApplication();
    $context = 'com_pizzabox.status.';
    $default_limit = $app->getCfg('list_limit');
        
    if ( $enable_limit ) {
			$limit      = $this->getState('limit');
			$limitstart = $this->getState('limitstart');
		} else {
			$limit      = 0;
			$limitstart = 0;
		}    	
    $search        = $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
    //$order         = $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order', 'filter_order', 'ordering', 'cmd'));
    $order         = 'ordering';
    //$order_dir     = $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order_dir', 'filter_order_Dir', 'asc', 'cmd'));
    $order_dir     = 'asc';
        
    $query = 'SELECT * FROM `#__pizzabox_status` ';
    $where = '';
		if ( $search ) {
			$search = $this->_db->getEscaped( trim( strtolower( $search ) ) );
			$where .= " WHERE `name` LIKE '%$search%' ";
		}
		
		$query .= $where .  ' ORDER BY ' . $order . ' ' . strtoupper( $order_dir ) ;
	
    $this->_db->setQuery( $query, $limitstart, $limit );
		$rows = $this->_db->loadObjectList('id');

    if ( !$this->_db->getErrorNum() ) {
      $result['limitstart'] = $limitstart ;
      $result['limit'] = $limit ; 
      $result['total'] = $this->_getListCount( $query );
      $result['rows'] = $rows ;
      $result['search'] = $search ;
      $result['order'] = $order ;
      $result['order_dir'] = $order_dir;          
    } else {
      JError::raiseWarning( 200, $this->_db->getErrorMsg() );
      return false;
    }
         
    return $result ;
  }

  function save()
  {
    $row = & $this->getRow();

    $isNew = true;
    if(!empty($row->id)) {
      $isNew = false;
    }

    if ( !$row->bind( JRequest::get('post') ) ) {
      JError::raiseWarning( 200, $row->getError() );
      return false;
    }

    if ( $isNew ) $row->ordering = $row->getNextOrder();

    $lock = JRequest::getVar('lock',FALSE);		
    $row->lock = $lock ? 1 : 0;

    if ( ! $row->check() || in_array(false, $result, true) ) {
      JError::raiseWarning( 200, $row->getError() );
      return false;
    }

    if ( !$row->store() ) {
      JError::raiseWarning( 200, $row->getError() );
      return false;
    }

    $row->checkin();

    $this->_lastId = $row->id;

    return true;
  }
   
  function remove()
	{
		$cid = $this->getCid() ;
		$cids = implode( ',', $cid );
		$this->_db->setQuery( 'DELETE FROM `#__pizzabox_status` WHERE id IN (' . $cids . ')' );
		
		if ( !$this->_db->query() ) {
			JError::raiseError( 500, $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}
}
