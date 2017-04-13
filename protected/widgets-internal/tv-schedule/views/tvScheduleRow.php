<?php
    $prop = '';
    if(!$editable || ($className !== '')) {
        $prop = 'disabled="disabled"';
    }
?>

<div class="<?= $className ?>">
    <input name="<?= $postName ?>[tvScheduleFrom][]" type="text" size="15"
      class="form-control tv-schedule-datetime" title="From datetime" value="<?= $dtFrom; ?>" <?= $prop; ?>/>
    <input name="<?= $postName ?>[tvScheduleTo][]" type="text" size="15"
      class="form-control tv-schedule-datetime" title="To datetime" value="<?= $dtTo; ?>" <?= $prop; ?>>
    <?php if($editable): ?>
        <button class="btn btn-danger remove-tv-block" type="button"> x </button>
    <?php endif; ?>
</div>
