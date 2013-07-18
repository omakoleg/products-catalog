<?php $form=$this->beginWidget('EBootstrapActiveForm', array(
    'id'=>'category-form',
    'horizontal' => true,
)); ?>
	<?php echo $form->hiddenField($model,'id'); ?>
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->beginControlGroup($model, 'name'); ?>
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->beginControls(); ?>
		  <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		  <?php echo $form->error($model,'name'); ?>
	    <?php echo $form->endControls(); ?>
	<?php echo $form->endControlGroup(); ?>

	<?php echo $form->beginControlGroup($model, 'slug'); ?>
		<?php echo $form->labelEx($model,'slug'); ?>
		<?php echo $form->beginControls(); ?>
		  <?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>255)); ?>
		  <?php echo $form->error($model,'slug'); ?>
		<?php echo $form->endControls(); ?>
	<?php echo $form->endControlGroup(); ?>
	
	
	
	<?php echo $form->beginControlGroup($model, 'parent_id'); ?>
	   <?php echo $form->labelEx($model,'parent_id'); ?>
	   <?php echo $form->beginControls(); ?>
	   	<?php if($model->childsCount){ ?>
			Can't be changed, remove all subcategories first
		<?php }else{ ?> 
	    	<?php echo $form->dropDownList($model,'parent_id',
	    		CHtml::listData(array_merge(array(0 => ''),$parents),'id','name')); ?>
	    <?php } ?>
	<?php echo $form->endControlGroup(); ?>
	

<?php $this->endWidget(); ?>
