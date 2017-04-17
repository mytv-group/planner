<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=[
    ['label'=>'List', 'url'=>['index']],
    ['label'=>'Create', 'url'=>['create']],
    ['label'=>'Update', 'url'=>['update', 'id'=>$model->id]],
    ['label'=>'Change point list', 'url'=>['changePoints', 'id'=>$model->id]],
    ['label'=>'Individual update', 'url'=>['individualUpdate', 'id'=>$model->id]],
    ['label'=>'View', 'url'=>['view', 'id'=>$model->id]],
];
?>

<h1>Update Net <?= $model->name; ?></h1>

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
  <?= $form->labelEx($model, 'attachedPoints'); ?>
  <?php
    $counter = 1;
    foreach ($model->points as $point) {
        echo('<p>'.$counter.'. '.'<b>'.$point->name.'</b>'.' '.$point->ip.'</p>');
        $counter++;
    }
  ?>
</div>

<div class="row buttons">
  <?= CHtml::link('Change point list',
    '/net/changePoints/id/'.$model->id,
    ['class'=> 'btn btn-danger']
  ); ?>
</div>

<div class="row">
    <h3>Add config to all network points</h3>
    <p class="note">All exist points configs will be saved, but new will be add</p>
</div>

<div class="row">
    <?= $form->labelEx($model, 'TVschedule'); ?>
    <?php $this->widget('tvScheduleWidget', [
        'tvBlocks' => [],
        'editable' => true,
        'postName' => 'NetApplications'
    ]); ?>
</div>

<div class="row">
    <?= $form->labelEx($model, 'channels'); ?>
    <?php $this->widget('PointChannelsWidget', [
        'point' => [],
        'editable' => true,
        'postName' => 'NetApplications'
    ]); ?>
</div>

<div class="row buttons">
  <?= CHtml::submitButton('Save', ['class'=> 'btn btn-default']); ?>

  <?= CHtml::link('Discard and perform individual update',
    '/net/individualUpdate/id/'.$model->id,
    ['class'=> 'btn btn-warning']
  );?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php $this->widget('ChooseWidgetDialogWidget', [
  'widgets' => $widgets,
]); ?>

<?php foreach (Playlists::$types as $type => $name) {
    $this->widget('ChoosePlaylistDialogWidget', [
      'channelType' => $type, // 0, 1, 2
      'channelName' => $name,
      'playlists' => isset($playlists[$type]) ? $playlists[$type] : [],
    ]);
} ?>
