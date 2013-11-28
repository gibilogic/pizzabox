<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: views/orders/tmpl/default_form.php 2012-09-18 09:16:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
$this->loadHelper('pizzabox');
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

<form action="<?php echo JRoute::_('index.php?option=com_pizzabox&controller=orders') ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminForm pizzabox">
		<tr>
			<td><?php echo JText::_('JGRID_HEADING_ID')?></td>
			<td><?php echo $this->row->id ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_ORDER_NAME')?></td>
			<td><?php echo $this->row->name ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('PIZZABOX_USER')?></td>
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
		<?php if( $this->row->isAdmin ) : ?>
		<tr>
			<td><label for"status"><?php echo JText::_('PIZZABOX_STATUS')?></label></td>
			<td><?php echo $this->lists['status'] ?></td>
		</tr>
		<?php endif ?>
	</table>
	<table class="adminForm pizzabox">
		<tr>
			<th style="width: 25%;text-align: left"><?php echo JText::_('PIZZABOX_CONTAINER') ?></th>
			<th style="width: 25%;text-align: left"><?php echo JText::_('PIZZABOX_PART') ?></th>
			<th style="width: 25%;text-align: left"><?php echo JText::_('PIZZABOX_FLAVOUR') ?></th>
		</tr>
    <?php $container_number = 0 ?>
    <?php $class = 'row1' ?>
		<?php foreach ( $this->parts as $part ) : ?>
      <?php if ( $part->container_number != $container_number ) {
        $container_number = $part->container_number;
        $class = ( $class == 'row1' ? '' : 'row1' );
      } else {
        $part->container_name = '';
        $part->container_image = '';
      } ?>
			<tr class="<?php echo $class ?>">
				<td><?php echo $part->container_name ?></td>
				<td><?php echo $part->part_name ?></td>
				<td><?php echo $part->flavour_name ?></td>
			</tr>
		<?php endforeach ?>
	</table>
    <?php if( $this->row->isAdmin ) : ?>
      <input type="submit" value="Submit" />
      <input type="hidden" name="task" value="save" />
      <input type="hidden" name="controller" value="orders" />
      <input type="hidden" name="id" value="<?php echo $this->row->id ?>" />
      <?php echo JHTML::_( 'form.token' ) ?>
    <?php else : ?>
      <a href="<?php echo JRoute::_('index.php?option=com_pizzabox&view=orders') ?>"><?php echo JText::_('PIZZABOX_ORDERS_BACK')?></a>
    <?php endif ?>
	</div>
</form>


