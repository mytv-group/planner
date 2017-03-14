
<?php if (isset($widgetDescription) && isset($widgetId)): ?>
  <button class="detach-widget btn btn-warning" type="button"
    data-window-id="<?= $windowId ?>" title="<?= $widgetDescription ?>">
      <span class="glyphicon glyphicon-off"></span>
      Detach <span class="detach-widget-name"><?= $widgetDescription ?></span>
      <input class="showcase-widget" value="Point[Showcases][<?= $windowId ?>][]" value="<?= $widgetId ?>"/>
  </button>
<?php else: ?>
  <button class="attach-widget btn btn-success" type="button"
    data-window-id="<?= $windowId ?>">
      <span class="glyphicon glyphicon-paperclip"></span>
      Attach widget
      <input class="showcase-widget" value="Point[Showcases][<?= $windowId ?>][]" value=""/>
  </button>
<?php endif; ?>
