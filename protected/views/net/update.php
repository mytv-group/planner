<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
	array('label'=>'Create', 'url'=>array('create')),
	array('label'=>'View', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Update Net <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', [
    'model' => $model,
		'attachedPoints' => $attachedPoints
]);
