<?php
/* @var $this UserController */
/* @var $model User */

// $this->breadcrumbs=array(
// 	'Users'=>array('index'),
// 	$model->name,
// );

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
	array('label'=>'Create', 'url'=>array('create')),
	array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete', 'url'=>array('delete', 'id'=>$model->id), 
			'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
			'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View User: <?php echo $model->username; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'username',
		'name',
		'blocked',
		array(
				'name'  => 'role',
				'value' => Yii::app()->controller->RoleSrting($model->role),
				'type'  => 'raw',
		),
	),
)); ?>
