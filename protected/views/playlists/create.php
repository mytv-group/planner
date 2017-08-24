<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
);
?>

<h1>Create Playlists</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'stream'=>$stream)); ?>
