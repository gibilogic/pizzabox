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
<form action="index.php?option=com_pizzabox&controller=containers" method="post" name="containerForm" id="containerForm">
  <div class="buttons">
    <input type="submit" class="btn btn-primary" value="<?php echo JText::_('JNEXT') ?>" />
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="element" value="container" />
    <?php echo JHTML::_('form.token') ?>
  </div>
  <div id="radiobox-wrapper" class="clearfix">
    <?php foreach ($this->containers as $container) :?>
		<div class="radiobox clearfix radio_<?php echo $container->id ?>">
			<input type="radio" id="radio_<?php echo $container->id ?>" name="containers" value="<?php echo $container->id ?>"/>
			<label for="radio_<?php echo $container->id ?>">
				<?php if( $container->image ): ?>
					<span class="image"><img src="<?php echo $container->image ?>" alt="<?php echo $container->desc ?>" /></span>
				<?php endif; ?>
				<span class="name lead"><?php echo $container->name ?></span>
				<?php if ($container->price > 0): ?>
					<span class="price"><?php echo $this->helper->formatPrice($container->price) ?></span>
				<?php endif; ?>
				<?php if ($container->desc): ?>
				<span class="desc"><?php echo $container->desc ?></span>
				<?php endif; ?>
			</label>
		</div>
    <?php endforeach ?>
  </div>
</form>
