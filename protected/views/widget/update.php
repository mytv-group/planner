<?php
/* @var $this UserController */
/* @var $model User */

$this->menu = array(
	array('label'=>'List', 'url'=>array('index')),
);
?>

<h1><?= ucfirst($action) ?> Widget: <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', ['model'=>$model]); ?>
