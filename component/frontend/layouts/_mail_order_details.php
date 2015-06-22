<?php
/**
 * @version      frontend/layouts/_mail_order_details.php 2015-06-22 16:20:00Z zanardi
 * @package      GiBi PizzaBox
 * @author       GiBiLogic <info@gibilogic.com>
 * @authorUrl    http://www.gibilogic.com
 * @copyright    Copyright (C) 2015 GiBiLogic. All rights reserved.
 * @license      GNU/GPL v3 or later
 */

defined('_JEXEC') or die('Restricted access');

?>
<h3><?php echo JText::_('PIZZABOX_ORDER_DETAILS') ?></h3>
<p><?php echo JText::_('PIZZABOX_ORDER_TOTAL') ?>: <strong><?php echo $this->helper->formatPrice($this->order_total) ?></strong></p>

<?php if ($this->tpl == 'confirmed') : ?>
    <?php
    if ($this->order['row']->delivery != '0000-00-00 00:00:00')
    {
        $delivery = new DateTime($this->order['row']->delivery);
        $date = $delivery->format('d/m/Y');
        $time = $delivery->format('H:i');
    }
    else
    {
        $date = '';
        $time = '';
    }
    ?>
    <?php if ($date && $time): ?>
    <p><?php echo JText::sprintf('PIZZABOX_DELIVERY_DATETIME', $date, $time) ?></p>
    <?php endif ?>
    <p><?php echo JText::_('PIZZABOX_ORDER_NAME') ?>: <strong><?php echo $this->order['row']->name ?></strong></p>
<?php else : ?>
    <p class="info"><?php echo JText::_('PIZZABOX_ORDER_NOT_CONFIRMED_YET') ?></p>
<?php endif ?>

<table id="order_details" class="adminlist">
    <thead>
        <tr>
            <?php if ($this->tpl != 'confirmed'): ?>
            <th></th>
            <?php endif; ?>
            <th><?php echo JText::_('PIZZABOX_CONTAINER') ?></th>
            <th><?php echo JText::_('PIZZABOX_PART') ?></th>
            <th><?php echo JText::_('PIZZABOX_FLAVOUR') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->orderData as $containerNumber => $container): ?>
        <tr class="<?php echo ($containerNumber % 2 != 0 ? 'row1' : '') ?> row-container-<?php echo $containerNumber ?>">
            <?php if ($this->tpl != 'confirmed'): ?>
            <td rowspan="<?php echo $container['rowspan'] ?>" style="vertical-align:top;">
                <button class="btn btn-danger btn_delete_container" data-container="<?php echo $containerNumber ?>">
                    <i class="icon-remove icon-white"></i>
                </button>
            </td>
            <?php endif; ?>
            <td rowspan="<?php echo $container['rowspan'] ?>" style="vertical-align:top;">
                <?php if (!empty($container['image'])): ?>
                <span class="image"><img src="<?php echo $container['image'] ?>" alt="<?php echo $container['name'] ?>" width="100" /></span>
                <?php endif; ?>
                <?php echo $container['name'] ?>
            </td>

            <?php foreach($container['parts'] as $part): ?>
            <td rowspan="<?php echo count($part['flavours']) ?>" style="vertical-align:top;">
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
            </tr><tr class="<?php echo ($containerNumber % 2 != 0 ? 'row1' : '') ?> row-container-<?php echo $containerNumber ?>">
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>

        <?php if (count($this->parts) == 0): ?>
        <tr>
            <td colspan="4" style="text-align:center;">
                <em><?php echo JText::_('PIZZABOX_EMPTY_ORDER') ?></em>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
