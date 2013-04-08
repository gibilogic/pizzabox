/**
 * @version			  $Id: assets/helper.js 2012-08-19 09:50:00Z zanardi $
 * @package			  GiBi PizzaBox
 * @author			  GiBiLogic
 * @authorUrl		  http://www.gibilogic.com
 * @authorEmail		info@gibilogic.com
 * @copyright		  Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license			  GNU/GPL v2 or later
 */

Element.implement({

  isHidden: function(){
    var w = this.offsetWidth, h = this.offsetHeight,
    force = (this.tagName === 'TR');
    return (w===0 && h===0 && !force) ? true : (w!==0 && h!==0 && !force) ? false : this.getStyle('display') === 'none';
  },

  isVisible: function(){
    return !this.isHidden();
  }

});

// set equal heights for given elements
function setEqualHeight( elements )
{
  var maxHeight = 0;
	elements.each( function(item) {
    if( item.isVisible() ) {
      height = item.measure(function(){
        return this.getDimensions().y;
      });
      if ( height > maxHeight ) {
        maxHeight = height;
      }
    }
	});
	elements.setStyle('height', maxHeight );
}

// request a translation via ajax and translate the string
function alertTranslatedString( string )
{
  var req = new Request({
    method: 'get',
    url: 'index.php?option=com_pizzabox&controller=ajax&format=raw&string=' + string,
    onComplete: function( response ) {
      alert( response );
    }
  }).send();
}

window.addEvent('load',function(){

	// add class for selected element in containers radio button group
//	$$('div.radiobox label').addEvent('click',function(e){
//
//		// force radio button check (for IE7)
//		e.preventDefault();
//		$("#"+$(this.getProperty('for')).set('checked', true));

//		var values = $$('div.radiobox input[type=radio]:checked').map(function(e){ return e.id });
//		$$('div.radiobox').removeClass('selected');
//		for( i=0; i<values.length; i++ ) {
//			var myDiv = $$('div.radiobox.' + values[i] )[0];
//			myDiv.addClass('selected');
//
//			// get flavour name and desc
//			var flavourname = $$('div.radiobox.' + values[i] + ' span.name').get('html');
//			var flavourdesc = $$('div.radiobox.' + values[i] + ' span.desc').get('html');
//
//			// get part id
//			var parentClass = myDiv.getParent().get('class');
//			var partId = parentClass.replace(/flavours/g,'').replace(/hidden/g,'').replace(/clearfix/g,'').trim();
//
//			$$('div.part' + partId + ' span.flavourname').set('html', flavourname );
//			$$('div.part' + partId + ' span.flavourdesc').set('html', flavourdesc );
//		}
//	});

	// single click to next page
	$$('form#deliveryForm div.radiobox label').addEvent('click',function(e){
		e.preventDefault();
		$("#"+$(this.getProperty('for')).set('checked', true));
		$('deliveryForm').submit();
	});

  // toggle part divs
  $$('div.part').addEvent('click',function(){
    part_id = this.get('class').replace(/part/g,'').replace(/btn-primary/g,'').replace(/btn/g,'').trim();
    $$('div.flavours').addClass('hidden');
    $$('div.flavours' + part_id).removeClass('hidden');
    $$('div.part').removeClass('btn-primary');
    $$('div.part' + part_id).addClass('btn-primary');
  });

	// toggle toolbar
	if ( $('containersToolbar') ) {
		$('hideToolbar').addEvent('click',function(){
			$('containersToolbar').setStyle('display','none');
			$('hideToolbar').setStyle('display','none');
			$('showToolbar').setStyle('display','inline');
		});
		$('showToolbar').addEvent('click',function(){
			$('containersToolbar').setStyle('display','block');
			$('hideToolbar').setStyle('display','inline');
			$('showToolbar').setStyle('display','none');
		});
	}

	// toggle filters
	if ( $('hideFilters') ) {
		$('hideFilters').addEvent('click',function(){
			$$('fieldset.filters').setStyle('display','none');
			$('hideFilters').setStyle('display','none');
			$('showFilters').setStyle('display','inline');
		});
	}
	if ( $('showFilters') ) {
		$('showFilters').addEvent('click',function(){
			$$('fieldset.filters').setStyle('display','block');
			$('hideFilters').setStyle('display','inline');
			$('showFilters').setStyle('display','none');
		});
	}

  // set equal height
  setEqualHeight( $$('#radiobox-wrapper label') );

});

