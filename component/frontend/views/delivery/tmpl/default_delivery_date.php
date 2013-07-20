<?php

/**
 * @version			  frontend/views/delivery/tmpl/default_delivery_date.php 2013-07-20 07:57:00Z zanardi
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
<h1><?php echo JText::_('PIZZABOX_DELIVERY_DATE_SELECT') ?></h1>
<form action="index.php?option=com_pizzabox&controller=delivery" method="post" id="deliveryForm" name="deliveryForm">
    <div class="buttons">
        <input type="submit" class="btn btn-primary" value="<?php echo JText::_('JNEXT') ?>" />
        <input type="hidden" name="option" value="com_pizzabox" />
        <input type="hidden" name="controller" value="delivery" />
        <input type="hidden" name="element" value="delivery_date" />
        <input type="hidden" name="task" value="" />
        <?php echo JHTML::_('form.token') ?>
    </div>
    <div class="delivery_date">
        <?php $i = 0 ?>
        <?php foreach ($this->dates as $value => $date) : ?>
            <?php $i++ ?>
            <div class="radiobox radio_<?php echo $i ?>">
                <label for="radio_<?php echo $i ?>">
                    <input type="radio" name="delivery_date" id="radio_<?php echo $i ?>" value="<?php echo $value ?>" />
                    <span class="date-default lead"><?php echo strftime("%d %b %Y", $date) ?></span>
                    <span class="date-dayshort"><?php echo strftime("%a", $date) ?></span>
                    <span class="date-daylong"><?php echo strftime("%A", $date) ?></span>
                    <span class="date-daynumber"><?php echo strftime("%d", $date) ?></span>
                    <span class="date-monthshort"><?php echo strftime("%b", $date) ?></span>
                    <span class="date-monthlong"><?php echo strftime("%B", $date) ?></span>
                    <span class="date-monthnumber"><?php echo strftime("%m", $date) ?></span>
                    <span class="date-year"><?php echo strftime("%Y", $date) ?></span>
                </label>
            </div>
        <?php endforeach ?>
    </div>
    <div class="buttons">
        <a href="index.php?option=com_pizzabox&controller=delivery&task=leave&id=<?php echo $this->order_id ?>" class="btn btn-inverse back"><?php echo JText::_('PIZZABOX_LEAVE_ORDER') ?></a>
        <input type="submit" class="btn btn-primary" value="<?php echo JText::_('JNEXT') ?>" />
    </div>
</form>
<?php echo $this->sublayout['order_details']; ?>
