<?php
/* @var $this StatisticController */
/* @var $model Statistic */

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

<h1>Statistics</h1>

<div class='row row-menu'>
    <span class="btn btn-default search-button">
        <span class="glyphicon glyphicon-search"></span>
        Filter
    </span>

    <div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
        'model'=>$model,
    )); ?>
    </div><!-- search-form -->
<div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'statistic-grid',
    'dataProvider' => $model->search(),
    'columns'=>array(
        'dt_playback',
        array(
            'name' => 'Сhannel',
            'value' => function($data, $row){
                if (isset(Channel::$types[$data->channel])) {
                    return '<span class="channel-type channel-type--'.$data->channel.'">' .
                        Channel::$types[$data->channel];
                        '</span>';
                } else {
                    return '';
                }
            },
            'type'  => 'raw',
        ),
        array(
            'name' => 'File',
            'value' => function($data, $row){
                if($data->file_name) {
                    return substr($data->file_name, 13, strlen($data->file_name));
                } else {
                    return '';
                }
            },
            'type'  => 'raw',
        ),
        array(
            'name' => 'Point',
            'value' => function($data, $row){
                if(isset($data->point)
                    && isset($data->point->name)
                    && isset($data->point->ip)
                ) {
                    return "<a href='" .
                        Yii::app()->createUrl('point/' . $data->point->id). "'>"
                        . $data->point->name
                        . '<p>( '.$data->point->ip.' )</p>';
                    "</a>";
                } else {
                    return '';
                }
            },
            'type'  => 'raw',
        ),
        array(
            'name' => 'Playlist',
            'value' => function($data, $row){
                if(isset($data->playlist)
                    && isset($data->playlist->name)
                ) {
                    return "<a href='" .
                        Yii::app()->createUrl('playlists/' . $data->playlist->id). "'>"
                        . $data->playlist->name
                        . "</a>";
                } else {
                    return '';
                }
            },
            'type'  => 'raw',
        ),
    ),
));
