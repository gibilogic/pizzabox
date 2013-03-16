<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: views/delivery/tmpl/default_delivery_name.php 2012-11-06 10:10:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
 
$document =& JFactory::getDocument();
$document->addScript( JURI::base() . "/components/com_pizzabox/assets/helper.js" );
?>

<h1><?php echo JText::_('PIZZABOX_DELIVERY_NAME_SELECT') ?></h1>

<form action="index.php?option=com_pizzabox&controller=delivery" method="post" name="deliveryForm" id="deliveryForm">
  
	<div class="delivery_name">
    
    <?php if( $this->time) : ?>
		<div class="delivery_date">
			<p class="info">
				<span class="date-default lead"><?php echo strftime( "%d %b %Y", $this->datets ) . ' ' . $this->time ?></span>
			</p>
		</div>
    <?php endif ?>
    
    <input type="text" name="delivery_name" id="delivery_name" />

    <div class="buttons">
      <a class="btn back" href="index.php?option=com_pizzabox&controller=delivery&task=selecttime"><?php echo JText::_('JPrev') ?></a>	
      <input class="btn btn-primary" type="submit" value="<?php echo JText::_('JSAVE') ?>" />
      <input type="hidden" name="task" value="save" />
      <input type="hidden" name="element" value="delivery_name" />
      <?php echo JHTML::_( 'form.token' ) ?>
    </div>

  </div>

</form>

<form id="adminForm2" name="adminForm2">
	<input type="submit" name="submit" class="btn btn-inverse back" value="<?php echo JText::_('PIZZABOX_LEAVE_ORDER') ?>" />
	<input type="hidden" name="option" value="com_pizzabox" />
	<input type="hidden" name="controller" value="delivery" />
	<input type="hidden" name="task" value="leave" />
	<input type="hidden" name="id" value="<?php echo $this->order_id ?>" />
</form>
<?php echo $this->sublayout['order_details']; ?>
