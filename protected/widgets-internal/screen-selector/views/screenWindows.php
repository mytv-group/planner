<?php foreach ($windows as $window): ?>
    <div class="showcases-container btn-toolbar" role="toolbar" aria-label="">
        <div class="btn-group" role="group" aria-label="">
            <button class="btn btn-default channel-btn" disabled="disabled">
                Window <span class="screen-name"><?= $window->name; ?></span>
            </button>

            <?php $widget = isset($window->widget) ? $window->widget : null; ?>

            <?php $this->render('blockForWidget', [
                'windowId' => $window->id,
                'widgetId' => isset($widget->id) ? $widget->id : null,
                'widgetDescription' => isset($widget->description) ? $widget->description : null,
                'editable' => $editable
            ]); ?>
        </div>
    </div>
<?php endforeach; ?>
