<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: views/orders/tmpl/default.php 2012-09-18 09:15:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */
$this->loadHelper('pizzabox');
$helper = new PizzaboxHelper();
$rowsCount = count( $this->items['rows'] ) ;
$altrow = 1;
?>

<?php if (! $this->is_admin ) : ?>
	<style type="text/css">
		a#showFilters{display: none}
		a#hideFilters{display: none}
		fieldset.filters{display: none}
	</style>
<?php endif ?>

<form action="index.php?option=com_pizzabox&controller=orders" method="post" name="adminForm" id="adminForm">

<div class="adminheading">
	<?php if ( $this->is_admin ) : ?>
	<fieldset class="toggles">
			<a id="hideToolbar" href="#"><?php echo JText::_('PIZZABOX_HIDE_TOOLBAR')?></a>
			<a id="showToolbar" href="#"><?php echo JText::_('PIZZABOX_SHOW_TOOLBAR')?></a>
	</fieldset>
	<?php endif ?>
	<fieldset class="filters">
		<?php echo JText::_('Filter') ?> 
		<?php if ( $this->is_admin ) { echo $this->filters['user']; } ?>
		<?php echo $this->filters['status']; ?>
	</fieldset>
	<fieldset class="filters">
		
		<label for="filter_order_date_from"><?php echo JText::_('PIZZABOX_ORDER_DATE_FROM')?>: </label>
		<?php echo JHTML::_('calendar', $this->filters['order_date_from'], 'filter_order_date_from', 'filter_order_date_from') ?>
		
		<label for="filter_order_date_to"><?php echo JText::_('To')?>: </label>
		<?php echo JHTML::_('calendar', $this->filters['order_date_to'], 'filter_order_date_to', 'filter_order_date_to') ?>
		
		<input type="submit" value="<?php echo JText::_('Filter')?>" />
	</fieldset>
	<fieldset class="filters">
		
		<label for="filter_delivery_date_from"><?php echo JText::_('PIZZABOX_DELIVERY_DATE_FROM')?>: </label>
		<?php echo JHTML::_('calendar', $this->filters['delivery_date_from'], 'filter_delivery_date_from', 'filter_delivery_date_from') ?>
		
		<label for="filter_delivery_date_to"><?php echo JText::_('To')?>: </label>
		<?php echo JHTML::_('calendar', $this->filters['delivery_date_to'], 'filter_delivery_date_to', 'filter_delivery_date_to') ?>
		
		<input type="submit" value="<?php echo JText::_('Filter')?>" />
	</fieldset>
	<?php if ( $this->is_admin ) : ?>
		<fieldset class="filters">
			<input type="checkbox" name="change_status">
			<?php echo JText::_('PIZZABOX_STATUS_CHANGE') ?> 
			<?php echo $this->lists['status'] ?> 
			<input type="submit" value="<?php echo JText::_('PIZZABOX_SUBMIT')?>" />
		</fieldset>
	<?php endif ?>
	<fieldset class="toggles">
		<a id="hideFilters" href="#"><?php echo JText::_('PIZZABOX_HIDE_FILTERS')?></a>
		<a id="showFilters" href="#"><?php echo JText::_('PIZZABOX_SHOW_FILTERS')?></a>
	</fieldset>
</div>

