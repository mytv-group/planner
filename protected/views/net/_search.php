<?php
/* @var $this NetController */
/* @var $model Net */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'action'=>Yii::app()->createUrl($this->route),
  'method'=>'get',
)); ?>

  <div class="row">
    <?php echo $form->label($model,'name'); ?>
    <?php echo $form->textField($model,'name', ['class'=>"form-control", 'size'=>45,'maxlength'=>45]); ?>
  </div>

  <div class="row buttons">
      <?php echo CHtml::submitButton('Search', ['class'=>"btn btn-default", 'name'=> 'submit', 'value'=>'search']); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
