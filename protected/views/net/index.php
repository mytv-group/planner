<?php
/* @var $this NetController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
  array('label'=>'Create', 'url'=>array('create')),
);
?>

<h1>Nets</h1>

<div class="container-fluid">
    <?php $this->widget('zii.widgets.CListView', array(
      'dataProvider' => $dataProvider,
      'itemView' => '_view',
      'pager' => [
          'firstPageLabel'=>'&laquo;',
          'prevPageLabel'=>'&lsaquo;',
          'nextPageLabel'=>'&rsaquo;',
          'lastPageLabel'=>'&raquo;',
          'maxButtonCount'=>'5',
          'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css'
      ],
    )); ?>
</div>
