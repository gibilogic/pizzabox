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

?>
<h1><?php echo JText::_('PIZZABOX_FLAVOURS_SELECT') ?></h1>
<form action="<?php echo JRoute::_('index.php?option=com_pizzabox&controller=parts') ?>" method="post" name="partForm" id="partForm">
	<ul class="pager">
		<li class="prev">
			<a class="btn back" href="<?php echo JRoute::_('index.php?option=com_pizzabox') ?>"><?php echo JText::_('JPREV') ?></a>
		</li>
		<li class="next">
			<input class="btn btn-primary submitrestart" type="submit" value="<?php echo JText::_('PIZZABOX_START_ANOTHER_CONTAINER') ?>" />
			<input class="btn btn-primary" id="submit" type="submit" value="<?php echo $this->params->get('time_enabled', 1) ? JText::_('PIZZABOX_PROCEED_TO_DATETIME') : JText::_('PIZZABOX_PROCEED_TO_NAME') ?>" />
		</li>
	</ul>

	<div class="row-fluid">
		<div class="span8">
			<?php foreach ($this->rows as $part): ?>
			<?php if ($part->minimum != 0 || $part->maximum != 0): ?>
			<h2>
				<?php echo $part->name ?><?php echo $part->price > 0 ? ', +' . $this->helper->formatPrice($part->price) : '' ?>
				<?php if ($part->minimum == $part->maximum): ?>
				&nbsp;(select <?php echo $part->minimum ?>)</h2>
				<?php else: ?>
				&nbsp;(select from <?php echo $part->minimum ?> to <?php echo $part->maximum ?>)</h2>
				<?php endif; ?>
			<ul class="thumbnails thumbnails-flavours">
				<?php foreach ($this->lists['flavours'] as $flavour): ?>
				<?php if (false !== strrpos($flavour->parts, '"' . $part->id . '"')): ?>
				<li class="span3" data-part-id="<?php echo $part->id ?>" data-flavour-id="<?php echo $flavour->id ?>" title="<?php echo $flavour->name ?>">
					<div class="thumbnail">
						<?php if ($flavour->price > 0): ?>
						<span class="label label-inverse"><?php echo $this->helper->formatPrice($flavour->price) ?></span>
						<?php endif; ?>

						<?php if ($flavour->image): ?>
						<img src="<?php echo $flavour->image ?>" alt="<?php echo $flavour->name ?>" />
						<?php else: ?>
						<img src="http://placehold.it/200&text=PizzaBox" alt="<?php echo $flavour->name ?>" />
						<?php endif; ?>
						<h3><?php echo $flavour->name ?></h3>
					</div>
				</li>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<input type="hidden" name="parts[]" value="<?php echo $part->id ?>" />
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="span4">
			<div class="well well-small">
				<?php foreach ($this->rows as $part): ?>
				<?php if ($part->minimum != 0 || $part->maximum != 0): ?>
				<fieldset>
					<legend><?php echo $part->name ?></legend>
					<ul id="selected_part_<?php echo $part->id ?>" class="unstyled">
                        <li class="empty"><em><?php echo JText:: _('PIZZABOX_EMPTY_FLAVOUR') ?></em></li>
					</ul>
				</fieldset>
				<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<input type="hidden" name="restart" value="" id="restart" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="element" value="part" />
	<input type="hidden" name="ordering" value="ordering" />
	<input type="hidden" name="filter_order" value="<?php echo $this->order ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->order_dir ?>" />
	<input type="hidden" name="limitstart" value="<?php echo $this->limitstart ?>" />
	<?php echo JHTML::_( 'form.token' ) ?>
</form>
<?php echo $this->sublayout['order_details']; ?>
<script type="text/javascript">

window.addEvent('domready', function() {
	var ranges = <?php echo $this->ranges ?>;

	var checkValidity = function() {
		var isValid = true;

		Array.each(ranges, function(range) {
			var selectedCount = $$('input[type="hidden"][name*="flavours_' + range.part_id + '"]').length;
			if (selectedCount < parseInt(range.minimum)) {
				isValid = false;
			}
		});

		$$('input[type="submit"]').set('disabled', isValid ? '': 'disabled');
	};

	var isPartAtLimit = function(partId) {
		var range = ranges.filter(function(item, index){
			return item.part_id === partId;
		}).pick();
		var selectedCount = $$('input[type="hidden"][name*="flavours_' + range.part_id + '"]').length;

		return selectedCount >= range.maximum;
	};

	$$('input[type="submit"]').set('disabled', 'disabled');

	$$('.submitrestart').addEvent('click', function(){
		document.id('restart').set('value','yes');
	});

	$$('.thumbnails-flavours > li').addEvent('click', function() {
		if (isPartAtLimit(this.get('data-part-id'))) {
			return false;
		}

		var hiddenInput = new Element('input', {
			type: 'hidden',
			name: 'flavours_' + this.get('data-part-id') + '[]',
			value: this.get('data-flavour-id')
		});
		hiddenInput.inject(document.id('partForm'));

		var selectedFlavourRemove = new Element('button', {
			type: 'button',
			name: this.get('name'),
			class: 'btn btn-danger btn-small btn-remove-flavour',
			html: '<i class="icon-remove icon-white"></i>',
			'data-part-id': this.get('data-part-id'),
			'data-flavour-id': this.get('data-flavour-id')
		});
		var selectedFlavourContainer = new Element('li', {
			html: '&nbsp;' + this.get('title')
		});
		selectedFlavourRemove.addEvent('click', function() {
			var hidden = $$('input[type="hidden"][name*="flavours_' + this.get('data-part-id') + '"][value="' + this.get('data-flavour-id') + '"]').pick();
			if (hidden) {
				hidden.dispose();
			}

			if ($$('input[type="hidden"][name*="flavours_' + this.get('data-part-id') + '"]').length === 0) {
				$$('#selected_part_' + this.get('data-part-id') + ' .empty').setStyle('display', '');
			}

			this.getParent().dispose();
			checkValidity();
		}).inject(selectedFlavourContainer, 'top');
		selectedFlavourContainer.inject(document.id('selected_part_' + this.get('data-part-id')));

		$$('#selected_part_' + this.get('data-part-id') + ' .empty').setStyle('display', 'none');

		checkValidity();
	});
});

</script>