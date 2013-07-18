<form enctype='multipart/form-data'>
	<p>
		<div class="files-drop-area-message">	
			<div class="progress"></div>
			<div class="errors"></div>
			<div class="message">Drop files here: </div>
		</div>
		<div class="files-drop-area" data-product_id="<?php echo $product_id; ?>">
			<img src="/img/ajax-loader-bar.gif"/>	
		</div>
	</p>
	<table style="width:100%;" class="images-table">
	</table>
	<?php echo CHtml::hiddenField('product_id', $product_id); ?>
</form>


<script type="text/html" id="images-table-item-template">
	<tr class="existing-item-<%= image.id %>">
		<td>
			<input type="hidden" name="product_image[<%= image.id %>][id]" value="<%= image.id %>"/>
			<input type="radio" 
				name="default_image_id" 
				value="<%= image.id %>" <% if(image.is_default == '1'){ %> checked <% } %> />
		</td>
		<td style="width:150px;"><img src="<%= image.img_normal %>"/></td>
		<td><input type="text" name="product_image[<%= image.id %>][name]" value="<%= image.name %>"/></td>
		<td style="width:20px;">
			<a href="#" onclick="App.EM.trig('images:delete',{elem: '.existing-item-<%= image.id %>', id: <%= image.id %> });return false;">
				<i class="icon-remove"></i>
			</a>
		</td>
	</tr>
</script>