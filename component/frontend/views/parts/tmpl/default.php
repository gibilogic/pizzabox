<?php

/**
 * @version		    $Id: views/parts/tmpl/default.php 2012-08-19 09:50:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');
$this->loadHelper('pizzabox');
$helper = new PizzaboxHelper();

?>
<script type="text/javascript">

window.addEvent('domready', function() {
	var ranges = <?php echo $this->ranges ?>;

	$$('input[type="submit"]').set('disabled', 'disabled');

	$$('.submitrestart').addEvent('click', function(){
		$('restart').set('value','yes');
	});

	$$('.flavour_checkbox').addEvent('change', function() {
		var isValid = true;

		Array.each(ranges, function(range) {
			var selected_count = $$('input[name*="flavours_' + range.part_id + '"]:checked').length;
			if (selected_count >= parseInt(range.maximum)) {
				$$('input[name*="flavours_' + range.part_id + '"]:not(:checked)').set('disabled', 'disabled');
			}
			else {
				$$('input[name*="flavours_' + range.part_id + '"]:not(:checked)').set('disabled', '');
			}

			if (selected_count < parseInt(range.minimum)) {
				isValid = false;
			}
		});

		$$('input[type="submit"]').set('disabled', isValid ? '': 'disabled');
	});
});

</script>
<h1><?php echo JText::_('PIZZABOX_FLAVOURS_SELECT') ?></h1>
<form action="index.php?option=com_pizzabox&controller=parts" method="post" name="partForm" id="partForm">
	<div class="buttons">
		<a class="btn back" href="index.php?option=com_pizzabox&controller=schemes"><?php echo JText::_('JPREV') ?></a>
		<input class="btn btn-primary submitrestart" type="submit" value="<?php echo JText::_('PIZZABOX_START_ANOTHER_CONTAINER') ?>" />
		<?php if( $this->params->get('time_enabled', 1) ): ?>
			<input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_DATETIME') ?>" />
		<?php else: ?>
			<input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_NAME') ?>" />
		<?php endif ?>
	</div>

	<?php foreach ($this->rows as $part): ?>
	<fieldset>
		<legend><?php echo $part->name ?><?php echo $part->price > 0 ? ', +' . $helper->formatPrice($part->price) : '' ?> (da <?php echo $part->minimum ?> a <?php echo $part->maximum ?>)</legend>
		<input type="hidden" name="parts[]" value="<?php echo $part->id ?>" />
		<?php foreach ($this->lists['flavours'] as $flavour): ?>
		<?php if (false !== strrpos($flavour->parts, '"' . $part->id . '"')): ?>
		<label>
			<input type="checkbox" name="flavours_<?php echo $part->id ?>[]" value="<?php echo $flavour->id ?>" class="flavour_checkbox" />
			<?php echo $flavour->name ?> <?php echo $flavour->price > 0 ? '(' . $helper->formatPrice($flavour->price) . ')' : '' ?>
		</label>
		<?php endif; ?>
		<?php endforeach; ?>
	</fieldset>
	<?php endforeach; ?>

	<input id="restart" type="hidden" name="restart" value="" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="element" value="part" />
	<input type="hidden" name="ordering" value="ordering" />
	<input type="hidden" name="filter_order" value="<?php echo $this->order ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->order_dir ?>" />
	<input type="hidden" name="limitstart" value="<?php echo $this->limitstart ?>" />
	<?php echo JHTML::_( 'form.token' ) ?>
</form>
