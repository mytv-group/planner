<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
	array('label'=>'Create', 'url'=>array('create')),
	array('label'=>'View', 'url'=>array('view', 'id'=>$model->id))
);
?>

<h1>Update User: <?php echo $model->username; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'rolesList'=>$rolesList)); ?>
