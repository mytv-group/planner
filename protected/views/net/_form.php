<?php
/* @var $this NetController */
/* @var $model Net */
/* @var $form CActiveForm */
?>

<div class="form form-group">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'net-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'name'); ?>
    <?php echo $form->textField($model,'name', ['size'=>60,'maxlength'=>255, 'class'=> 'form-control']); ?>
    <?php echo $form->error($model,'name'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', ['class'=> 'btn btn-default']); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
