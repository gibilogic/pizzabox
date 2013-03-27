<?php

/**
 * @version			$Id: layouts/_order_details.php 2012-11-06 11:14:00Z zanardi $
 * @package			PizzaBox
 * @copyright		Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

?>
<h3><?php echo JText::_('PIZZABOX_ORDER_DETAILS') ?></h3>
<?php if ($this->tpl == 'confirmed') : ?>
	<?php
	if ($this->order['row']->delivery != '0000-00-00 00:00:00') {
		$delivery = new DateTime($this->order['row']->delivery);
		$date = $delivery->format('d/m/Y');
		$time = $delivery->format('H:i');
	}
	else {
		$date = '';
		$time = '';
	}
	?>
	<p><?php echo JText::_('PIZZABOX_ORDER_TOTAL') ?>: <strong><?php echo $this->helper->formatPrice($this->order_total) ?></strong></p>
	<?php if ($date && $time): ?>}
		<p><?php echo JText::sprintf('PIZZABOX_DELIVERY_DATETIME', $date, $time) ?></p>
	<?php endif ?>
	<p><?php echo JText::_('PIZZABOX_ORDER_NAME') ?>: <strong><?php echo $this->order['row']->name ?></strong></p>
<?php else : ?>
	<p class="info"><?php echo JText::_('PIZZABOX_ORDER_NOT_CONFIRMED_YET') ?></p>
<?php endif ?>

<table id="order_details" class="adminlist">
	<tr>
		<th><?php echo JText::_('PIZZABOX_CONTAINER') ?></th>
		<th><?php echo JText::_('PIZZABOX_PART') ?></th>
		<th><?php echo JText::_('PIZZABOX_FLAVOUR') ?></th>
	</tr>
	<?php $container_number = 0 ?>
	<?php $class = 'row1' ?>
	<?php foreach ($this->parts as $part): ?>
		<?php
		if ($part->container_number != $container_number) {
			$container_number = $part->container_number;
			$class = ( $class == 'row1' ? '' : 'row1' );
		}
		else {
			$part->container_name = '';
			$part->container_image = '';
		}
		?>
		<tr class="<?php echo $class ?>">
			<td class="container">
				<?php if ($part->container_image) : ?>
				<span class="image"><img src="<?php echo $part->container_image ?>" alt="Container image" /></span>
				<?php endif ?>
				<?php echo $part->container_name ?>
			</td>
			<td class="part">
				<?php if ($part->part_image) : ?>
				<span class="image"><img src="<?php echo $part->part_image ?>" alt="Part image" /></span>
				<?php endif ?>
				<?php echo $part->part_name ?>
			</td>
			<td class="flavour">
				<?php if ($part->flavour_image) : ?>
				<span class="image"><img src="<?php echo $part->flavour_image ?>" alt="Flavour image" /></span>
				<?php endif ?>
				<?php echo $part->flavour_name ?>
			</td>
		</tr>
	<?php endforeach ?>
</table>
