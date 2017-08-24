<div class="windows-list <?= $isActive ? 'is-active' : ''; ?>"
  data-screen-id="<?= $screenId; ?>"
  data-point-id="<?= $pointId; ?>"
>
  <?php foreach ($windows as $window): ?>
      <div class="showcases-container btn-toolbar" role="toolbar" aria-label="">
          <div class="btn-group" role="group" aria-label="">
              <button class="btn btn-default channel-btn" disabled="disabled">
                  Window <span class="screen-name"><?= $window->name; ?></span>
              </button>

              <?php $widget = null;
                  if(isset($window->widget) && ($isActive)) {
                      $showcases = $window->showcases;

                      foreach ($showcases as $showcase) {
                          if (($showcase->id_point === $pointId)
                            && ($showcase->widget)
                          ) {
                              $widget = $showcase->widget;
                          }
                      }
                  }
              ?>

              <?php $this->render('blockForWidget', [
                  'windowId' => $window->id,
                  'pointId' => $pointId,
                  'widgetId' => isset($widget->id) ? $widget->id : null,
                  'widgetDescription' => isset($widget->description) ? $widget->description : null,
                  'editable' => $editable,
                  'postName' => $postName
              ]); ?>
          </div>
      </div>
  <?php endforeach; ?>
</div>
