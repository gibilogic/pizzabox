<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: helpers/pizzabox.php 2012-11-06 09:50:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
 
class PizzaboxHelper
{
  function __construct()
  {
    $this->params =& JComponentHelper::getParams('com_pizzabox');
  }
  
	function formatPrice( $price )
	{
    if(! $price ) return;
    
    if( $this->params->get('currency_symbol_position','before') == 'before' ) {
      return $this->params->get('currency_symbol') . " " . sprintf( "%.2f", $price );
    } else {
      return sprintf( "%.2f", $price ) . " " . $this->params->get('currency_symbol');
    }
  }
  
  function emailNotification( $order_id ) 
  {
    require_once( JPATH_COMPONENT_ADMINISTRATOR.'/models/orders.php');
    $model = new PizzaboxModelOrders();
    $model->setId( $order_id );
    $order = $model->getItem();
    
    $mailer = JFactory::getMailer();

    // Sender
    $config = JFactory::getConfig();
    $mailer->setSender( $config->get('mailfrom','pizzabox@gibilogic.com') );
    
    // Recipient(s)
		$recipient_email = $this->params->get('email_address','');
    if( $recipient_email ) {
      $mailer->addRecipient( $recipient_email );
    } else {
      foreach ( $this->_getSystemEmailAddresses() as $recipient ) {
        $mailer->addRecipient( $recipient->email, $recipient->name  );
      }
    }
    
    // Subject and body
    $link = JURI::root() . "/index.php?option=com_pizzabox&controller=orders&task=edit&id=$order_id";
    $mailer->setSubject( JText::_('PIZZABOX_EMAIL_NOTIFICATION_SUBJECT') );
    $mailer->setBody( '<html><body>' . JText::sprintf('PIZZABOX_EMAIL_NOTIFICATION_BODY',$link) . '</body></html>' );
		
		// Send message
		$mailer->IsHTML( true );
		$mailer->send();    
  }
  
  protected function _getSystemEmailAddresses()
  {
    $recipients = array();
    $query =  "SELECT `name`, `email` " .
              "FROM `#__users` " .
              "WHERE `sendEmail` = '1'";
    $db =& JFactory::getDBO();
    $db->setQuery( $query );
    if( $result = $db->loadObjectList() ) {
      $recipients = $result;
    
    }
  }
}
