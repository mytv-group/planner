<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=[
    ['label'=>'List', 'url'=>['index']],
    ['label'=>'Create', 'url'=>['create']],
    ['label'=>'Update', 'url'=>['update', 'id'=>$model->id]],
    ['label'=>'Individual update', 'url'=>['individualUpdate', 'id'=>$model->id]],
    ['label'=>'Add to all points', 'url'=>['add', 'id'=>$model->id]],
    ['label'=>'View', 'url'=>['view', 'id'=>$model->id]],
];
?>

<h1>Change Net <?= $model->name; ?> Points</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'net-form',
  'enableAjaxValidation'=>false,
)); ?>

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
    <?= CHtml::submitButton('Save', ['class'=> 'btn btn-default']); ?>
  </div>

<?php $this->endWidget(); ?>
