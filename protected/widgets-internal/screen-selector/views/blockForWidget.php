<?php
    $description = isset($widgetDescription) ? $widgetDescription : '';
    $disabled = isset($widgetId)  ? '' : 'disabled="disabled"';
?>

<?php if($editable): ?>
  <button class="<?= isset($widgetId) ? 'detach-widget' : 'attach-widget' ?> btn
    <?= isset($widgetId) ? 'btn-warning' : 'btn-success' ?>" type="button"
    data-window-id="<?= $windowId ?>" title="<?= $description ?>">
      <span class="glyphicon <?= isset($widgetId) ? 'glyphicon-off' : 'glyphicon-paperclip' ?>"></span>
      <span class="widget-detach-btn-text">Detach</span>
      <span class="widget-attach-btn-text">Attach widget</span>
      <span class="widget-name"><?= $description ?></span>
      <input class="showcase-widget" name="Point[showcases][<?= $windowId ?>]"
        value="<?= isset($widgetId) ? $widgetId : '' ?>"
        <?= $disabled ?>/>
  </button>
<?php elseif(!$editable && ($description !== '')): ?>
  <button class="btn btn-default" type="button"
    data-window-id="<?= $windowId ?>" title="<?= $description ?>">
      <span class="widget-detach-btn-text">Widget</span>
      <span class="widget-name"><?= $description ?></span>
  </button>
<?php endif; ?>
