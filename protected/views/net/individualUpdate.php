<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=[
    ['label'=>'List', 'url'=>['index']],
    ['label'=>'Create', 'url'=>['create']],
    ['label'=>'Change point list', 'url'=>['changePoints', 'id'=>$model->id]],
    ['label'=>'Update', 'url'=>['update', 'id'=>$model->id]],
    ['label'=>'View', 'url'=>['view', 'id'=>$model->id]],
];
?>

<h1>Individual <?= $model->name; ?> Network Points Update</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'net-form',
  'enableAjaxValidation'=>false,
)); ?>

<?= $form->errorSummary($model); ?>

  <div class="row">
    <?= $form->labelEx($model,'name'); ?>
    <?= $form->textField($model,'name', ['size'=>60,'maxlength'=>255, 'class'=> 'form-control', 'readonly' => true]); ?>
    <?= $form->error($model,'name'); ?>
  </div>

  <div class="row">

  </div>

  <div class="row buttons">
    <?= CHtml::submitButton('Save', ['class'=> 'btn btn-default']); ?>
  </div>

<?php $this->endWidget(); ?>
