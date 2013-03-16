<?php defined('_JEXEC') or die('The way is shut!');
/**
 * @version		    $Id: views/parts/tmpl/default_form.php 2012-08-17 10:26:00Z zanardi $
 * @package		    GiBi PizzaBox
 * @author        GiBiLogic snc
 * @authorEmail   info@gibilogic.com
 * @authorUrl     http://www.gibilogic.com
 * @copyright	    Copyright (C) 2011-2012 GiBiLogic. All rights reserved.
 * @license		    GNU/GPL v2 or later
 */
JHTML::_('behavior.mootools');
JHTML::_('behavior.modal', 'a.modal-button');
?>
<script language="javascript" type="text/javascript">
function jInsertEditorText( tag, target ) 
{
	var image = tag.replace(/<img src="([^"]+)" alt="" \/>/, '$1' );
	$( target ).set('value', image );
	
	if( $$('div#elementimage img').length > 0 ) {
		$$('div#elementimage img').set('src', '/' + image);
	} else {
		var imageTag = new Element ( 'img' , { 'src' : '/' + image });
		$('elementimage').grab( imageTag );
	};
}

function submitbutton( pressbutton, section ) {
	var form = document.adminForm;
	if (pressbutton == 'apply' || pressbutton == 'save') {
		if ( form.name.value == "" ) {
			alert( "<?php echo JText::_('PIZZABOX_ITEM_MUST_HAVE_A_NAME') ?>" );
			return;
		}
	}
	submitform(pressbutton);
	return;
}

function getSchemes( item ) {
	
	// first we should remove all options from the select 
	$('scheme_id').empty();
	var option = new Option( '<?php echo '--'. JText::_( 'PIZZABOX_SCHEME_SELECT' ) .'--' ?>', '' );
	option.setProperty('selected', 'selected');
	$('scheme_id').add( option, null );
	
	// then, if we have a valid container, get schemes and add options
	if ( item.value ) {
		
		var req = new Request({
			method: 'get',
      url: 'index.php?option=com_pizzabox&controller=ajax&format=raw&task=getschemes',
			data: { 'filter_containers' : item.value },
			onComplete: function( response ) { 
				var schemes = JSON.decode( response ); 
				for ( i=0; i<schemes.length; i++ ) {
					var option = new Option( schemes[i].name, schemes[i].id );
					$('scheme_id').add( option, null );
				}
			}
		}).send();	
	}
}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="adminForm">
		<tr>
			<td><label for="name"><?php echo JText::_('PIZZABOX_NAME')?></label></td>
			<td><input id="name" name="name" type="text" class="inputbox" value="<?php echo $this->row->name ?>"/></td>
		</tr>
		<tr>
			<td><label for="containers"><?php echo JText::_('PIZZABOX_CONTAINER')?></label></td>
			<td><?php echo $this->lists['containers'] ?></td>
		</tr>
		<tr>
			<td><label for="schemes"><?php echo JText::_('PIZZABOX_SCHEME')?></label></td>
			<td><?php echo $this->lists['schemes'] ?></td>
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
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="parts" />
	<input type="hidden" name="option" value="com_pizzabox" />
	<input type="hidden" name="id" value="<?php echo $this->row->id ?>" />
	<?php echo JHTML::_( 'form.token' ) ?>
</form>

