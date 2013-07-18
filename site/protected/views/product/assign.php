<form class="form-horizontal">
	<div class="control-group">
		<label class="control-label">Categories</label>
		<div class="controls">
			<?php echo CHtml::hiddenField('product_id', $product_id); ?>
			<?php echo CHtml::listBox('category_id', $selected, $categories, array(
				"multiple" => "multiple",
				"size"=>20,
				"class" => "input-xlarge"
				)); ?>	
		</div>
	</div>
</form>