<?php $form=$this->beginWidget('EBootstrapActiveForm', array(
    'id'=>'feature-value-form',
    'horizontal' => true,
)); ?>
	<?php echo $form->hiddenField($model,'feature_id'); ?>
	<?php echo $form->hiddenField($model,'id'); ?>
    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->beginControlGroup($model, 'name'); ?>
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->beginControls(); ?>
          <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
          <?php echo $form->error($model,'name'); ?>
        <?php echo $form->endControls(); ?>
    <?php echo $form->endControlGroup(); ?>

    
<?php $this->endWidget(); ?>