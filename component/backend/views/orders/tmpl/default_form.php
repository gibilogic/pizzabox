<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: views/orders/tmpl/default_form.php 2012-09-18 09:30:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
require_once( JPATH_ROOT.DS.'components'.DS.'com_pizzabox'.DS.'helpers'.DS.'pizzabox.php');
$helper = new PizzaboxHelper();
?>

<style type="text/css">
	tr.odd {background-color: #ccc}
</style>

<script language="javascript" type="text/javascript">

function submitbutton( pressbutton, section ) {
	var form = document.adminForm;
	submitform(pressbutton);
	return;
}

</script>

<form action="index.php?option=com_pizzabox&controller=orders" method="post" name="adminForm" id="adminForm">
	<table class="adminForm">
		<tr>
			<td><?php echo JText::_('Id')?></td>
			<td><?php echo $this->row->id ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_ORDER_NAME')?></td>
			<td><?php echo $this->row->name ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('User')?></td>
			<td><?php echo $this->row->user ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_DELIVERY')?></td>
			<td><?php echo $this->row->delivery ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_DATETIME')?></td>
			<td><?php echo $this->row->datetime ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_PRICE')?></td>
			<td><?php echo $helper->formatPrice( $this->row->order_total ) ?></td>
		</tr>
    <tr>
			<td><label for"status"><?php echo JText::_('PIZZABOX_STATUS')?></label></td>
			<td><?php echo $this->lists['status'] ?></td>
		</tr>
	</table>

	<?php if ($this->row->address_id): ?>
	<h3><?php echo JText::_('PIZZABOX_ADDRESS')?></h3>
	<table class="adminForm">
		<tr>
			<td><?php echo JText::_('PIZZABOX_ADDRESS_NAME')?></td>
			<td><?php echo $this->address->name ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_ADDRESS_STREET')?></td>
			<td><?php echo $this->address->street ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_ADDRESS_ZIP')?></td>
			<td><?php echo $this->address->zip ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_ADDRESS_CITY')?></td>
			<td><?php echo $this->address->city ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_ADDRESS_STATE')?></td>
			<td><?php echo $this->address->state ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_ADDRESS_COUNTRY')?></td>
			<td><?php echo $this->address->country ?></td>
		</tr>
	</table>
	<?php endif; ?>

	<br />
	<table class="adminForm" style="width: 50%">
		<tr>
			<th style="width: 25%;text-align: left"><?php echo JText::_('PIZZABOX_CONTAINER') ?></th>
			<th style="width: 25%;text-align: left"><?php echo JText::_('PIZZABOX_SCHEME') ?></th>
			<th style="width: 25%;text-align: left"><?php echo JText::_('PIZZABOX_PART') ?></th>
			<th style="width: 25%;text-align: left"><?php echo JText::_('PIZZABOX_FLAVOUR') ?></th>
		</tr>
		<?php $class = 'even' ?>
		<?php foreach ( $this->parts as $item ) : ?>
			<? $class = ( $class == 'odd' ? 'even' : 'odd' ) ?>
			<tr class="<?php echo $class ?>">
				<td><?php echo $item->container_name ?></td>
				<td><?php echo $item->scheme_name ?></td>
				<td><?php echo $item->part_name ?></td>
				<td><?php echo $item->flavour_name ?></td>
			</tr>
		<?php endforeach ?>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="orders" />
	<input type="hidden" name="id" value="<?php echo $this->row->id ?>" />
	<?php echo JHTML::_( 'form.token' ) ?>
</form>
