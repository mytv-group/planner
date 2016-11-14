<h1>Manage Widgets</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'widget-grid',
    'dataProvider'=>$model->search(),
    'columns'=>array(
        'id',
        'name',
        'description',
        'show_duration',
        'periodicity',
        array(
            'name' => 'Preview',
            'value' => function($data, $row){
                return '<button type="button" class="btn btn-default btn-sm btn-widget-preview" ' .
                            'data-id="'.$data->id.'">
                          <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                        </button>';
            },
            'type'  => 'raw',
        ),
        array(
            'name' => 'Config',
            'value' => function($data, $row){
                if ($data->config === null) {
                    return '';
                }

                return '<button type="button" class="btn btn-default btn-sm widget-edit" ' .
                            'data-id="'.$data->id.'">
                          <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        </button>';
            },
            'type'  => 'raw',
        ),
    ),
));
