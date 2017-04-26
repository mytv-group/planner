<?php
/* @var $this PlaylistsController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
    array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#statistic-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Playlists</h1>

<div class='row row-menu'>
    <span class="btn btn-default search-button">
        <span class="glyphicon glyphicon-search"></span>
        Filter
    </span>

    <div class="search-form" style="display:none">
      <?php $this->renderPartial('_search', array(
          'model' => $model,
      )); ?>
    </div><!-- search-form -->
</div>

<div class="playlist-list container-fluid">
<?php
  $this->widget('zii.widgets.CListView', [
      'dataProvider' => $model->search(),
      'itemView' => '_view',
      'sortableAttributes'=>array(
          'id',
          'name',
      ),
      'pager' => [
          'firstPageLabel'=>'&laquo;',
          'prevPageLabel'=>'&lsaquo;',
          'nextPageLabel'=>'&rsaquo;',
          'lastPageLabel'=>'&raquo;',
          'maxButtonCount'=>'5',
          'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css'
      ],
  ]); ?>
</div>
