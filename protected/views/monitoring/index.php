<?php
/* @var $this MonitoringController */
/* @var $model Monitoring */
?>

<h1>Monitorings</h1>

<div class="container-fluid">
    <div class="row">
        <h4 class="alert alert-danger" role="alert">Expired playlists</h4>
    </div>

    <div class="row">
        <div class="playlists-list container-fluid">
            <?php $this->widget('zii.widgets.CListView', array(
              'dataProvider' => Playlists::model()->searchByExpiration('CURDATE()'),
              'itemView' => '_playlists_view',
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
            )); ?>
        </div>
    </div>

    <div class="row">
        <h4 class="alert alert-warning" role="alert">Expiring this week playlists</h4>
    </div>

    <div class="row">
        <div class="playlists-list container-fluid">
            <?php $this->widget('zii.widgets.CListView', array(
              'dataProvider' => Playlists::model()->searchByExpiration('CURDATE() + INTERVAL 7 DAY', 'CURDATE()'),
              'itemView' => '_playlists_view',
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
            )); ?>
        </div>
    </div>

    <div class="row">
        <h4 class="alert alert-danger" role="alert">Points without planned background content</h4>
    </div>

    <div class="row">
        <div class="points-list container-fluid">
            <?php $this->widget('zii.widgets.CListView', array(
              'dataProvider' => Point::model()->searchWithoutContent(),
              'itemView' => '_points_view',
              'sortableAttributes'=>array(
                  'id',
                  'name',
                  'ip',
              ),
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
    </div>
</div>
