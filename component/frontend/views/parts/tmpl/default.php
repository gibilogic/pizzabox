<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: views/parts/tmpl/default.php 2012-08-19 09:50:00Z zanardi $
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

<script type="text/javascript">
	window.addEvent('domready', function(){
		
		$$('.submitrestart').addEvent('click',function(){
			$('restart').set('value','yes');
		});
		
		// check that every part has a flavour selected
		$('partForm').addEvent('submit',function(){
				
			var part_ids = new Array();
			<?php foreach ($this->rows as $part) : ?>
				part_ids.push(<?php echo $part->id ?>);
			<?php endforeach ?>

			var valid = true;
			part_ids.each( function( part_id ){
				var flavour_id = 0;
				flavour_id = $$('div.radiobox input[name=flavours_' + part_id + ']:checked').map(function(e){return e.value;});
				if ( flavour_id == 0 ) { valid = false;	}
			});
			
			if ( valid == false ) {
				alertTranslatedString( "PIZZABOX_NO_FLAVOURS_SELECTION" );
				return false;
			} else { 
				return true;
			}
		});
	});
</script>

<h1><?php echo JText::_('PIZZABOX_FLAVOURS_SELECT') ?></h1>

<?php if( count( $this->rows ) ) : ?>

  <form action="index.php?option=com_pizzabox&controller=parts" method="post" name="partForm" id="partForm">

    <div class="buttons">
      <a class="btn back" href="index.php?option=com_pizzabox&controller=schemes"><?php echo JText::_('JPREV') ?></a>	
      <input class="btn btn-primary submitrestart" type="submit" value="<?php echo JText::_('PIZZABOX_START_ANOTHER_CONTAINER') ?>" />
      <?php if( $this->params->get('time_enabled',1) ): ?>
        <input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_DATETIME') ?>" />
      <?php else: ?>
        <input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_NAME') ?>" />
      <?php endif ?>
      <input id="restart" type="hidden" name="restart" value="" />
      <input type="hidden" name="task" value="save" /> 
      <input type="hidden" name="element" value="part" />
      <input type="hidden" name="ordering" value="ordering" />
      <input type="hidden" name="filter_order" value="<?php echo $this->order ?>" />
      <input type="hidden" name="filter_order_Dir" value="<?php echo $this->order_dir ?>" />
      <input type="hidden" name="limitstart" value="<?php echo $this->limitstart ?>" />
      <?php echo JHTML::_( 'form.token' ) ?>
    </div>
    
    <div id="radiobox-wrapper" class="parts clearfix">
      <?php $i = 0 ?>
      <?php foreach ($this->rows as $part) :?>		
        <input type="hidden" name="parts[]" value="<?php echo $part->id ?>" />
        <div class="partbox">
          <div class="part btn part<?php echo $part->id ?> <?php if( $i == 0 ) echo "btn-primary"?>">
            <span class="image"><img src="/<?php echo $part->image ?>" alt="" /></span>
            <span class="name"><?php echo $part->name ?> : </span>
            <?php if( $part->price > 0 ) : ?>
              <span class="price"> + <?php echo $helper->formatPrice( $part->price ) ?></span>
            <?php endif ?>
            <span class="flavourname lead"></span>
            <span class="flavourdesc"></span>
            <div style="clear: both"></div>
          </div>
          <?php 
            // set height from config parameter
            $flavours_box_styles = array(); 
            if ( $this->params->get('flavours_box_height') ) { 
              $flavours_box_styles []= 'height: ' . $this->params->get('flavours_box_height') . 'px'; 
            }
            // hide all flavours box except the first
            $flavours_box_class = "flavours flavours{$part->id}";
            if ( $i != 0 ) { 
              $flavours_box_class .= ' hidden'; 
            }
          ?>
          <div class="<?php echo $flavours_box_class ?> clearfix" style="<?php echo join(';',$flavours_box_styles); ?>" >
          <?php foreach ( $this->lists['flavours'] as $flavour ) : ?>
            <?php $i++ ?>
            <div class="radiobox radio_<?php echo $i ?>">
              <input type="radio" id="radio_<?php echo $i ?>" name="flavours_<?php echo $part->id ?>" value="<?php echo $flavour->id ?>"/> 
              <label for="radio_<?php echo $i ?>">
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
        </div>
      <?php endforeach ?>
      <div style="clear: both"></div>
    </div>
    
    <div class="buttons">
      <a class="btn back" href="index.php?option=com_pizzabox&controller=schemes"><?php echo JText::_('JPREV') ?></a>	
      <input class="btn btn-primary submitrestart" type="submit" value="<?php echo JText::_('PIZZABOX_START_ANOTHER_CONTAINER') ?>" />
      <?php if( $this->params->get('time_enabled',1) ): ?>
        <input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_DATETIME') ?>" />
      <?php else: ?>
        <input class="btn btn-primary" id="submit" type="submit" value="<?php echo JText::_('PIZZABOX_PROCEED_TO_NAME') ?>" />
      <?php endif ?>
    </div>
  </form>

<?php else : // no parts, error message ?> 

  <div class="buttons">
    <a class="btn back" href="index.php?option=com_pizzabox&controller=schemes"><?php echo JText::_('JPREV') ?></a>	
  </div>
  <p class="info"><?php echo JText::_('PIZZABOX_NO_PARTS_AVAILABLE') ?></p>

<?php endif ?>
