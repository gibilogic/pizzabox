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

var checkValidity = function() {
	var ranges = <?php echo $this->ranges ?>;
	var isValid = true;

	Array.each(ranges, function(range) {
		var selected_count = $$('input[type="hidden"][name*="flavours_' + range.part_id + '"]').length;
		if (selected_count >= parseInt(range.maximum)) {
			$$('.btn-select-flavour[data-part="' + range.part_id + '"]').set('disabled', 'disabled').removeClass('btn-info');
			$$('.btn-select-flavour[data-part="' + range.part_id + '"] i').removeClass('icon-white');
		}
		else {
			$$('.btn-select-flavour[data-part="' + range.part_id + '"]').set('disabled', '').addClass('btn-info');
			$$('.btn-select-flavour[data-part="' + range.part_id + '"] i').addClass('icon-white');
		}

		if (selected_count < parseInt(range.minimum)) {
			isValid = false;
		}
	});

	$$('input[type="submit"]').set('disabled', isValid ? '': 'disabled');
};

window.addEvent('domready', function() {
	$$('input[type="submit"]').set('disabled', 'disabled');

	$$('.submitrestart').addEvent('click', function(){
		$('restart').set('value','yes');
	});

	$$('.btn-select-flavour').addEvent('click', function(e) {
		e.preventDefault();

		var hiddenInput = new Element('input', {
			type: 'hidden',
			name: 'flavours_' + this.get('data-part') + '[]',
			value: this.get('data-flavour-id')
		});
		hiddenInput.inject($('partForm'));

		var selectedFlavourRemove = new Element('button', {
			type: 'button',
			name: this.get('name'),
			class: 'btn btn-danger btn-small btn-remove-flavour',
			html: '<i class="icon-remove icon-white"></i>',
			'data-part': this.get('data-part'),
			'data-flavour-id': this.get('data-flavour-id')
		});
		var selectedFlavourContainer = new Element('li', {
			html: '&nbsp;' + this.get('name')
		});
		selectedFlavourRemove.addEvent('click', function() {
			var hiddens = $$('input[type="hidden"][name*="flavours_' + this.get('data-part') + '"][value="' + this.get('data-flavour-id') + '"]');
			hiddens[0].dispose();

			if ($$('input[type="hidden"][name*="flavours_' + this.get('data-part') + '"]').length === 0) {
				$$('#selected_part_' + this.get('data-part') + ' .empty').setStyle('display', '');
			}

			this.getParent().dispose();
			checkValidity();
		}).inject(selectedFlavourContainer, 'top');
		selectedFlavourContainer.inject($('selected_part_' + this.get('data-part')));

		$$('#selected_part_' + this.get('data-part') + ' .empty').setStyle('display', 'none');

		checkValidity();
	});
});

</script>
<h1><?php echo JText::_('PIZZABOX_FLAVOURS_SELECT') ?></h1>
<form action="index.php?option=com_pizzabox&controller=parts" method="post" name="partForm" id="partForm">
	<div class="container-fluid">
		<div class="row row-fluid">
			<div class="span12">
				<div class="buttons">
					<a class="btn back" href="index.php?option=com_pizzabox"><?php echo JText::_('JPREV') ?></a>
					<input class="btn btn-primary submitrestart" type="submit" value="<?php echo JText::_('PIZZABOX_START_ANOTHER_CONTAINER') ?>" />
					<?php if( $this->params->get('time_enabled', 1) ): ?>
						<input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_DATETIME') ?>" />
					<?php else: ?>
						<input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_NAME') ?>" />
					<?php endif ?>
				</div>
			</div>
		</div>
		<div class="row row-fluid">
			<div class="span6">
				<h3>Opzioni Disponibili</h3>
				<?php foreach ($this->rows as $part): ?>
				<?php if ($part->minimum != 0 || $part->maximum != 0): ?>
				<fieldset>
					<legend><?php echo $part->name ?><?php echo $part->price > 0 ? ', +' . $helper->formatPrice($part->price) : '' ?> (da <?php echo $part->minimum ?> a <?php echo $part->maximum ?>)</legend>
					<input type="hidden" name="parts[]" value="<?php echo $part->id ?>" />
					<?php foreach ($this->lists['flavours'] as $flavour): ?>
					<?php if (false !== strrpos($flavour->parts, '"' . $part->id . '"')): ?>
					<div class="media clearfix">
						<a class="pull-left" href="#">
							<?php if ($flavour->image): ?>
							<img class="media-object" src="<?php echo $flavour->image ?>" alt="<?php echo $flavour->name ?>" width="80" />
							<?php else: ?>
							<img class="media-object" src="http://placehold.it/80&text=PizzaBox" alt="<?php echo $flavour->name ?>" />
							<?php endif; ?>
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<button class="btn btn-success btn-small btn-select-flavour" data-part="<?php echo $part->id ?>" data-flavour-id="<?php echo $flavour->id ?>" name="<?php echo $flavour->name ?>">
									<i class="icon-plus icon-white"></i>
								</button>
								&nbsp;<?php echo $flavour->name ?>
							</h4>
							<?php echo $flavour->price > 0 ? $helper->formatPrice($flavour->price) : '' ?>
						</div>
					</div>
					<?php endif; ?>
					<?php endforeach; ?>
				</fieldset>
				<?php endif; ?>
				<?php endforeach; ?>

				<input id="restart" type="hidden" name="restart" value="" />
				<input type="hidden" name="task" value="save" />
				<input type="hidden" name="element" value="part" />
				<input type="hidden" name="ordering" value="ordering" />
				<input type="hidden" name="filter_order" value="<?php echo $this->order ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $this->order_dir ?>" />
				<input type="hidden" name="limitstart" value="<?php echo $this->limitstart ?>" />
				<?php echo JHTML::_( 'form.token' ) ?>
			</div>
			<div class="span6">
				<h3>Opzioni Selezionate</h3>
				<?php foreach ($this->rows as $part): ?>
				<?php if ($part->minimum != 0 || $part->maximum != 0): ?>
				<fieldset>
					<legend><?php echo $part->name ?></legend>
					<ul id="selected_part_<?php echo $part->id ?>" class="unstyled">
						<li class="empty"><em>Nessun elemento selezionato</em></li>
					</ul>
				</fieldset>
				<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</form>