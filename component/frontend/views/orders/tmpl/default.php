<?php

/**
 * @version			  frontend/views/orders/tmpl/default.php 2013-11-24 17:36:00 UTC zanardi
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic <info@gibilogic.com>
 * @authorUrl		  http://www.gibilogic.com
 * @copyright		  (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

defined('_JEXEC') or die();

$this->loadHelper('pizzabox');
$helper = new PizzaboxHelper();
$rowsCount = count($this->items['rows']);
$altrow = 1;

?>

<?php if (!$this->is_admin) : ?>
<style type="text/css">
    a#showFilters{display: none}
    a#hideFilters{display: none}
    fieldset.filters{display: none}
</style>
<?php endif ?>

<form action="<?php echo JRoute::_('index.php?option=com_pizzabox&controller=orders') ?>" method="post" name="adminForm" id="adminForm">

    <div class="adminheading">
        <?php if ($this->is_admin): ?>
        <fieldset class="toggles">
            <a id="hideToolbar" href="#"><?php echo JText::_('PIZZABOX_HIDE_TOOLBAR') ?></a>
            <a id="showToolbar" href="#"><?php echo JText::_('PIZZABOX_SHOW_TOOLBAR') ?></a>
        </fieldset>
        <?php endif; ?>
        <fieldset class="filters">
            <?php echo JText::_('Filter') ?>
            <?php echo $this->is_admin ? $this->filters['user'] : '' ?>
            <?php echo $this->filters['status']; ?>
        </fieldset>
        <fieldset class="filters">
            <label for="filter_order_date_from"><?php echo JText::_('PIZZABOX_ORDER_DATE_FROM') ?>: </label>
            <?php echo JHTML::_('calendar', $this->filters['order_date_from'], 'filter_order_date_from', 'filter_order_date_from') ?>

            <label for="filter_order_date_to"><?php echo JText::_('To') ?>: </label>
            <?php echo JHTML::_('calendar', $this->filters['order_date_to'], 'filter_order_date_to', 'filter_order_date_to') ?>

            <input type="submit" value="<?php echo JText::_('Filter') ?>" />
        </fieldset>
        <fieldset class="filters">
            <label for="filter_delivery_date_from"><?php echo JText::_('PIZZABOX_DELIVERY_DATE_FROM') ?>: </label>
            <?php echo JHTML::_('calendar', $this->filters['delivery_date_from'], 'filter_delivery_date_from', 'filter_delivery_date_from') ?>

            <label for="filter_delivery_date_to"><?php echo JText::_('To') ?>: </label>
            <?php echo JHTML::_('calendar', $this->filters['delivery_date_to'], 'filter_delivery_date_to', 'filter_delivery_date_to') ?>

            <input type="submit" value="<?php echo JText::_('Filter') ?>" />
        </fieldset>
        <?php if ($this->is_admin) : ?>
        <fieldset class="filters">
            <input type="checkbox" name="change_status" />
            <?php echo JText::_('PIZZABOX_STATUS_CHANGE') ?>
            <?php echo $this->lists['status'] ?>
            <input type="submit" value="<?php echo JText::_('PIZZABOX_SUBMIT') ?>" />
        </fieldset>
        <?php endif; ?>
        <fieldset class="toggles">
            <a id="hideFilters" href="#"><?php echo JText::_('PIZZABOX_HIDE_FILTERS') ?></a>
            <a id="showFilters" href="#"><?php echo JText::_('PIZZABOX_SHOW_FILTERS') ?></a>
        </fieldset>
    </div>

    <table class="adminlist">
        <thead>
            <tr>
                <th><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $rowsCount ?>);" /></th>
                <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'PIZZABOX_ORDER_NUMBER', 'id', $this->filters['order_Dir'], $this->filters['order']) ?></th>
                <th nowrap="nowrap"><?php echo JText::_('PIZZABOX_USER') ?></th>
                <th nowrap="nowrap"><?php echo JText::_('PIZZABOX_STATUS') ?></th>
                <th nowrap="nowrap"><?php echo JText::_('PIZZABOX_DELIVERY') ?></th>
                <th nowrap="nowrap"><?php echo JText::_('PIZZABOX_DATETIME') ?></th>
                <th nowrap="nowrap"><?php echo JText::_('PIZZABOX_PRICE') ?></th>
                <th><?php echo JText::_('PIZZABOX_REPEAT') ?></th>
                <th><?php echo JText::_('PIZZABOX_DELETE') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items['rows'] as $k => $row) : ?>
                <?php $altrow = ( $altrow == 1 ) ? 0 : 1; ?>
                <tr class="row<?php echo $altrow ?>">
                    <td><?php echo JHTML::_('grid.id', $k, $row->id) ?></td>
                    <td width="20%">
                        <a href="#edit" onclick="return listItemTask('cb<?php echo $k ?>', 'edit')">
                            <?php echo JText::_('PIZZABOX_ORDER_NUMBER') . ' ' . $row->id ?>
                            <?php if ($row->name) echo "<br />($row->name)" ?>
                        </a>
                    </td>
                    <td><?php echo $row->user ?></td>
                    <td><?php echo $row->status ?></td>
                    <td><?php if ($row->delivery) echo strftime("%d %b %Y %H:%M", strtotime($row->delivery)) ?></td>
                    <td><?php if ($row->datetime) echo strftime("%d %b %Y %H:%M", strtotime($row->datetime)) ?></td>
                    <td><?php echo $helper->formatPrice($row->order_total) ?></td>
                    <td align="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_pizzabox&controller=orders&task=repeat&id=' . $row->id) ?>">
                            <img src="/components/com_pizzabox/assets/repeat.png" alt="<?php echo JText::_('PIZZABOX_REPEAT') ?>" />
                        </a>
                    </td>
                    <td align="center">
                        <?php if (!$row->lock) : ?>
                        <a href="#delete" onclick="if (confirm('<?php echo JText::_('PIZZABOX_CONFIRM_DELETE') ?>')) { return listItemTask('cb<?php echo $k ?>', 'remove'); }">
                            <img src="/components/com_pizzabox/assets/trash.png" alt="<?php echo JText::_('PIZZABOX_DELETE') ?>" />
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if ($this->is_admin) : ?>
                <!-- order details -->
                <tr class="row<?php echo $altrow ?>">
                    <td></td>
                    <td colspan="8">
                        <table class="table-order-detail">
                            <tr>
                                <th width="10%"><?php echo JText::_('PIZZABOX_CONTAINER_NUMBER') ?></th>
                                <th width="30%"><?php echo JText::_('PIZZABOX_CONTAINER') ?></th>
                                <th width="30%"><?php echo JText::_('PIZZABOX_PART') ?></th>
                                <th width="30%"><?php echo JText::_('PIZZABOX_FLAVOUR') ?></th>
                            </tr>
                            <?php foreach ($helper->convertOrderRows($row->parts) as $containerNumber => $container): ?>
                            <tr class="row-container-<?php echo $containerNumber ?>">
                                <td rowspan="<?php echo $container['rowspan'] ?>">
                                    <?php echo $containerNumber ?>
                                </td>
                                <td rowspan="<?php echo $container['rowspan'] ?>">
                                    <?php if (!empty($container['image'])): ?>
                                    <span class="image"><img src="<?php echo $container['image'] ?>" alt="<?php echo $container['name'] ?>" width="100" /></span>
                                    <?php endif; ?>
                                    <?php echo $container['name'] ?>
                                </td>
                                <?php foreach($container['parts'] as $part): ?>
                                <td rowspan="<?php echo count($part['flavours']) ?>">
                                    <?php if (!empty($part['image'])): ?>
                                    <span class="image"><img src="<?php echo $part['image'] ?>" alt="<?php echo $part['name'] ?>" width="100" /></span>
                                    <?php endif; ?>
                                    <?php echo $part['name'] ?>
                                </td>
                                <?php foreach($part['flavours'] as $flavour): ?>
                                <td>
                                    <?php if (!empty($flavour['image'])): ?>
                                    <span class="image"><img src="<?php echo $flavour['image'] ?>" alt="<?php echo $flavour['name'] ?>" width="100" /></span>
                                    <?php endif; ?>
                                    <?php echo $flavour['name'] ?>
                                    <?php if ($flavour['count'] > 1): ?>
                                    &nbsp;x<?php echo $flavour['count'] ?>
                                    <?php endif; ?>
                                </td>
                                </tr><tr class="row-container-<?php echo $containerNumber ?>">
                                <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <div class="limit"><?php echo JText::_('PIZZABOX_ITEMS_PER_PAGE') ?> : <?php echo $this->pagination->getLimitBox() ?></div>
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
        <?php echo JHTML::_('form.token') ?>
    </div>
</form>
