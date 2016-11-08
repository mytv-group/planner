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

</div>
