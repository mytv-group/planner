<?php
    $pointId = isset($point->id) ? $point->id : '';
    $screenId = -1;
    if (isset($point->screen) && isset($point->screen->id)) {
        $screenId = $point->screen->id;
    }

    $prop = '';
    if(!$editable) {
        $prop = 'disabled="disabled"';
    }
?>

<div class='screen-selector-grid'>
    <select class="point-screen form-control" size="10" <?= $prop ?>
        name="<?= $postName ?>[screen_id]"
        data-point-id="<?=$pointId ?>"
    >
        <? foreach ($screens as $item): ?>
            <option value="<?= $item->id; ?>"
              <?= ($item->id == $screenId) ? "selected='selected'" : '' ?>
            >
                <?= $item->name; ?>
            </option>
        <? endforeach; ?>
    </select>
</div>

<div class='screen-showcases' data-point-id="<?=$pointId ?>">
    <?php if (isset($point->screen) && isset($point->screen->windows)): ?>
        <?php $this->render('screenWindows', [
            'isActive' => true,
            'pointId' => $pointId,
            'screenId' => $point->screen->id,
            'windows' => $point->screen->windows,
            'editable' => $editable,
            'postName' => $postName
        ]); ?>
    <? endif; ?>

    <? foreach ($screens as $screen): ?>
        <?php if (isset($screen->windows)): ?>
            <?php $this->render('screenWindows', [
                'isActive' => false,
                'pointId' => $pointId,
                'screenId' => $screen->id,
                'windows' => $screen->windows,
                'editable' => $editable,
                'postName' => $postName
            ]); ?>
        <?php endif; ?>
    <? endforeach; ?>
</div>
