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
	isHidden: function() {
		var w = this.offsetWidth, h = this.offsetHeight, force = (this.tagName === 'TR');
		return (w === 0 && h === 0 && !force) ? true : (w !== 0 && h !== 0 && !force) ? false : this.getStyle('display') === 'none';
	},
	isVisible: function() {
		return !this.isHidden();
	}

});

// set equal heights for given elements
function setEqualHeight(elements)
{
	var maxHeight = 0;
	elements.each(function(item) {
		if (item.isVisible()) {
			height = item.measure(function() {
				return this.getDimensions().y;
			});
			if (height > maxHeight) {
				maxHeight = height;
			}
		}
	});
	elements.setStyle('height', maxHeight);
}

// request a translation via ajax and translate the string
function alertTranslatedString(string)
{
	var req = new Request({
		method: 'get',
		url: 'index.php?option=com_pizzabox&controller=ajax&format=raw&string=' + string,
		onComplete: function(response) {
			alert(response);
		}
	}).send();
}

window.addEvent('load', function() {

	// single click to next page
	$$('form#deliveryForm div.radiobox label').addEvent('click', function(e) {
		e.preventDefault();
		document.id("#" + document.id(this.getProperty('for')).set('checked', true));
		document.id('deliveryForm').submit();
	});

	// toggle part divs
	$$('div.part').addEvent('click', function() {
		part_id = this.get('class').replace(/part/g, '').replace(/btn-primary/g, '').replace(/btn/g, '').trim();
		$$('div.flavours').addClass('hidden');
		$$('div.flavours' + part_id).removeClass('hidden');
		$$('div.part').removeClass('btn-primary');
		$$('div.part' + part_id).addClass('btn-primary');
	});

	// toggle toolbar
	if (document.id('containersToolbar')) {
		document.id('hideToolbar').addEvent('click', function() {
			document.id('containersToolbar').setStyle('display', 'none');
			document.id('hideToolbar').setStyle('display', 'none');
			document.id('showToolbar').setStyle('display', 'inline');
		});
		document.id('showToolbar').addEvent('click', function() {
			document.id('containersToolbar').setStyle('display', 'block');
			document.id('hideToolbar').setStyle('display', 'inline');
			document.id('showToolbar').setStyle('display', 'none');
		});
	}

	// toggle filters
	if (document.id('hideFilters')) {
		document.id('hideFilters').addEvent('click', function() {
			$$('fieldset.filters').setStyle('display', 'none');
			document.id('hideFilters').setStyle('display', 'none');
			document.id('showFilters').setStyle('display', 'inline');
		});
	}
	if (document.id('showFilters')) {
		document.id('showFilters').addEvent('click', function() {
			$$('fieldset.filters').setStyle('display', 'block');
			document.id('hideFilters').setStyle('display', 'inline');
			document.id('showFilters').setStyle('display', 'none');
		});
	}

	// set equal height
	setEqualHeight($$('#radiobox-wrapper label'));

});
