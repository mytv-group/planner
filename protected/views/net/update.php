<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=[
    ['label'=>'List', 'url'=>['index']],
    ['label'=>'Create', 'url'=>['create']],
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
      foreach ($model->pointsToNet as $item) {
          echo('<p>'.$counter.'. '.'<b>'.$item->point->name.'</b>'.' '.$item->point->ip.'</p>');
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

  <?php if(!$model->isNewRecord) : ?>
    <div class="row">
        <h3>Apply config to all network points</h3>
        <p class="note">All exist points configs will be lost</p>
    </div>

    <div class="row">
        <?= $form->labelEx($model, 'TVschedule'); ?>
        <?php $this->widget('TVscheduleWidget', [
            'tvBlocks' => [],
            'editable' => true
        ]); ?>
    </div>

    <div class="row">
        <?= $form->labelEx($model, 'channels'); ?>
        <?php $this->widget('PointChannelsWidget', [
            'playlistToPoint' => [],
            'editable' => true
        ]); ?>
    </div>

    <div class="row">
        <?= $form->labelEx($model, 'screen_id'); ?>
        <?php $this->widget('ScreenSelectorWidget', [
            'point' => $model,
            'screens' => $screens,
            'editable' => true
        ]); ?>
        <?= $form->error($model,'screen_id'); ?>
    </div>
  <?php endif; ?>

  <div class="row buttons">
    <?= CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', ['class'=> 'btn btn-default']); ?>

    <?php if(!$model->isNewRecord) {
        echo CHtml::link('Discard and perform individual update',
          '/net/individualUpdate/id/'.$model->id,
          ['class'=> 'btn btn-warning']
        );
      }
    ?>
    </div>
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
