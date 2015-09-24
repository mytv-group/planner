<?php
/* @var $this PointController */
/* @var $data Point */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><? /*php echo CHtml::encode($data->getAttributeLabel('sync_time')); */ ?>:</b>
	<? /* php echo CHtml::encode($data->sync_time); */ ?>
<!-- 	<br /> -->

	<b><? /*php echo CHtml::encode($data->getAttributeLabel('update_time')); */ ?>:</b>
	<? /* php echo CHtml::encode($data->update_time); */ ?>
<!-- 	<br /> -->

	<b><?php echo CHtml::encode($data->getAttributeLabel('volume')); ?>:</b>
	<?php echo CHtml::encode($data->volume); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('TV')); ?>:</b>
	<?php echo CHtml::encode($data->TV); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel1')); ?>:</b>
	<?php echo CHtml::encode($data->channel1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel2')); ?>:</b>
	<?php echo CHtml::encode($data->channel2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel3')); ?>:</b>
	<?php echo CHtml::encode($data->channel3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel4')); ?>:</b>
	<?php echo CHtml::encode($data->channel4); ?>
	<br />

	*/ ?>

</div>