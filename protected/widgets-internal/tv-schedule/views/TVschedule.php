<div id='tv-schedule-grid'>

<?php foreach ($tvBlocks as $item): ?>
    <?php $this->render('tvScheduleRow', [
        'rowId' => '',
        'dtFrom' => $item->dt_from,
        'dtTo' => $item->dt_to,
        'editable' => $editable,
        'postName' => $postName
    ]); ?>
<?php endforeach; ?>

</div>

<?php
    /*Row for js to allow just copy DOM on add button click */
?>

<?php $this->render('tvScheduleRow', [
    'rowId' => 'js-tv-schedule',
    'dtFrom' => '',
    'dtTo' => '',
    'editable' => $editable,
    'postName' => $postName
]); ?>

<?php if($editable): ?>
    <div>
        <button id="add-tv-period" class="btn btn-default" type="button">Add period</button>
    </div>
<?php endif; ?>
