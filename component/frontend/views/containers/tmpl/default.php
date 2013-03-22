<?php defined('_JEXEC') or die('The way is shut');
/**
 * @version			  $Id: views/containers/tmpl/default.php 2012-08-18 22:50:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

$document =& JFactory::getDocument();
$document->addScript( JURI::base() . "/components/com_pizzabox/assets/helper.js" );
$this->loadHelper('pizzabox');
$helper = new PizzaboxHelper();
?>
<h1><?php echo JText::_('PIZZABOX_CONTAINER_SELECT') ?></h1>
<form action="index.php?option=com_pizzabox&controller=containers" method="post" name="containerForm" id="containerForm">
  <div class="buttons">
    <input type="submit" class="btn btn-primary" value="<?php echo JText::_('JNEXT') ?>" />
    <input type="hidden" name="task" value="save" /> 
    <input type="hidden" name="element" value="container" />
    <input type="hidden" name="ordering" value="ordering" />
    <input type="hidden" name="filter_order" value="<?php echo $this->order ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->order_dir ?>" />
    <input type="hidden" name="limitstart" value="<?php echo $this->limitstart ?>" />
    <?php echo JHTML::_( 'form.token' ) ?>
  </div>
  <div id="radiobox-wrapper" class="clearfix">
    <?php foreach ($this->rows as $k => $row) :?>
      <div class="radiobox clearfix radio_<?php echo $row->id ?>">
        <input type="radio" id="radio_<?php echo $row->id ?>" name="containers" value="<?php echo $row->id ?>"/> 
        <label for="radio_<?php echo $row->id ?>">
          <?php if( $row->image ): ?>
            <span class="image"><img src="<?php echo $row->image ?>" alt="<?php echo $row->desc ?>" /></span>
          <?php endif ?>
          <span class="name lead"><?php echo $row->name ?></span>
          <?php if( $row->price > 0 ) : ?>
            <span class="price"><?php echo $helper->formatPrice( $row->price ) ?></span>
          <?php endif ?>
          <span class="desc"><?php echo $row->desc ?></span>
        </label>
      </div>
    <?php endforeach ?>
  </div>
</form>
