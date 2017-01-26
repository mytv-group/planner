<?php
/* @var $this PointController */
/* @var $model Point */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'point-form',
    'enableAjaxValidation'=>true,
)); ?>

<?php
    $isView = false;
    if(isset($isViewForm)
        && ($isViewForm === true)
    ) {
        $isView = true;
    }
?>

    <?php if(!$isView): ?>
        <p class="note">Fields with <span class="required">*</span> are required.</p>
    <?php endif; ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>"form-control", 'readonly' => $isView)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'ip'); ?>
        <?php echo $form->textField($model,'ip',array('size'=>60,'maxlength'=>255, 'class'=>"form-control", 'readonly' => $isView)); ?>
        <?php echo $form->error($model,'ip'); ?>
    </div>

    <div class="row">
        <?php echo $form->hiddenField($model,'username', array('value'=>Yii::app()->user->name)); ?>
    </div>

    <?php $this->renderPartial('sections/_volume', [
        'model' => $model,
        'form' => $form,
        'isView' => $isView
    ]); ?>

    <?php $this->renderPartial('sections/_tv', [
        'model' => $model,
        'form' => $form,
        'isView' => $isView
    ]); ?>

    <?php $this->renderPartial('sections/_channels', [
        'model' => $model,
        'form' => $form,
        'isView' => $isView
    ]); ?>

    <?php $this->renderPartial('sections/_screen-with-blocks', [
        'model' => $model,
        'form' => $form,
        'isView' => $isView
    ]); ?>

    <div class="row">
        <?php
            echo $form->hiddenField($model,'sync', array("value"=>"0"));
            if(!$model->isNewRecord) {
                echo CHtml::tag('div',array('id'=> 'pointId', 'data-value'=>$model->id));
            }
        ?>
    </div>

    <?php if (!$isView): ?>
        <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>"form-control")); ?>
        </div>
    <?php endif; ?>

<?php $this->endWidget(); ?>

</div>
<!-- form -->
