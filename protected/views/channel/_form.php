<?php
/* @var $this PointController */
/* @var $model Point */
/* @var $form CActiveForm */

if (Yii::app()->controller->action->id == "create"){
    $id_point = $_GET['id'];
}
else {
    $id_point = $model->id_point;
}
$point = Point::model()->findByPk($id_point)->with('net');
$net = $point->net;
$playlists = $net->playlists;
$pls = array();
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'point-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <?
        echo $form->hiddenField($model,'id_point', array('value'=>$id_point));
    ?>
    
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',array('0' => 'фоновый', '1' => 'основной')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'media_type'); ?>
		<?php echo $form->dropDownList($model,'media_type',array('v' => 'видео', 'a' => 'аудио')); ?>
		<?php echo $form->error($model,'media_type'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'id_playlist'); ?>
        <?php echo $form->dropDownList($model,'id_playlist', CHtml::listData($playlists, 'id', 'name')); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
	
	<? echo $form->hiddenField($model,'user_created', array('value'=>Yii::app()->user->id)); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->