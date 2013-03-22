<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: models/schemes.php 2012-09-10 15:13:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

require_once('abstract.php');

class PizzaboxModelSchemes extends PizzaboxModelAbstract
{
  var $_error = '';
  var $_lastId = 0;
	
  function getHtmlList()
  {
		$db =& JFactory::getDBO();
		$query = 'SELECT `id`, `name` FROM #__pizzabox_schemes';
		$db->setQuery( $query );
		$schemes_list = $db->loadObjectList('id');
		return $schemes_list;
	}
    
  function getItems( $enable_limit = true )
  {   
		$result = array();   		
    $app =& JFactory::getApplication();
    $context = 'com_pizzabox.schemes.';
    $default_limit = $app->getCfg('list_limit');
        
	  $filter_containers	= $app->getUserStateFromRequest( $context.'filter_containers', 'filter_containers', '', 'int' );
    if ( $enable_limit ) {
			$limit      = $app->getUserStateFromRequest( $context.'limit', 'limit', $default_limit, 'int' );
			$limitstart = $app->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
		} else {
			$limit 			= 0;
			$limitstart	= 0;
		}    	
    $search        		= $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
    $order         		= $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order', 'filter_order', 'ordering', 'cmd'));
    $order_dir     		= $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order_dir', 'filter_order_Dir', 'asc', 'cmd'));

    $query = 'SELECT * FROM `#__pizzabox_schemes` ';
    $where = array();
		if ( $search ) {
			$search = $this->_db->getEscaped( trim( strtolower( $search ) ) );
			$where []= "`name` LIKE '%$search%'";
		}
		if ( $filter_containers ) {
			$where []= "`container_id` = '$filter_containers'";
		}
		if ( count( $where ) ) {
			$query .= " WHERE " . join( ' AND ', $where );
		}
		
    if( $order == 'container_name' ) $order = 'container_id';
		$query .= ' ORDER BY ' . $order . ' ' . strtoupper( $order_dir ) ;
    $rows = $this->_getList( $query , $limitstart, $limit );

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
 
  function remove()
	{
		$cid = $this->getCid() ;
		$cids = implode( ',', $cid );
		$this->_db->setQuery( 'DELETE FROM `#__pizzabox_schemes` WHERE id IN (' . $cids . ')' );
		
		if ( !$this->_db->query() ) {
			JError::raiseError( 500, $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}
}
