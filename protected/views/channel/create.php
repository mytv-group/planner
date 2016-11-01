<?php
/* @var $this PointController */
/* @var $model Point */

$this->breadcrumbs=array(
	'Points'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Point', 'url'=>array('index')),
	array('label'=>'Manage Point', 'url'=>array('admin')),
);
?>

<h1>Create Point</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>