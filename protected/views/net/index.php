<?php
/* @var $this NetController */
/* @var $model Net */

$this->menu=array(
	array('label'=>'Create Net', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#net-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Nets</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
// echo "<br><br>";
// $modelData = $model->findAll();
// foreach ($modelData as $key => $val)
// {
// 	echo "<br><br>";
// 	foreach ($val as $key1 => $val1)
// 	{
// 	echo "<br><br>";
// 	var_dump($key1);
// 	var_dump($val1);
// 	echo "<br><br>";
// 	}
// }
// exit();
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'net-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
		array(
			'name' => 'user',
			'value' => function($model) {
				$userName = $model->user;
				return $userName['name'];
			},
			'type' => 'raw',
		),
		array(
			'name' => 'pointsattached',
			'value' => function($model) { 
				$attacjedPoints = array();
				foreach ($model->pointsattached as $attached)
				{
					$attacjedPoints[] = $attached['name'];
				}
				return implode(",", $attacjedPoints);
			},
			'type' => 'raw',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
