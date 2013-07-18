

<?php $form=$this->beginWidget('EBootstrapActiveForm', array(
    'id'=>'product-form',
    'horizontal' => true,
    'enableAjaxValidation'=>false,
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

    <?php echo $form->beginControlGroup($model, 'price'); ?>
        <?php echo $form->labelEx($model,'price'); ?>
        <?php echo $form->beginControls(); ?>
          <?php echo $form->textField($model,'price',array('size'=>60,'maxlength'=>255)); ?>
          <?php echo $form->error($model,'price'); ?>
        <?php echo $form->endControls(); ?>
    <?php echo $form->endControlGroup(); ?>
    
     <?php echo $form->beginControlGroup($model, 'description'); ?>
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->beginControls(); ?>
          <?php echo $form->textArea($model, 'description', array('maxlength' => 255, 'rows' => 6, 'cols' => 50)); ?>
          <?php echo $form->error($model,'description'); ?>
        <?php echo $form->endControls(); ?>
    <?php echo $form->endControlGroup(); ?>
    
    <?php echo $form->beginControlGroup($model, 'ref'); ?>
        <?php echo $form->labelEx($model,'ref'); ?>
        <?php echo $form->beginControls(); ?>
          <?php echo $form->textField($model,'ref',array('size'=>60,'maxlength'=>255)); ?>
          <?php echo $form->error($model,'ref'); ?>
        <?php echo $form->endControls(); ?>
    <?php echo $form->endControlGroup(); ?>
    
     <?php echo $form->beginControlGroup($model, 'is_new'); ?>
        <?php echo $form->labelEx($model,'is_new'); ?>
        <?php echo $form->beginControls(); ?>
          <?php echo $form->checkBox($model,'is_new'); ?>
          <?php echo $form->error($model,'is_new'); ?>
        <?php echo $form->endControls(); ?>
    <?php echo $form->endControlGroup(); ?>

<?php $this->endWidget(); ?>
