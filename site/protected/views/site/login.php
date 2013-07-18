<?php
$this->pageTitle=Yii::app()->name . ' - Login';
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/bootstrap.min.css", '');
?>

<div style="width:600px; margin: 0 auto;">
    
<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<br/>

<?php $form=$this->beginWidget('EBootstrapActiveForm', array(
    'id'=>'login-form',
    'enableAjaxValidation'=>false,
    'horizontal' => true,
)); ?>
    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->beginControlGroup($model, 'username'); ?>
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->beginControls(); ?>
          <?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
          <?php echo $form->error($model,'username'); ?>
        <?php echo $form->endControls(); ?>
    <?php echo $form->endControlGroup(); ?>

    <?php echo $form->beginControlGroup($model, 'password'); ?>
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->beginControls(); ?>
          <?php echo $form->passwordField($model,'password'); ?>
          <?php echo $form->error($model,'password'); ?>
        <?php echo $form->endControls(); ?>
    <?php echo $form->endControlGroup(); ?>
  
    <?php echo $form->beginActions(); ?>
        <?php echo CHtml::submitButton('Login', array('class'=>'btn btn-primary')); ?>
    <?php echo $form->endActions(); ?>

<?php $this->endWidget(); ?>




</div>
