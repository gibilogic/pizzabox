<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: views/parts/tmpl/default.php 2012-08-14 15:15:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
$rowsCount = count( $this->rows ) ;
$altrow = 1;
?>

<script type="text/javascript">
function resetFilter() {
	$('search').set('value','');
	$('filter_containers').set('value','');
	$('filter_schemes').set('value','');
	$('adminForm').submit();
}
</script>

<form action="index.php?option=com_pizzabox&controller=parts" method="post" name="adminForm" id="adminForm">

<table class="adminheading">
	<tr>
		<td>
			<label for="search" ><?php echo JText::_('JSEARCH_FILTER') ?></label> : 
			<input type="text" id="search" name="search" size="8" value="<?php echo $this->search ?>" /> 
			<input type="submit" value="<?php echo JText::_('JSEARCH_FILTER_SUBMIT') ?>" />
			<?php echo JText::_('JSEARCH_FILTER_LABEL') ?> 
			<?php echo $this->filters['containers']; ?>
			<?php echo $this->filters['schemes']; ?>
			<button onclick="resetFilter();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
		</td>
	</tr>
</table>

<table class="adminlist">
	<thead>
		<tr>
			<th style="width: 1%">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $rowsCount ?>);" />
      </th>
			<th nowrap="nowrap" style="width: 5%">
				<?php echo JHTML::_('grid.sort', 'JGLOBAL_FIELD_ID_LABEL', 'id', $this->filters['order_Dir'] , $this->filters['order'] ) ?>
			</th>     
      <th nowrap="nowrap" style="width: 5%">
				<?php echo JHTML::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $this->filters['order_Dir'] , $this->filters['order'] ) ?>
			</th>
      <th nowrap="nowrap" style="width: 5%">
				<?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'published', $this->filters['order_Dir'] , $this->filters['order'] ) ?>
			</th>
      <th nowrap="nowrap" style="width: 5%">
				<?php echo JText::_('PIZZABOX_EDIT') ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PIZZABOX_NAME', 'name', $this->filters['order_Dir'] , $this->filters['order'] ) ?>
			</th>
			<th nowrap="nowrap" style="width: 10%">
        <?php echo JText::_('PIZZABOX_CONTAINER') ?>
      </th>
			<th nowrap="nowrap" style="width: 10%">
        <?php echo JText::_('PIZZABOX_SCHEME') ?>
      </th>
 			<th nowrap="nowrap" style="width: 5%">
        <?php echo JText::_('PIZZABOX_PRICE') ?>
      </th>
			<th nowrap="nowrap">
        <?php echo JText::_('PIZZABOX_DESCRIPTION') ?>
      </th>
			<th nowrap="nowrap">
        <?php echo JText::_('PIZZABOX_IMAGE') ?>
      </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->rows as $k => $row) :?>

		<?php $altrow = ( $altrow == 1 )? 0 : 1; ?>

		<tr class="row<?php echo $altrow ?>">

			<td><?php echo JHTML::_('grid.checkedout', $row, $k ) ?></td>
      
      <td align="center"><?php echo $row->id ?></td>

			<td class="order" align="center">
				<span><?php echo $this->pagination->orderUpIcon( $k, true,'orderup', 'Move Up', ($this->order == 'ordering' && $this->order_dir == 'asc') ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $k, $rowsCount, true, 'orderdown', 'Move Down', ($this->order == 'ordering' && $this->order_dir == 'asc') ); ?></span>
        <?php echo $row->ordering ?>
			</td>

      <td align="center"><?php echo JHTML::_('grid.published', $row, $k, 'publish_g.png') ?></td>

			<td align="center">
				<a href="#edit" onclick="return listItemTask('cb<?php echo $k ?>','edit')">
          <img src="/components/com_pizzabox/assets/icon-16-edit.png" alt="<?php echo JText::_('JEDIT') ?>">
        </a>
			</td>

			<td>
				<a href="#edit" onclick="return listItemTask('cb<?php echo $k ?>','edit')">
          <?php echo $this->escape( $row->name ) ?>
        </a>
			</td>

			<td><?php echo $row->container_name ?></td>
			
			<td><?php echo $row->scheme_name ?></td>

			<td><?php echo $row->price ?></td>

			<td><?php echo $row->desc ?></td>

			<td style="text-align: center">
				<?php if ( $row->image ) : ?>
					<img src="/<?php echo $row->image ?>" alt="" />
				<?php endif ?>
			</td>
		</tr>

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
	<input type="hidden" name="limitstart" value="<?php echo $this->limitstart ?>" />
	<?php echo JHTML::_( 'form.token' ) ?>
</div>

</form>
