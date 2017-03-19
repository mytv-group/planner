<?php
    $screenId = -1;
    if (isset($point->screen) && isset($point->screen->id)) {
        $screenId = $point->screen->id;
    }

    $prop = '';
    if(!$editable) {
        $prop = 'disabled="disabled"';
    }
?>

<div id='screen-selector-grid'>
    <select id="point-screen-id" class="form-control" name="Point[screen_id]" size="10" <?= $prop ?>>
        <? foreach ($screens as $item): ?>
            <option value="<?= $item->id; ?>"
              <?= ($item->id == $screenId) ? "selected='selected'" : '' ?>
            >
                <?= $item->name; ?>
            </option>
        <? endforeach; ?>
    </select>
</div>

<div id='screen-showcases'>
    <?php if (isset($point->screen) && isset($point->screen->windows)): ?>
        <?php $this->render('screenWindows', [
            'isActive' => true,
            'screenId' => $point->screen->id,
            'windows' => $point->screen->windows,
            'editable' => $editable
        ]); ?>
    <? endif; ?>

    <? foreach ($screens as $screen): ?>
        <?php if (isset($screen->windows)): ?>
            <?php $this->render('screenWindows', [
                'isActive' => false,
                'screenId' => $screen->id,
                'windows' => $screen->windows,
                'editable' => $editable
            ]); ?>
        <?php endif; ?>
    <? endforeach; ?>
</div>
