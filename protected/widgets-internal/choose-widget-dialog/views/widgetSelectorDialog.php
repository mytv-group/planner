<?php function buildWidgetModalRow($rowClass, $attr) { ?>
  <div class="row <?= $rowClass; ?>" data-id="<?= $attr['id']; ?>">
    <div class="col-sm-1">
      <?= $attr['counter']; ?>
    </div>
    <div class="col-sm-2">
      <?= $attr['name']; ?>
    </div>
    <div class="col-sm-5 modal-widget-description">
      <?= $attr['description']; ?>
    </div>
    <div class="col-sm-1">
      <?= $attr['show_duration']; ?>
    </div>
    <div class="col-sm-1">
      <?= $attr['offset']; ?>
    </div>
    <div class="col-sm-1">
      <?= $attr['periodicity']; ?>
    </div>
  </div>
<? } ?>

<div id="choose-widget-dialog" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Widgets list</h4>
      </div>
      <div class="modal-body">
          <?php
             $attributeLabels = Widget::model()->attributeLabels();
             buildWidgetModalRow('row-header', array_merge(['id' => '', 'counter' => '#'], $attributeLabels));
          ?>

          <?php
             $widgets = Widget::model()->findAll();
             $counter = 1;
          ?>
          <?php foreach ($widgets as $widget) {
              buildWidgetModalRow('row-data', [
                  'id' => $widget->id,
                  'counter' => $counter,
                  'name' => $widget->name,
                  'description' => $widget->description,
                  'show_duration' => $widget->show_duration,
                  'offset' => $widget->offset,
                  'periodicity' => $widget->periodicity,
              ]);
              $counter++;
          } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="modalbtn-attach-widget" type="button" class="btn btn-primary" disabled="disabled">Attach</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
