<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */

// $this->breadcrumbs=array(
// 	'Playlists'=>array('index'),
// 	'Create',
// );

$this->menu=array(
	array('label'=>'List Playlists', 'url'=>array('index')),
	/*array('label'=>'Manage Playlists', 'url'=>array('admin')),*/
);
?>

<h1>Create Playlists</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'stream'=>$stream)); ?>