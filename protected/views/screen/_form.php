<?php
/* @var $this ScreenController */
/* @var $model Screen */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'screen-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->hiddenField($model,'id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>"form-control")); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<table>
			<tr style="text-align:left">
				<td><?php echo $form->labelEx($model,'width'); ?></td>
				<td><?php echo $form->labelEx($model,'height'); ?></td>
			</tr>
			<tr>
				<td><?php if($model->isNewRecord){
							echo $form->textField($model,'width', array('class'=>"form-control")); 
						  } else {
						  	echo $form->textField($model,'width', array('class'=>"form-control", "readonly" => true));
						  }
					?>
				</td>
				<td><?php if($model->isNewRecord){
							echo $form->textField($model,'height', array('class'=>"form-control")); 
						  } else {
						  	echo $form->textField($model,'height', array('class'=>"form-control", "readonly" => 'true'));
						  }
					?>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->error($model,'width'); ?></td>
				<td><?php echo $form->error($model,'height'); ?></td>
			</tr>
		</table>
	</div>
	
	<div><p><?php if(!$model->isNewRecord) { echo "Attention! Changing display size will drop all blocks."; } ?></p></div>

	<div class="row buttons">
		<table>
			<tr style="text-align:left">
				<td><?= CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>"form-control")) ?></td>
				<td><?php if(!$model->isNewRecord){
							echo CHtml::button('Change display size', 
									array('id' => 'changeDisplaySizeButt',
											'class'=>"form-control btn btn-danger"));
						  } 
					?>
				</td>
			</tr>
		</table>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->