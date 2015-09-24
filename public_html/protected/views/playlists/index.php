<?php
/* @var $this PlaylistsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Playlists',
);

$this->menu=array(
	array('label'=>'Create Playlists', 'url'=>array('create')),
	array('label'=>'Manage Playlists', 'url'=>array('admin')),
);
?>

<h1>Playlists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
