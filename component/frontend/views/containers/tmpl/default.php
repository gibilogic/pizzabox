<?php
/**
 * @version			  views/containers/tmpl/default.php 2013-11-24 23:03:00 UTC zanardi
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic <info@gibilogic.com>
 * @authorUrl		  http://www.gibilogic.com
 * @copyright		  (C) 2011-2013 GiBiLogic. All rights reserved.
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
            <?php if (count($this->parts) > 0): ?>
            <li class="prev">
                <a href="index.php?option=com_pizzabox&controller=delivery" class="btn">
                    <?php echo $this->params->get('time_enabled', 1) ? JText::_('PIZZABOX_PROCEED_TO_DATETIME') : JText::_('PIZZABOX_PROCEED_TO_NAME') ?>
                </a>
            </li>
            <?php endif; ?>
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
  $$('input[type=submit]').setStyle('display', 'none');

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