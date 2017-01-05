<h1>Manage Widgets</h1>
<?
$columns = [
    'id',
    'name',
    'description',
    'show_duration',
    'periodicity',
    'offset',
    [
        'name' => 'Preview',
        'value' => function($data, $row){
            return '<button type="button" class="btn btn-default btn-sm btn-widget-preview" ' .
                        'data-id="'.$data->id.'">
                      <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                    </button>';
        },
        'type'  => 'raw',
    ]
];

if(Yii::app ()->user->checkAccess ("widgetEditUser")) {
  $columns[] = [
      'name' => 'Options',
      'value' => function($data, $row){
          if ($data->config === null) {
              return '';
          }

          $str = '<form action="/widget/copy/'.$data->id.'" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="Copy">
              <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
            </button>
          </form>';

          $str .= '<form action="/widget/update/'.$data->id.'" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="Update">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>
          </form>';

          if (Yii::app ()->user->checkAccess ("widgetUser")) {
              $str .= '<form action="/widget/delete/'.$data->id.'" type="post" class="btn-group delete-widget">
                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
              </form>';
          }

          return $str;
      },
      'type'  => 'raw',
  ];
}
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'widget-grid',
    'dataProvider'=>$model->search(),
    'columns'=>$columns
));
