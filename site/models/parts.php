<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: models/parts.php 2012-08-19 12:30:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

jimport('joomla.application.component.model');

class PizzaboxModelParts extends JModel
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
		$query = 'SELECT `id`, `name` FROM #__pizzabox_parts';
		$db->setQuery( $query );
		$html_list = $db->loadObjectList('id');
		return $html_list;
	}
    
    function getItems()
    {   
		$result = array();   
        $app 		=& JFactory::getApplication();
        $session 	=& JFactory::getSession();
        $context = 'com_pizzabox.parts.';
        $default_limit = $app->getCfg('list_limit');
        
        $limit				= $app->getUserStateFromRequest( $context.'limit', 'limit', $default_limit, 'int' );
		$limitstart			= $app->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
        $filter_containers 	= $session->get( 'com_pizzabox.container.id' );
        $filter_schemes 	= $session->get( 'com_pizzabox.scheme.id' );
    	$search        		= $app->getUserStateFromRequest( $context.'search', 'search', '', 'string' );
        $order         		= $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order', 'filter_order', 'ordering', 'cmd'));
        $order_dir     		= $this->_db->getEscaped($app->getUserStateFromRequest( $context.'order_dir', 'filter_order_Dir', 'asc', 'cmd'));

    $query = 'SELECT * FROM `#__pizzabox_parts` ';
    $where = array();
    $where []= "`published` = '1'";        
		if ( $search ) {
			$search = $this->_db->getEscaped( trim( strtolower( $search ) ) );
			$where []= " `name` LIKE '%$search%' ";
		}
		if ( $filter_containers ) {
			$where []= "`container_id` = '$filter_containers'";
		}
		if ( $filter_containers ) {
			$where []= "`scheme_id` = '$filter_schemes'";
		}
		if ( count( $where ) ) {
			$query .= ' WHERE ' . join( ' AND ', $where );
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
		// Check that user is registered
    $params =& JComponentHelper::getParams('com_pizzabox');
    if( $params->get('registered_users_only',0) == 1 ) { 
      $user = JFactory::getUser();
      if ( $user->guest ) return false;
    }
		
		$db =& JFactory::getDBO();
		
		$session =& JFactory::getSession();
		$order_id = $session->get( 'com_pizzabox.order.id', 0 );
		$container_number = $session->get( 'com_pizzabox.container.number', 1 );
		
		if ( $order_id ) {
			// Check that order exists in db
			$query = "SELECT COUNT(*) FROM `#__pizzabox_orders` WHERE `id` = '$order_id'";
			$db->setQuery( $query );
			if (! $db->loadResult() ) { return false; }
			$container_number++;
		} else { 
			$date = new DateTime();
			$date = $date->format ('Y-m-d H:i:s');
			$query = "INSERT INTO #__pizzabox_orders ( `user_id`, `status_id`, `datetime`  ) VALUES ( '$user->id', '0', '$date' )";
			$db->setQuery( $query );
			$db->query();
			$order_id = $db->insertid();
			$session->set( 'com_pizzabox.order.id', $order_id );
		}
				
		$container_id = $session->get('com_pizzabox.container.id');
		$container_name = $this->getElementName( 'containers', $container_id );
		
		$scheme_id = $session->get('com_pizzabox.scheme.id');
		$scheme_name = $this->getElementName( 'schemes', $scheme_id );

		$query = "INSERT INTO #__pizzabox_orders_parts ( `order_id`, `container_number`, `container_id`, `container_name`, `scheme_id`, `scheme_name`, `part_id`, `part_name`, `flavour_id`, `flavour_name` ) VALUES ";
		$values = array();
		foreach ( JRequest::getVar('parts') as $part_id ) {
			
			$part_name = $this->getElementName( 'parts', $part_id );

			$flavour_id = JRequest::getInt( 'flavours_'.$part_id );
			$flavour_name = $this->getElementName( 'flavours', $flavour_id );

			$values []=	"( '$order_id', '$container_number', '$container_id', '$container_name', '$scheme_id', '$scheme_name', '$part_id', '$part_name', '$flavour_id', '$flavour_name' )";
		}
		$query .= join( ",", $values );
		$db->setQuery( $query );
		$db->query();
		$session->set( 'com_pizzabox.container.number', $container_number );
	}
	
	function getElementName ( $element_type, $id ) 
	{
		if ( $element_type == 'parts' ) {
			$this->setId( $id );
			$element = $this->getItem();
			$element_name = $element['row']->name;
		} else {
			require_once ( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.$element_type.'.php' );
			$class_name = 'PizzaboxModel' . $element_type;
			$model = new $class_name();
			$model->setId( $id );
			$element = $model->getItem();
			$element_name = $element['row']->name;
		}
		return ( $element_name );
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
