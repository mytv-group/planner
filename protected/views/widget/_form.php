<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'widget-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>true,
)); ?>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'description'); ?>
            <?php echo $form->textArea($model,'description',array('class'=>'form-control', 'size'=>80,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'description'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'show_duration'); ?>
            <?php echo $form->textField($model,'show_duration',array('class'=>'form-control', 'size'=>10,'maxlength'=>5)); ?>
            <?php echo $form->error($model,'show_duration'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'periodicity'); ?>
            <?php echo $form->textField($model,'periodicity',array('class'=>'form-control', 'size'=>10,'maxlength'=>5)); ?>
            <?php echo $form->error($model,'periodicity'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'offset'); ?>
            <?php echo $form->textField($model,'offset',array('class'=>'form-control', 'size'=>10,'maxlength'=>5)); ?>
            <?php echo $form->error($model,'offset'); ?>
        </div>
    </div>

    <div class="row">
      <div class="col-md-5 text-danger"><h4>Individual configurations</h4></div>
    </div>

    <div class="row">
      <?php $config = json_decode($model->config); ?>
      <?php foreach ($config as $key => $value): ?>
        <div class="col-md-5">
            <label for="WidgetConfig_<?= $key; ?>"><?= ucfirst($key); ?></label>
            <input class="form-control" size="10" maxlength="255" name="WidgetConfig[<?= $key; ?>]" id="Widget_<?= $key; ?>" type="text" value="<?= $value; ?>">
        </div>
      <?php endforeach; ?>
    </div>

    <div class="row buttons">
        <div class="col-md-5">
            <?php echo CHtml::submitButton('Save', array("id"=>"submitUser", 'class'=>'btn btn-primary')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
