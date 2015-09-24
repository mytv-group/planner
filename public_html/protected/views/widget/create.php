<?php
/* @var $this WidgetController */
/* @var $model Widget */

$this->breadcrumbs=array(
	'Widgets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
);
?>

<h1>Create Widget</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>