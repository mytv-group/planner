<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'playlists-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100, 'class'=>"form-control")); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
    <table>
        <tr style="text-align:left">
            <td><?php echo $form->labelEx($model,'fromDatetime'); ?></td>
            <td><?php echo $form->labelEx($model,'toDatetime'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->textField($model,'fromDatetime', array('class'=>"form-control datepicker")); ?></td>
            <td><?php echo $form->textField($model,'toDatetime', array('class'=>"form-control datepicker")); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->error($model,'fromDatetime'); ?></td>
            <td><?php echo $form->error($model,'toDatetime'); ?></td>
        </tr>
    </table>
    </div>

    <div class="row">
        <table>
            <tr style="text-align:left">
                <td><?php echo $form->labelEx($model,'fromTime'); ?></td>
                <td><?php echo $form->labelEx($model,'toTime'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->textField($model,'fromTime', array('class'=>"form-control timepicker")); ?></td>
                <td><?php echo $form->textField($model,'toTime', array('class'=>"form-control timepicker")); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->error($model,'fromTime'); ?></td>
                <td><?php echo $form->error($model,'toTime'); ?></td>
            </tr>
        </table>
    </div>

    <div class="row" style='padding-bottom:1em;'>
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->radioButtonList($model,'type',
            array('0'=>'Background','1'=>'Advertising','2'=>'Stream'),
            array('class'=>"type-control",
                'separator'=>' &nbsp; ',
                'labelOptions'=>array('style'=>'display:inline;')
            )
        ); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>

    <div id="everyBlock" class="row">
        <table>
            <tr style="text-align:left">
                <td><?php echo $form->labelEx($model,'every'); ?></td>
            </tr>
            <tr>
                <td>    <?php echo $form->textField($model,'every',array('class'=>"form-control timepicker",
                            'value'=>$model->isNewRecord ? "00:30:00" : $model->every)); ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->error($model,'every'); ?></td>
            </tr>
        </table>
    </div>

    <div id="stream-url-block" class="row" >
        <?php echo $form->labelEx($stream,'url'); ?>
        <?php echo $form->textField($stream,'url', array('class'=>"form-control")); ?>
        <?php echo $form->error($stream,'url'); ?>
    </div>

    <div class="row">
        <table>
            <tr style="text-align:left">
                <td><?php echo $form->labelEx($model,'sun'); ?></td>
                <td><?php echo $form->labelEx($model,'mon'); ?></td>
                <td><?php echo $form->labelEx($model,'tue'); ?></td>
                <td><?php echo $form->labelEx($model,'wed'); ?></td>
                <td><?php echo $form->labelEx($model,'thu'); ?></td>
                <td><?php echo $form->labelEx($model,'fri'); ?></td>
                <td><?php echo $form->labelEx($model,'sat'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->checkBox($model,'sun'); ?></td>
                <td><?php echo $form->checkBox($model,'mon'); ?></td>
                <td><?php echo $form->checkBox($model,'tue'); ?></td>
                <td><?php echo $form->checkBox($model,'wed'); ?></td>
                <td><?php echo $form->checkBox($model,'thu'); ?></td>
                <td><?php echo $form->checkBox($model,'fri'); ?></td>
                <td><?php echo $form->checkBox($model,'sat'); ?></td>
            </tr>
        </table>
    </div>

    <div class="row">
        <?php echo $form->error($model,'sun'); ?>
        <?php echo $form->error($model,'mon'); ?>
        <?php echo $form->error($model,'tue'); ?>
        <?php echo $form->error($model,'wed'); ?>
        <?php echo $form->error($model,'thu'); ?>
        <?php echo $form->error($model,'fri'); ?>
        <?php echo $form->error($model,'sat'); ?>
    </div>

    <div class="row">
        <?php echo $form->hiddenField($model,'author',array('value'=>Yii::app()->user->name)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>"form-control")); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
