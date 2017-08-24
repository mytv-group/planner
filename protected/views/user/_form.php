<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'username'); ?>
            <?php echo $form->textField($model,'username',array('class'=>'form-control', 'size'=>60,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'username'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control', 'size'=>60,'maxlength'=>255, 'value'=>'')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model,'name',array('class'=>'form-control', 'size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php
                $checkedState = '';
                if($model->blocked == 1)
                {
                    $checkedState = 'checked';
                }
            ?>
            <?php echo $form->labelEx($model,'blocked'); ?>
            <?php echo $form->checkBox($model,'blocked',  array('checked'=>$checkedState)); ?>
            <?php echo $form->error($model,'blocked'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php echo $form->labelEx($model,'role'); ?>
            <?php echo $form->dropDownList($model,'role', $rolesList, array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'role'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-md-5">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array("id"=>"submitUser", 'class'=>'btn btn-primary')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
