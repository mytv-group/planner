<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=array(
    array('label'=>'List', 'url'=>['index']),
);
?>

<h1>Create Net</h1>

<?php $this->renderPartial('_form', [
    'model' => $model
]);
