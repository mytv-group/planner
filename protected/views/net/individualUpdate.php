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
    <h3>Apply config to each network point</h3>
</div>

<ul>
<?php foreach ($model->points as $point): ?>
  <li>
    <div class="row">
        <h4 class="alert alert-info" role="alert"><strong>Point</strong> <?= $point->name ?> <?= $point->ip ?></h4>
    </div>

    <div class="row">
        <?= $form->labelEx($point, 'TVschedule'); ?>
        <?php $this->widget('TVscheduleWidget', [
            'tvBlocks' => $point->tv,
            'editable' => true,
            'postName' => "NetApplications[Points][".$point->id."]"
        ]); ?>
    </div>

    <div class="row">
        <?= $form->labelEx($point, 'channels'); ?>
        <?php $this->widget('PointChannelsWidget', [
            'playlistToPoint' => $point->playlistToPoint,
            'editable' => true,
            'postName' => "NetApplications[Points][".$point->id."]"
        ]); ?>
    </div>

    <div class="row">
        <?= $form->labelEx($point, 'screen_id'); ?>
        <?php $this->widget('ScreenSelectorWidget', [
            'point' => $point,
            'screens' => $screens,
            'editable' => true,
            'postName' => "NetApplications[Points][".$point->id."]"
        ]); ?>
        <?= $form->error($point,'screen_id'); ?>
    </div>
  </li>
<? endforeach; ?>
</ul>

<div class="row buttons">
  <?= CHtml::submitButton('Save', ['class'=> 'btn btn-default']); ?>

  <?= CHtml::link('Discard and perform general update',
    '/net/update/id/'.$model->id,
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
