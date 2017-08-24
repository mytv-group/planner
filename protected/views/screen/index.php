<?php
/* @var $this ScreenController */
/* @var $model Screen */

$this->menu=array(
  array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $('#screen-grid').yiiGridView('update', {
    data: $(this).serialize()
  });
  return false;
});
");
?>

<h1>Manage Screens</h1>

<?php echo CHtml::link(' Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
  'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'screen-grid',
  'dataProvider'=>$model->search(),
  'columns'=>array(
    'name',
    'width',
    'height',
    array(
      'class'=>'CButtonColumn',
    ),
  ),
)); ?>
