<?php
/* @var $this StatisticController */
/* @var $model Statistic */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'dt_playback'); ?>
        <?php echo $form->textField($model,'dt_playback'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'channel'); ?>
        <?php echo $form->textField($model,'channel'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'file_name'); ?>
        <?php echo $form->textField($model,'file_name',array('size'=>45,'maxlength'=>45)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'id_point'); ?>
        <?php echo $form->textField($model,'id_point'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'id_playlist'); ?>
        <?php echo $form->textField($model,'id_playlist'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
