<?php
/**
 * @version      frontend/layouts/_order_details.php 2013-07-07 19:57:00Z zanardi
 * @package      GiBi PizzaBox
 * @author       GiBiLogic <info@gibilogic.com>
 * @authorUrl    http://www.gibilogic.com
 * @copyright    Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license      GNU/GPL v2 or later
 */

defined('_JEXEC') or die('Restricted access');

?>
<script type="text/javascript">
var deleteContainerUrl = 'index.php?option=com_pizzabox&controller=delivery&task=removerow';

window.addEvent('domready', function() {
    $$('.btn_delete_container').addEvent('click', function(event) {
        var container = event.target.get('data-container');
        if (!event.target.hasClass('btn_delete_container')) {
            container = event.target.parentElement.get('data-container');
        }

        if (confirm('<?php echo JText::_('PIZZABOX_CONFIRM_DELETE') ?>')) {
            var req = new Request({
                url: deleteContainerUrl,
                method: 'get',
                data: {
                    "container": container
                },
                onSuccess: function(response) {
                    if (response === 'Ok') {
                        $$('tr.row-container-' + container).dispose();

                        if ($$('#order_details tbody tr').length == 0 && document.adminForm2)
                        {
                            document.adminForm2.submit();
                        }

                        window.location.reload(true);
                    }
                }
            }).send();
        }
    });
});
</script>

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
        <?php $container_number = 0 ?>
        <?php $class = 'row1' ?>
        <?php foreach ($this->parts as $part): ?>
            <?php
            if ($part->container_number != $container_number)
            {
                $container_number = $part->container_number;
                $class = ( $class == 'row1' ? '' : 'row1' );
            }
            else
            {
                $part->container_name = '';
                $part->container_image = '';
            }
            ?>
            <tr class="<?php echo $class ?> row-container-<?php echo $container_number ?>">
                <?php if ($this->tpl != 'confirmed'): ?>
                <td>
                    <?php if ($part->container_name != ''): ?>
                        <button class="btn btn-danger btn_delete_container" data-container="<?php echo $container_number ?>">
                            <i class="icon-remove icon-white"></i>
                        </button>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <td class="">
                    <?php if ($part->container_image) : ?>
                        <span class="image"><img src="<?php echo $part->container_image ?>" alt="Container image" width="100" /></span>
                    <?php endif ?>
                    <?php echo $part->container_name ?>
                </td>
                <td class="">
                    <?php if ($part->part_image) : ?>
                        <span class="image"><img src="<?php echo $part->part_image ?>" alt="Part image" width="100" /></span>
                    <?php endif ?>
                    <?php echo $part->part_name ?>
                </td>
                <td class="">
                    <?php if ($part->flavour_image) : ?>
                        <span class="image"><img src="<?php echo $part->flavour_image ?>" alt="Flavour image" width="100" /></span>
                    <?php endif ?>
                    <?php echo $part->flavour_name ?>
                </td>
            </tr>
        <?php endforeach ?>
        <?php if (count($this->parts) == 0): ?>
            <tr>
                <td colspan="4" style="text-align:center;"><em><?php echo JText::_('PIZZABOX_EMPTY_ORDER') ?></em></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
