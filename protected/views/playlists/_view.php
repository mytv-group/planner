<?php
/* @var $this PlaylistsController */
/* @var $data Playlists */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

<!-- 	<b><?php //echo CHtml::encode($data->getAttributeLabel('files')); ?>:</b> -->
	<?php //echo CHtml::encode($data->files); ?>
<!-- 	<br /> -->

	<b><?php echo CHtml::encode($data->getAttributeLabel('fromDatetime')); ?>:</b>
	<?php echo CHtml::encode($data->fromDatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('toDatetime')); ?>:</b>
	<?php echo CHtml::encode($data->toDatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fromTime')); ?>:</b>
	<?php echo CHtml::encode($data->fromTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('toTime')); ?>:</b>
	<?php echo CHtml::encode($data->toTime); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sun')); ?>:</b>
	<?php echo CHtml::encode($data->sun); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mon')); ?>:</b>
	<?php echo CHtml::encode($data->mon); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tue')); ?>:</b>
	<?php echo CHtml::encode($data->tue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wed')); ?>:</b>
	<?php echo CHtml::encode($data->wed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('thu')); ?>:</b>
	<?php echo CHtml::encode($data->thu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fri')); ?>:</b>
	<?php echo CHtml::encode($data->fri); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sat')); ?>:</b>
	<?php echo CHtml::encode($data->sat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />

	*/ ?>

</div>