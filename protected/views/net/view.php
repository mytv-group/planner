<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=[
  ['label'=>'List Net', 'url'=>['index']],
  ['label'=>'Create Net', 'url'=>['create']],
  ['label'=>'Update Net', 'url'=>['update', 'id'=>$model->id]],
  ['label'=>'Change point list', 'url'=>['changePoints', 'id'=>$model->id]],
  ['label'=>'Individual update', 'url'=>['individualUpdate', 'id'=>$model->id]],
  ['label'=>'Delete Net', 'url'=>'#', 'linkOptions'=>['submit'=>
    ['delete','id'=>$model->id],
    'confirm'=>'Are you sure you want to delete this item?']
  ],
];
?>

<h1>View Net <?= $model->name; ?></h1>

<?php $form=$this->beginWidget('CActiveForm', [
  'id'=>'net-form',
  'enableAjaxValidation'=>false,
]); ?>

  <div class="row">
    <?= $form->labelEx($model,'name'); ?>
    <?= $form->textField($model,'name', ['size'=>60,'maxlength'=>255, 'class'=> 'form-control', 'readonly' => true]); ?>
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
<?php $this->endWidget(); ?>
