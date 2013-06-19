<?php

/**
 * @version		    $Id: views/containers/tmpl/default_form.php 2012-08-17 10:26:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */

defined('_JEXEC') or die('The way is shut!');
JHTML::_('behavior.mootools');
JHTML::_('behavior.modal', 'a.modal-button');

?>
<script language="javascript" type="text/javascript">

function jInsertEditorText( tag, target )
{
	var image = tag.replace(/<img src="([^"]+)" alt="" \/>/, '$1' );
	document.id(target).set('value', image);

  if( $$('div#elementimage img').length > 0 ) {
		$$('div#elementimage img').set('src', '/' + image);
	} else {
		var imageTag = new Element ( 'img' , { 'src' : '/' + image });
		document.id('elementimage').grab( imageTag );
	};
}

function submitbutton( pressbutton, section ) {
	var form = document.adminForm;
	if (pressbutton === 'apply' || pressbutton === 'save') {
		if ( form.name.value === "" ) {
			alert( "<?php echo JText::_('PIZZABOX_ITEM_MUST_HAVE_A_NAME') ?>" );
			return;
		}
	}
	submitform(pressbutton);
	return;
}

window.addEvent('domready', function(){
	$$('.toggle_min_max').addEvent('click', function() {
		var inputs = this.getParent().getParent().getElements('.input_range');
		if (this.checked) {
			inputs.set('disabled','');
		}
		else {
			inputs.set('value', '0').set('disabled', 'disabled');
		}
	});
});

</script>
<form action="index.php?option=com_pizzabox&controller=containers" method="post" name="adminForm" id="adminForm">
	<fieldset>
		<table class="adminForm">
			<tr>
				<td><label for="name"><?php echo JText::_('PIZZABOX_NAME')?></label></td>
				<td><input id="name" name="name" type="text" class="inputbox" value="<?php echo $this->row->name ?>"/></td>
			</tr>
			<tr>
				<td><label for="price"><?php echo JText::_('PIZZABOX_PRICE')?></label></td>
				<td><input id="price" name="price" type="text" class="inputbox" value="<?php echo $this->row->price ?>"/></td>
			</tr>
			<tr>
				<td><label for="image"><?php echo JText::_('PIZZABOX_IMAGE')?></label></td>
				<td>
					<div id="elementimage">
						<?php if ( $this->row->image ) : ?>
							<img src="/<?php echo $this->row->image ?>" alt="" /><br />
						<?php endif ?>
					</div>
					<input id="image" name="image" type="text" class="inputbox" size="80" value="<?php echo $this->row->image ?>" />  <a class="modal-button" rel="{handler: 'iframe', size: {x: 570, y: 400}}" href="<?php echo JRoute::_('index.php?option=com_media&view=images&tmpl=component&e_name=image', false) ?>" title="Image"><?php echo JText::_('Browse') ?></a></td>
			</tr>
			<tr>
				<td><label for="desc"><?php echo JText::_('PIZZABOX_DESCRIPTION')?></label></td>
				<td><textarea id="desc" name="desc" class="inputbox" cols="80" rows="5"><?php echo $this->row->desc ?></textarea></td>
			</tr>
			<tr>
				<td><label for="published"><?php echo JText::_('JPublished')?></label></td>
				<td><input type="checkbox" <?php if( $this->row->published ) : ?> checked="checked" <?php endif ?> /></td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend><?php echo JText::_('PIZZABOX_PARTS')?></legend>
		<table class="adminForm">
			<thead>
				<th></th>
				<th><?php echo JText::_('PIZZABOX_PART')?></th>
				<th><?php echo JText::_('PIZZABOX_PART_MIN')?></th>
				<th><?php echo JText::_('PIZZABOX_PART_MAX')?></th>
			</thead>
			<tbody>
				<?php foreach ($this->parts_table as $row): ?>
				<tr>
					<td>
						<input type="checkbox" name="parts_ids[]" class="toggle_min_max" value="<?php echo $row['id'] ?>" <?php echo $row['enabled'] ? 'checked="checked"' : '' ?> />
					</td>
					<td><?php echo $row['name'] ?></td>
					<td>
						<input type="text" name="parts_minimums[]" class="input_range" value="<?php echo $row['minimum'] ?>" <?php echo $row['enabled'] ? '' : 'disabled="disabled"' ?> />
					</td>
					<td>
						<input type="text" name="parts_maximums[]" class="input_range" value="<?php echo $row['maximum'] ?>" <?php echo $row['enabled'] ? '' : 'disabled="disabled"' ?> />
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->row->id ?>" />
	<?php echo JHTML::_( 'form.token' ) ?>
</form>

