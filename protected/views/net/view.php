<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=[
  ['label'=>'List', 'url'=>['index']],
  ['label'=>'Create', 'url'=>['create']],
  ['label'=>'Update', 'url'=>['update', 'id'=>$model->id]],
  ['label'=>'Change point list', 'url'=>['changePoints', 'id'=>$model->id]],
  ['label'=>'Individual update', 'url'=>['individualUpdate', 'id'=>$model->id]],
  ['label'=>'Add to all points', 'url'=>['add', 'id'=>$model->id]],
  ['label'=>'Delete', 'url'=>'#', 'linkOptions'=>['submit'=>
    ['delete','id'=>$model->id],
    'confirm'=>'Are you sure you want to delete this item?']
  ],
];
?>

<h1>View Net <?= $model->name; ?></h1>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'net-form',
  'enableAjaxValidation'=>false,
)); ?>

<div class="row">
  <?= $form->labelEx($model,'name'); ?>
  <?= $form->textField($model,'name', ['size'=>60,'maxlength'=>255, 'class'=> 'form-control',  'readonly' => true]); ?>
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

<div class="row">
    <h3>Network points</h3>
</div>

<ul>
<?php foreach ($model->points as $point): ?>
  <li>
    <div class="row">
        <h4 class="alert alert-info" role="alert"><strong>Point</strong> <?= $point->name ?>  <?= $point->ip ?></h4>
    </div>

    <?php if (count($point->tv) > 0): ?>
      <div class="row">
          <?= $form->labelEx($point, 'TVschedule'); ?>
          <?php $this->widget('TvScheduleWidget', [
              'tvBlocks' => $point->tv,
              'editable' => false,
              'postName' => "NetApplications[Points][".$point->id."]"
          ]); ?>
      </div>
    <?php endif; ?>

    <div class="row">
        <?= $form->labelEx($point, 'channels'); ?>
        <?php $this->widget('PointChannelsWidget', [
            'point' => $point,
            'editable' => false,
            'postName' => "NetApplications[Points][".$point->id."]"
        ]); ?>
    </div>

    <?php if ($point->screen): ?>
      <div class="row">
          <?= $form->labelEx($point, 'screen_id'); ?>
          <?php $this->widget('ScreenSelectorWidget', [
              'point' => $point,
              'screens' => [],
              'editable' => false,
              'postName' => "NetApplications[Points][".$point->id."]"
          ]); ?>
          <?= $form->error($point,'screen_id'); ?>
      </div>
    <?php endif; ?>
  </li>
<? endforeach; ?>
</ul>

<?php $this->endWidget(); ?>

</div><!-- form -->
