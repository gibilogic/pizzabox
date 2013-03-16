<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: views/delivery/tmpl/default_confirmed.php 2012-08-19 12:13:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
?>

<h1><?php echo JText::_('PIZZABOX_CONFIRMED') ?></h1>

<p><?php echo JText::_('PIZZABOX_ORDER_CONFIRMED') ?></p>

<!-- paypal section -->
<?php if( $this->params->get('paypal_enabled') ): ?>
  <div id="paypal">
    <form name="_xclick" action="https://www.paypal.com/it/cgi-bin/webscr" method="post">
      <input  type="hidden" 
              name="cmd" 
              value="_xclick">
      <input  type="hidden" 
              name="business" 
              value="<?php echo $this->params->get('paypal_business') ?>">
      <input  type="hidden" 
              name="currency_code" 
              value="<?php echo $this->params->get('currency'); ?>">
      <input  type="hidden" 
              name="item_name" 
              value="<?php echo $this->params->get('paypal_item_name_prefix','') . $this->order['row']->id ?>">
      <input  type="hidden" 
              name="amount" 
              value="<?php echo $this->order_total ?>">
      <input  type="image" 
              src="http://www.paypal.com/it_IT/i/btn/x-click-but01.gif" 
              border="0" 
              name="submit" 
              alt="<?php echo JText::_('PIZZABOX_PAYPAL') ?>">
    </form>
  </div>
<?php endif ?>
<!-- end paypal section -->

<?php echo $this->sublayout['order_details']; ?>

<p><a class="btn" href="<?php echo JRoute::_('index.php?view=orders') ?>"><?php echo JText::_('PIZZABOX_VIEW_ORDERS') ?></a></p>
