<?php
/* @var $this PointController */
/* @var $model Point */

$this->menu=array(
  array('label'=>'List', 'url'=>array('index')),
);
?>

<h1>Create Point</h1>

<?php $this->renderPartial('_form', [
    'model' => $model,
    'playlists'=> $playlists,
    'screens' => $screens,
    'widgets' => $widgets
]); ?>
