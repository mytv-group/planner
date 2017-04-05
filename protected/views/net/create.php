<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=array(
    array('label'=>'List', 'url'=>['index']),
);
?>

<h1>Create Net</h1>

<div class="form form-group">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'net-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?= $form->errorSummary($model); ?>

  <div class="row">
    <?= $form->labelEx($model,'name'); ?>
    <?= $form->textField($model,'name', ['size'=>60,'maxlength'=>255, 'class'=> 'form-control']); ?>
    <?= $form->error($model,'name'); ?>
  </div>

  <div class="row">
    <?
      $selected = [];
      if (isset($attachedPoints) && is_array($attachedPoints)) {
          $selected = $attachedPoints;
      }
    ?>
    <?= $form->labelEx($model, 'attachedPoints'); ?>
    <?= $form->dropDownList($model, 'attachedPoints', $model->availablePoints, [
      'class' => 'form-control',
      'multiple' => 'multiple',
      'size' => '15',
      'options' => $selected
    ]); ?>
  </div>

  <div class="row buttons">
    <?= CHtml::submitButton('Create', ['class'=> 'btn btn-default']); ?>
  </div>

<?php $this->endWidget(); ?>
