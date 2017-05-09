<?php
/* @var $this MonitoringController */
/* @var $model Monitoring */
?>

<h1>Monitorings</h1>


<?php

function isPointOnline ($ip){
    $monitoringModel = new Monitoring();
    return $monitoringModel->checkIpOnline($ip);
}

$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'point-grid',
        'dataProvider'=>$pointModel,
        'columns'=>array(
                'id',
                'name',
                'ip',
                'volume',
                'sync_time',
                'update_time',
                array(
                    'name' => 'sync',
                    'value' => function($data,$row){
                        if($data->sync) {
                            return '<input name="syncCheckBox" type="checkbox" checked>';
                        } else {
                            return '<input name="syncCheckBox" type="checkbox">';
                        }
                    },
                    'type'  => 'raw',
                ),
                array(
                    'name' => 'status',
                    'value' => function($data, $row){
                        $isOnline = isPointOnline($data->ip);
                        $glyphicon = $isOnline ? 'glyphicon-globe' : 'glyphicon-eye-close';
                        $btnType = $isOnline ? 'btn-success' : 'btn-danger';

                        return '<button type="button" class="btn '.$btnType.' btn-sm" disabled>
                              <span class="glyphicon '.$glyphicon.'" aria-hidden="true"></span>
                            </button>';
                    },
                    'type'  => 'raw',
                ),
                array(
                    'name' => 'screen',
                    'value' => function($data, $row){
                        return '<button type="button" class="btn btn-default btn-sm ShowPointScreenBut" ' .
                                    'data-id="'.$data->id.'" data-ip="'.$data->ip.'">
                                  <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                                </button>';
                    },
                    'type'  => 'raw',
                ),
                array(
                    'name' => 'preview',
                    'value' => function($data, $row){
                        return '<form action="/preview/' . $data->id . '" type="GET" class="btn-group" target="_blank">' .
                            '<button type="submit" class="btn btn-default btn-sm">' .
                                '<span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>' .
                            '</button>' .
                        '</form>';
                    },
                    'type'  => 'raw',
                ),
                array(
                    'name' => 'ctrl',
                    'value' => function($data, $row){
                        $viewBut = '<form action="/point/'.$data->id.'" type="post" class="btn-group">'.
                                        '<button type="submit" class="btn btn-default btn-sm">'.
                                          '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>'.
                                        '</button>'.
                                    '</form>';

                        $editBut = '<form action="/point/update/' . $data->id . '" type="post" class="btn-group">' .
                                        '<button type="submit" class="btn btn-default btn-sm">' .
                                            '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' .
                                        '</button>' .
                                    '</form>';

                        $delBut = '<form action="/monitoring/deletePoint/' . $data->id . '" type="post" class="delete-point btn-group">' .
                                        '<button type="submit" class="btn btn-danger btn-sm">' .
                                            '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>' .
                                        '</button>' .
                                    '</form>';

                        return $viewBut . $editBut . $delBut;
                    },
                    'type'  => 'raw',
                ),
        ),
));

$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'playlists-grid',
        'dataProvider'=>$playlistModel,
        'columns'=>array(
                'name',
                array(
                    'name' => 'type',
                    'value' => function($data, $row) {
                        if (isset(Playlists::$types[$data->type])) {
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
                    'value' => function($data,$row){
                        if($data->type == 1) //0 - bg, 1- adv
                            return $data->every;
                        else
                            return '';
                    },
                    'type'  => 'raw',
                ),
                array(
                    'name' => 'ctrl',
                    'value' => function($data, $row){
                        $viewBut = '<form action="/playlists/'.$data->id.'" type="post" class="btn-group">'.
                                        '<button type="submit" class="btn btn-default btn-sm">'.
                                          '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>'.
                                        '</button>'.
                                    '</form>';

                        $editBut = '<form action="/playlists/update/' . $data->id . '" type="post" class="btn-group">' .
                                    '<button type="submit" class="btn btn-default btn-sm">' .
                                    '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' .
                                    '</button>' .
                                    '</form>';

                        $delBut = '<form action="/monitoring/deletePlaylist/' . $data->id . '" type="post" class="delete-playlist btn-group">' .
                                    '<button type="submit" class="btn btn-danger btn-sm">' .
                                    '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>' .
                                    '</button>' .
                                    '</form>';

                        return $viewBut . $editBut . $delBut;
                    },
                    'type'  => 'raw',
                ),
        ),
));
