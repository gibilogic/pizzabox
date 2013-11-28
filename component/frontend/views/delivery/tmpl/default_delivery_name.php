<?php

/**
 * @version			  frontend/views/delivery/tmpl/default_delivery_name.php 2013-07-20 07:57:00Z zanardi
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2013 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut');

$document = & JFactory::getDocument();
$document->addScript(JURI::base() . "/components/com_pizzabox/assets/helper.js");

?>
<h1><?php echo JText::_('PIZZABOX_DELIVERY_NAME_SELECT' . ($this->isOrderNameMandatory ? '_MANDATORY' : '')) ?></h1>
<?php if ($this->time) : ?>
<div class="delivery_date">
    <p class="info">
        <span class="date-default lead"><?php echo strftime("%d %b %Y", $this->datets) . ' ' . $this->time ?></span>
    </p>
</div>
<?php endif ?>

<form action="index.php?option=com_pizzabox&controller=delivery" method="post" name="deliveryForm" id="deliveryForm">
    <fieldset>
        <legend><?php echo JText::_('PIZZABOX_DELIVERY_NAME_SELECT' . ($this->isOrderNameMandatory ? '_MANDATORY' : '')) ?></legend>
        <input type="text" name="delivery_name" id="delivery_name" maxlength="50" <?php echo $this->isOrderNameMandatory ? 'required="required"' : '' ?>/><?php echo $this->isOrderNameMandatory ? '*' : '' ?>
    </fieldset>

    <?php if ($this->showAddress): ?>
    <fieldset>
        <legend><?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS') ?></legend>
        <div style="width: 45%; float: left;">
            <label for="address[name]"><?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS_NAME') ?></label>
            <input type="text" name="address[name]" value="" maxlength="50" />
            <label for="address[street]"><?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS_STREET') ?></label>
            <input type="text" name="address[street]" value="" />
            <label for="address[zip]"><?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS_ZIP') ?></label>
            <input type="text" name="address[zip]" value="" maxlength="6" />
            <label for="address[city]"><?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS_CITY') ?></label>
            <input type="text" name="address[city]" value="" />
            <label for="address[state]"><?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS_STATE') ?></label>
            <input type="text" name="address[state]" value="" />
            <label for="address[country]"><?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS_COUNTRY') ?></label>
            <input type="text" name="address[country]" value="" />
            <input type="hidden" name="address[user_id]" value="<?php echo $this->user_id ?>" />
        </div>
        <div style="width: 45%; float: right;">
            <input type="radio" name="old_address" value="0" checked="checked" /> <?php echo JText::_('PIZZABOX_DELIVERY_ADDRESS_EMPTY') ?>
            <?php foreach ($this->addresses as $address): ?>
            <p>
                <input type="radio" name="old_address" value="<?php echo $address->id ?>" />&nbsp;<?php echo $address->name ?> <?php echo $address->street ?>, <?php echo $address->zip ?> <?php echo $address->city ?> (<?php echo $address->state ?>) - <?php echo $address->country ?>
            </p>
            <?php endforeach; ?>
        </div>
    </fieldset>
    <?php else: ?>
    <input type="hidden" name="old_address" value="0" />
    <?php endif; ?>

    <div class="buttons">
        <a href="index.php?option=com_pizzabox&controller=delivery&task=leave&id=<?php echo $this->order_id ?>" class="btn btn-inverse back"><?php echo JText::_('PIZZABOX_LEAVE_ORDER') ?></a>
        <a class="btn back" href="index.php?option=com_pizzabox&controller=delivery&task=selecttime"><?php echo JText::_('JPrev') ?></a>
        <input class="btn btn-primary" type="submit" value="<?php echo JText::_('JSAVE') ?>" />
        <input type="hidden" name="task" value="save" />
        <input type="hidden" name="element" value="delivery_name" />
        <?php echo JHTML::_('form.token') ?>
    </div>
</form>
<?php echo $this->sublayout['order_details']; ?>
