<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: views/delivery/tmpl/default_delivery_time.php 2012-09-18 09:11:00Z zanardi $
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

<h1><?php echo JText::_('PIZZABOX_DELIVERY_TIME_SELECT') ?></h1>

<form action="index.php?option=com_pizzabox&controller=delivery" method="post" name="deliveryForm" id="deliveryForm">
			
	<div class="delivery_time">
	
		<div class="delivery_date">
			<p class="info">
				<span class="date-default lead"><?php echo strftime( "%d %b %Y", $this->datets ) ?></span>
				<span class="date-dayshort"><?php echo strftime( "%a", $this->datets ) ?></span>
				<span class="date-daylong"><?php echo strftime( "%A", $this->datets ) ?></span>
				<span class="date-daynumber"><?php echo strftime( "%d", $this->datets ) ?></span>
				<span class="date-monthshort"><?php echo strftime( "%b", $this->datets ) ?></span>
				<span class="date-monthlong"><?php echo strftime( "%B", $this->datets ) ?></span>
				<span class="date-monthnumber"><?php echo strftime( "%m", $this->datets ) ?></span>
				<span class="date-year"><?php echo strftime( "%Y", $this->datets ) ?></span>
			</p>
		</div>

    <div class="buttons">
      <a class="btn back" href="index.php?option=com_pizzabox&controller=delivery&task=selectdate"><?php echo JText::_('JPrev') ?></a>	
      <input class="btn btn-primary" type="submit" value="<?php echo JText::_('JNEXT') ?>" />
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="element" value="delivery_time" />
      <input type="hidden" name="option" value="com_pizzabox" />
      <input type="hidden" name="controller" value="delivery" />
      <?php echo JHTML::_( 'form.token' ) ?>
    </div>

		<?php if( count( $this->times ) ) :?>
			<?php $i=0 ?>
			<?php foreach ($this->times as $value ) :?>
				<?php $i++ ?>
				<div class="radiobox radio_<?php echo $i ?>">
					<input type="radio" name="delivery_time" id="radio_<?php echo $i ?>" value="<?php echo $value ?>" />
					<label class="lead" for="radio_<?php echo $i ?>"><?php echo $value ?></label>
				</div>
			<?php endforeach ?>
		<?php else : ?>
			<p class="info"><?php echo JText::_('PIZZABOX_NO_TIMES_AVAILABLE') ?></p>
		<?php endif ?>
	</div>

	<div class="buttons">
		<a class="btn back" href="index.php?option=com_pizzabox&controller=delivery&task=selectdate"><?php echo JText::_('JPrev') ?></a>	
		<input class="btn btn-primary" type="submit" value="<?php echo JText::_('JNEXT') ?>" />
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