<table class="adminlist">
	<thead>
		<tr>
			<th>
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $rowsCount ?>);" />
      </th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PIZZABOX_ORDER_NUMBER', 'id', $this->filters['order_Dir'] , $this->filters['order'] ) ?>
			</th>
			<th nowrap="nowrap">
        <?php echo JText::_('PIZZABOX_USER') ?>
      </th>
			<th nowrap="nowrap">
        <?php echo JText::_('PIZZABOX_STATUS') ?>
      </th>
			<th nowrap="nowrap">
        <?php echo JText::_('PIZZABOX_DELIVERY') ?>
      </th>
			<th nowrap="nowrap">
        <?php echo JText::_('PIZZABOX_DATETIME') ?>
      </th>
			<th nowrap="nowrap">
        <?php echo JText::_('PIZZABOX_PRICE') ?>
      </th>
			<th>
        <?php echo JText::_('PIZZABOX_REPEAT') ?>
      </th>
			<th>
        <?php echo JText::_('PIZZABOX_DELETE') ?>
      </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items['rows'] as $k => $row) :?>

		<?php $altrow = ( $altrow == 1 )? 0 : 1; ?>

		<tr class="row<?php echo $altrow ?>">

			<td><?php echo JHTML::_('grid.checkedout', $row, $k ) ?></td>

			<td width="20%">
				<a href="#edit" onclick="return listItemTask('cb<?php echo $k ?>','edit')">
          <?php echo JText::_('PIZZABOX_ORDER_NUMBER') . ' ' . $row->id ?>
          <?php if( $row->name ) echo "<br />($row->name)" ?>
        </a>
			</td>

			<td><?php echo $row->user ?></td>
			
			<td><?php echo $row->status ?></td>
			
			<td><?php if( $row->delivery ) echo strftime( "%d %b %Y %H:%M", strtotime( $row->delivery ) ) ?></td>

			<td><?php if( $row->datetime ) echo strftime( "%d %b %Y %H:%M", strtotime( $row->datetime ) ) ?></td>
      
      <td><?php echo $helper->formatPrice( $row->order_total ) ?></td>

      <td align="center">
				<a href="<?php echo JRoute::_('index.php?option=com_pizzabox&controller=orders&task=repeat&id=' . $row->id ) ?>">
          <img src="/components/com_pizzabox/assets/repeat.png" alt="<?php echo JText::_('PIZZABOX_REPEAT') ?>" />
				</a>
			</td>
      
      <td align="center">
				<?php if ( ! $row->lock ) : ?>
					<a href="#delete" onclick="if(confirm('<?php echo JText::_('PIZZABOX_CONFIRM_DELETE') ?>')){return listItemTask('cb<?php echo $k ?>','remove')}">
            <img src="/components/com_pizzabox/assets/trash.png" alt="<?php echo JText::_('PIZZABOX_DELETE') ?>" />
          </a>
				<?php endif ?>
			</td>
		</tr>
		
		<?php if ( $this->is_admin ) : ?>
		<!-- order details -->
		<tr class="row<?php echo $altrow ?>">
			<td></td>
			<td colspan="6">
				<table>
					<tr>
						<th><?php echo JText::_('PIZZABOX_CONTAINER_NUMBER') ?></th>
						<th><?php echo JText::_('PIZZABOX_CONTAINER') ?></th>
						<th><?php echo JText::_('PIZZABOX_SCHEME') ?></th>
						<th><?php echo JText::_('PIZZABOX_PART') ?></th>
						<th><?php echo JText::_('PIZZABOX_FLAVOUR') ?></th>
					</tr>
					<?php $container_number = 0 ?>
					<?php $class = 'detail-row1' ?>
					<?php foreach ( $row->parts as $part ) : ?>
					<?php 
					if ( $part->container_number != $container_number ) {
						?><tr><td colspan="5"><hr /></td></tr><?php
						$container_number = $part->container_number;
						$class = ( $class == 'detail-row1' ? '' : 'detail-row1' );
					} else {
						$part->container_number = '';
						$part->container_name = '';
						$part->scheme_name = '';
					} 
					?>
					<tr class="<?php echo $class ?>">
						<td><?php echo $part->container_number ?></td>
						<td><?php echo $part->container_name ?></td>
						<td><?php echo $part->scheme_name ?></td>
						<td><?php echo $part->part_name ?></td>
						<td><?php echo $part->flavour_name ?></td>
					</tr>
					<?php endforeach ?>
				</table>
			</td>
		</tr>
		<?php endif ?>

	<?php endforeach ?>
	</tbody>
</table>

<div class="pagination">
	<div class="limit"><?php echo JText::_('PIZZABOX_ITEMS_PER_PAGE')?> : <?php echo $this->pagination->getLimitBox() ?></div>
	<?php echo $this->pagination->getPagesLinks() ?>
	<div class="limit"><?php echo $this->pagination->getPagesCounter() ?></div>
</div>

<div>
	<input type="hidden" name="option" value="com_pizzabox" />
	<input type="hidden" name="task" value="" /> 
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="ordering" value="ordering" />
	<input type="hidden" name="filter_order" value="<?php echo $this->filters['order'] ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filters['order_Dir'] ?>" />
	<?php echo JHTML::_( 'form.token' ) ?>
</div>

</form>
