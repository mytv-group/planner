<?php
    $id = '';
    if($rowId !== '') {
        $id = 'id="'.$rowId.'"';
    }
?>

<?php
    $prop = '';
    if(!$editable || ($rowId !== '')) {
        $prop = 'disabled="disabled"';
    }
?>

<div <?= $id; ?>>
    <input name="Point[tvScheduleFrom][]" type="text" size="15"
      class="form-control tv-schedule-datetime" title="From datetime" value="<?= $dtFrom; ?>" <?= $prop; ?>/>
    <input name="Point[tvScheduleTo][]" type="text" size="15"
      class="form-control tv-schedule-datetime" title="To datetime" value="<?= $dtTo; ?>" <?= $prop; ?>>
    <?php if($editable): ?>
        <button class="btn btn-danger remove-tv-block" type="button"> x </button>
    <?php endif; ?>
</div>
