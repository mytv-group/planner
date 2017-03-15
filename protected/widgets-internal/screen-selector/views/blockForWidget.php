<button class="<?= isset($widgetId) ? 'detach-widget' : 'attach-widget' ?> btn
  <?= isset($widgetId) ? 'btn-warning' : 'btn-success' ?>" type="button"
  data-window-id="<?= $windowId ?>" title="<?= isset($widgetDescription) ? $widgetDescription : '' ?>">
    <span class="glyphicon <?= isset($widgetId) ? 'glyphicon-off' : 'glyphicon-paperclip' ?>"></span>
    <span class="widget-detach-btn-text">Detach</span>
    <span class="widget-attach-btn-text">Attach widget</span>
    <span class="widget-name"><?= isset($widgetDescription) ? $widgetDescription : '' ?></span>
    <input class="showcase-widget" value="Point[Showcases][<?= $windowId ?>][]" value="<?= isset($widgetId) ? $widgetId : '' ?>"/>
</button>
