<?php

/**
 * @version			  $Id: views/orders/tmpl/default.php 2012-09-18 09:30:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_pizzabox' . DS . 'helpers' . DS . 'pizzabox.php');
$helper = new PizzaboxHelper();
$rowsCount = count($this->rows);
$altrow = 1;

?>
<form action="index.php?option=com_pizzabox&controller=orders" method="post" name="adminForm" id="adminForm">
	<table class="adminheading">
		<tr>
			<td>
				<?php echo JText::_('JSEARCH_FILTER_LABEL') ?>
			</td>
			<td>
				<?php echo $this->filters['user']; ?>
			</td>
			<td>
				<?php echo $this->filters['status']; ?>
			</td>
			<td>
				&nbsp;&nbsp;
				<label for="filter_order_date_from"><?php echo JText::_('PIZZABOX_ORDER_DATE_FROM') ?>: </label><input type="text" id="filter_order_date_from" name="filter_order_date_from" value="<?php echo $this->filters['order_date_from'] ?>" />
				<label for="filter_order_date_to"><?php echo JText::_('PIZZABOX_ORDER_DATE_TO') ?>: </label><input type="text" id="filter_order_date_to" name="filter_order_date_to" value="<?php echo $this->filters['order_date_to'] ?>"/>
			</td>
			<td>
				&nbsp;&nbsp;
				<label for="filter_delivery_date_from"><?php echo JText::_('PIZZABOX_DELIVERY_DATE_FROM') ?>: </label><input type="text" id="filter_delivery_date_from" name="filter_delivery_date_from" value="<?php echo $this->filters['delivery_date_from'] ?>" />
				<label for="filter_delivery_date_to"><?php echo JText::_('PIZZABOX_DELIVERY_DATE_TO') ?>: </label><input type="text" id="filter_delivery_date_to" name="filter_delivery_date_to" value="<?php echo $this->filters['delivery_date_to'] ?>"/>
			</td>
			<td>
				<input type="submit" value="<?php echo JText::_('JSEARCH_FILTER_SUBMIT') ?>" />
			</td>
		</tr>
	</table>

	<table class="adminlist">
		<thead>
			<tr>
				<th style="text-align:left"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $rowsCount ?>);" /></th>
				<th><?php echo JHTML::_('grid.sort', 'PIZZABOX_ORDER_NUMBER', 'id', $this->filters['order_Dir'], $this->filters['order']) ?></th>
				<th><?php echo JText::_('PIZZABOX_ORDER_NAME') ?></th>
				<th><?php echo JText::_('User') ?></th>
				<th><?php echo JText::_('PIZZABOX_STATUS') ?></th>
				<th><?php echo JText::_('PIZZABOX_DELIVERY') ?></th>
				<th><?php echo JText::_('PIZZABOX_DATETIME') ?></th>
				<th><?php echo JText::_('PIZZABOX_PRICE') ?></th>
				<th><?php echo JHTML::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $this->filters['order_Dir'], $this->filters['order']) ?></th>
				<th><?php echo JText::_('Delete') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->rows as $k => $row) : ?>
			<?php $altrow = ( $altrow == 1 ) ? 0 : 1; ?>
			<tr class="row<?php echo $altrow ?>">
				<td><?php echo JHTML::_('grid.checkedout', $row, $k) ?></td>
				<td width="20%">
					<a href="#edit" onclick="return listItemTask('cb<?php echo $k ?>', 'edit')"><?php echo JText::_('PIZZABOX_ORDER_NUMBER') . ' ' . $row->id ?></a>
				</td>
				<td><?php echo $row->name ?></td>
				<td><?php echo $row->user ?></td>
				<td><?php echo $row->status ?></td>
				<td><?php echo $row->delivery ?></td>
				<td><?php echo $row->datetime ?></td>
				<td><?php echo $helper->formatPrice($row->order_total) ?></td>
				<td class="order" align="center">
					<span><?php echo $this->pagination->orderUpIcon($k, true, 'orderup', 'Move Up', ($this->order == 'ordering' && $this->order_dir == 'asc')); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($k, $rowsCount, true, 'orderdown', 'Move Down', ($this->order == 'ordering' && $this->order_dir == 'asc')); ?></span>
					<?php echo $row->ordering ?>
				</td>
				<td align="center">
					<a href="#delete" onclick="if (confirm('<?php echo JText::_('PIZZABOX_CONFIRM_DELETE') ?>')) { return listItemTask('cb<?php echo $k ?>', 'remove'); }">
						<img src="/components/com_pizzabox/assets/trash.png" alt="<?php echo JText::_('Delete') ?>" />
					</a>
				</td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<div class="change-status-container">
		<input type="checkbox" name="change_status" />
		<?php echo JText::_('PIZZABOX_STATUS_CHANGE') ?>
		<?php echo $this->lists['status'] ?>
		<input type="submit" value="<?php echo JText::_('PIZZABOX_SUBMIT') ?>" />
	</div>
	<div class="pagination">
		<div class="limit"><?php echo JText::_('PIZZABOX_ITEMS_PER_PAGE') ?> : <?php echo $this->pagination->getLimitBox() ?></div>
		<?php echo $this->pagination->getPagesLinks() ?>
		<div class="limit"><?php echo $this->pagination->getPagesCounter() ?></div>
	</div>

	<input type="hidden" name="option" value="com_pizzabox" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="ordering" value="ordering" />
	<input type="hidden" name="filter_order" value="<?php echo $this->filters['order'] ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filters['order_Dir'] ?>" />
	<input type="hidden" name="limitstart" value="<?php echo $this->limitstart ?>" />
	<?php echo JHTML::_('form.token') ?>
</form>
