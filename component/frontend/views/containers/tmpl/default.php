<?php

/**
 * @version			  $Id: views/containers/tmpl/default.php 2012-08-18 22:50:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut');
JHTML::_('behavior.tooltip');
JHTML::script('helper.js', JURI::base() . '/components/com_pizzabox/assets/');

?>
<h1><?php echo JText::_('PIZZABOX_CONTAINER_SELECT') ?></h1>
<div class="row-fluid">
	<form action="index.php?option=com_pizzabox&controller=containers" method="post" name="containerForm" id="containerForm">
		<ul class="pager">
			<li class="next">
				<input type="submit" class="btn btn-primary" value="<?php echo JText::_('JNEXT') ?>" />
			</li>
		</ul>

		<ul class="thumbnails thumbnails-containers">
			<?php foreach ($this->containers as $container): ?>
			<li class="span3" data-container-id="<?php echo $container->id ?>">
				<div class="thumbnail">
					<input type="radio" id="radio_<?php echo $container->id ?>" name="containers" value="<?php echo $container->id ?>" />
					<?php if ($container->price > 0): ?>
					<span class="label label-inverse"><?php echo $this->helper->formatPrice($container->price) ?></span>
					<?php endif; ?>

					<?php if ($container->image): ?>
					<img src="<?php echo $container->image ?>" alt="<?php echo $container->name ?>" />
					<?php else: ?>
					<img src="http://placehold.it/250&text=PizzaBox" alt="<?php echo $container->name ?>" />
					<?php endif; ?>

					<h3><?php echo $container->name ?></h3>
					<?php if ($container->desc): ?>
					<p><?php echo $container->desc ?></p>
					<?php endif; ?>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>

		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="element" value="container" />
		<?php echo JHTML::_('form.token') ?>
	</form>
</div>
<?php echo $this->sublayout['order_details']; ?>
<script type="text/javascript">
window.addEvent('domready', function() {
	$$('input[type=radio]').setStyle('display', 'none');

	$$('.thumbnails > li').addEvent('click', function() {
		var containerId = this.get('data-container-id');
		document.id('radio_' + containerId).set('checked', 'checked');
		document.id('containerForm').submit();
	});

  document.id('containerForm').addEvent('submit', function() {
    var values = $$('input[type=radio]:checked');
    if (!values.length) {
      alertTranslatedString('PIZZABOX_NO_SELECTION');
      return false;
    }
  });
});
</script>