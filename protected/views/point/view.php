<?php
/* @var $this PointController */
/* @var $model Point */

$this->menu=array(
  array('label'=>'List', 'url'=>array('index')),
  array('label'=>'Create', 'url'=>array('create')),
  array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete', 'url'=>array('delete', 'id'=>$model->id), 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Point: <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', [
	'model' => $model,
  'screens' => $screens,
	'isViewForm' => true
]);
