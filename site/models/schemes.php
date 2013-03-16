<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: models/schemes.php 2012-08-19 12:30:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

jimport('joomla.application.component.model');

class PizzaboxModelSchemes extends JModel
{
  var $_error = '';
  var $_lastId = 0;
    
	function getId()
	{
		if ($this->_lastId > 0) {
			return $this->_lastId ;
		}
		$cid = $this->getCid();
		if (empty($cid[0])) {
			return JRequest::getInt('id', 0);
		}
		return $cid[0] ;
	}
	
	function setId( $id )
	{
		$this->_lastId = $id;
	}
	
	function getCid()
	{
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger( $cid, array(0) );
		return $cid ;
	}
	
	function &getRow()
	{
		$table =& $this->getTable();
		$table->load( $this->getId() );
		return $table;
	}
    
	function getHtmlList()
    {
		$db =& JFactory::getDBO();
		$query = 'SELECT `id`, `name` FROM #__pizzabox_schemes';
		$db->setQuery( $query );
		$html_list = $db->loadObjectList('id');
		return $html_list;
	}
    
  function getItems()
  {   
		$result = array();   
    $app 		=& JFactory::getApplication();
    $session 	=& JFactory::getSession();
    $context = 'com_pizzabox.schemes.';
    $default_limit = $app->getCfg('list_limit');
        
    $filter_containers 	= $session->get( 'com_pizzabox.container.id' );
		$limit         		  = $app->getUserStateFromRequest( $context.'limit', 'limit', $default_limit, 'int' );
	  $limitstart    		  = $app->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
    $search        		  = $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
    $order         		  = $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order', 'filter_order', 'ordering', 'cmd'));
    $order_dir     		  = $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order_dir', 'filter_order_Dir', 'asc', 'cmd'));

    $query = 'SELECT * FROM `#__pizzabox_schemes` ';
    $where = array();
    $where []= "`published` = '1'";
		if ( $search ) {
			$search = $this->_db->getEscaped( trim( strtolower( $search ) ) );
			$where []= " `name` LIKE '%$search%' ";
		}
		if ( $filter_containers ) {
			$where []= "`container_id` = '$filter_containers'";
		}
		if ( count( $where ) ) {
			$query .= " WHERE " . join( ' AND ', $where );
		}		
		
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

	function getItem()
	{
		$result = array();
		$row =& $this->getRow();
		$result['row'] = $row;
		return $result;
	}

	function save()
	{
		// only save scheme id in a session variable
		$element = JRequest::getVar('element');
		$id = JRequest::getInt('schemes');
		
		if ( ( $element == 'scheme' ) && $id ) {
			$session = JFactory::getSession();
			$session->set('com_pizzabox.scheme.id', $id );
			return true;
		} else {
			return false;
		}
	}
  
  function getPrice()
  {
    $price = null;
    $table = $this->getTable();
    $query = "SELECT `price` FROM `" . $table->getTableName() . "` WHERE `id` = '" . $this->_lastId . "'";
    $this->_db->setQuery( $query );
    if( $result = $this->_db->loadResult() ) {
      $price = $result;
    }
    return $price;
  }  
}
