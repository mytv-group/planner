<?php
/* @var $this PointController */
/* @var $model Point */

// $this->breadcrumbs=array(
// 	'Points'=>array('index'),
// 	'Manage',
// );

$this->menu=array(
	array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#point-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Points</h1>

<?php echo CHtml::link('Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'point-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		//'id',
		'name',
		/*'username',
		'password',*/
		'ip',	
		'volume',
		 //'TV',
		'sync_time',
		'update_time',
		array(
				'name' => 'sync',
				'value' => function($data,$row){
					if($data->sync)
					{
						return '<input name="syncCheckBox" type="checkbox" checked>';
					}
					else 
					{
						return '<input name="syncCheckBox" type="checkbox">';
					}
					
			         // $data - это объект модель для текущей стройки
			         // $row - это порядковый номер строчки начиная с нуля
			         // $this - объект колонки, объект класса http://www.yiiframework.com/doc/api/1.1/CDataColumn/
			     },
			     'type'  => 'raw',
		),
		/*array(
				'name'  => 'channel1',
				'value' => 'CHtml::link($this->grid->controller->PlaylistIdToName($data->channel1), 
					Yii::app()->createUrl("playlist/view",array("id"=>$data->channel1)))',
				'type'  => 'raw',
		),*/
		//'channel2',
		//'channel3',
		//'channel4',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
