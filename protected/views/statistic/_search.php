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
        <?php echo $form->textField($model,'dt_playback', ['class'=>"form-control"]); ?>
    </div>

    <div class="row">
        <label for="Statistic_channel"><p>Channel</p><p class='statistic-form-note'>1-BG;2-ADV;3-STR</p></label>
        <?php echo $form->textField($model,'channel', ['class'=>"form-control"]); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'file_name'); ?>
        <?php echo $form->textField($model,'file_name', ['class'=>"form-control", 'size'=>45,'maxlength'=>45]); ?>
    </div>

    <div class="row">
        <label for="Statistic_id_point"><p>Point</p><p class='statistic-form-note'>Name or ID</p></label>
        <?php echo $form->textField($model,'id_point', ['class'=>"form-control"]); ?>
    </div>

    <div class="row">
        <label for="Statistic_id_playlist"><p>Playlist</p><p class='statistic-form-note'>Name or ID</p></label>
        <?php echo $form->textField($model,'id_playlist', ['class'=>"form-control"]); ?>
    </div>

    <div class="row buttons">
      <?php echo CHtml::submitButton('Search', ['class'=>"btn btn-default", 'name'=> 'submit', 'value'=>'search']); ?>

      <?php echo CHtml::submitButton('Export', ['class'=>"btn btn-default", 'name'=> 'submit', 'value'=>'export']); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
