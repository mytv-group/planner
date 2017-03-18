<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */

$this->breadcrumbs=array(
  'Playlists'=>array('index'),
  'Manage',
);

$this->menu=array(
  /*array('label'=>'List Playlists', 'url'=>array('index')),*/
  array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $('#playlists-grid').yiiGridView('update', {
    data: $(this).serialize()
  });
  return false;
});
");
?>

<h1>Manage Playlists</h1>

<!-- <p> -->
<!-- You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> -->
<!-- or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done. -->
<!-- </p> -->

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
  'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'playlists-grid',
  'dataProvider' => $model->search(),
  'columns'=>array(
    'name',
    array(
        'name' => 'type',
        'value' => function($data, $row) {
          if (Playlists::$types[$data->type]) {
              return Playlists::$types[$data->type];
          }

          return 'unknown';
        },
        'type'  => 'raw',
    ),
    'fromDatetime',
    'toDatetime',
    'fromTime',
    'toTime',
    array(
        'name' => 'every',
        'value' => function($data, $row) {
          if($data->type == 1) //0 - bg, 1- adv
            return $data->every;
          else
            return '';
        },
        'type'  => 'raw',
    ),
    array(
      'class'=>'CButtonColumn',
    ),
  ),
)); ?>
