<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'id'); ?>
		<?php //echo $form->textField($model,'id'); ?>
<!-- 	</div> -->

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'files'); ?>
		<?php //echo $form->textArea($model,'files',array('rows'=>6, 'cols'=>50)); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'fromDatetime'); ?>
		<?php //echo $form->textField($model,'fromDatetime'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'toDatetime'); ?>
		<?php //echo $form->textField($model,'toDatetime'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'fromTime'); ?>
		<?php //echo $form->textField($model,'fromTime'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'toTime'); ?>
		<?php //echo $form->textField($model,'toTime'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'sun'); ?>
		<?php //echo $form->textField($model,'sun'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'mon'); ?>
		<?php //echo $form->textField($model,'mon'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'tue'); ?>
		<?php //echo $form->textField($model,'tue'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'wed'); ?>
		<?php //echo $form->textField($model,'wed'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'thu'); ?>
		<?php //echo $form->textField($model,'thu'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'fri'); ?>
		<?php //echo $form->textField($model,'fri'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'sat'); ?>
		<?php //echo $form->textField($model,'sat'); ?>
<!-- 	</div> -->

<!-- 	<div class="row"> -->
		<?php //echo $form->label($model,'author'); ?>
		<?php //echo $form->textField($model,'author',array('size'=>60,'maxlength'=>255)); ?>
<!-- 	</div> -->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->