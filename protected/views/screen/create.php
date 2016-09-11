<?php
/* @var $this ScreenController */
/* @var $model Screen */

// $this->breadcrumbs=array(
// 	'Screens'=>array('index'),
// 	'Create',
// );

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
// 	array('label'=>'Manage Screen', 'url'=>array('admin')),
);
?>

<h1>Create Screen</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>