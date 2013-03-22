<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: views/flavours/tmpl/default.php 2012-08-19 09:50:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
 
$this->loadHelper('pizzabox');
$helper = new PizzaboxHelper();
?>

<h1><?php echo JText::_('PIZZABOX_FLAVOURS_LIST') ?></h1>
<div id="radiobox-wrapper" class="flavours clearfix">
  <?php $i = 0 ?>
  <?php foreach ( $this->items as $flavour ) : ?>
    <?php $i++ ?>
    <div class="radiobox radio_<?php echo $i ?>">
      <label>
        <?php if( $flavour->image ) : ?>
          <span class="image"><img width="<?php echo $this->params->get('flavours_thumb_width') ?>" src="<?php echo $flavour->image ?>" alt="" /></span>
        <?php endif ?>
        <span class="name lead" style="margin-left: <?php echo $this->params->get('flavours_thumb_width') + 4 ?>px"><?php echo $flavour->name ?></span>
        <?php if( $flavour->price > 0 ) : ?>
          <span class="price"> + <?php echo $helper->formatPrice( $flavour->price ) ?></span>
        <?php endif ?>
        <span class="desc" style="margin-left: <?php echo $this->params->get('flavours_thumb_width') + 4 ?>px"><?php echo $flavour->desc ?></span>
      </label>
    </div>
  <?php endforeach ?>
</div>
