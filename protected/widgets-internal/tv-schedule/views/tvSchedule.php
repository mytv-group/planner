<div class='tv-schedule'>
  <div class='tv-schedule-grid'>

  <?php foreach ($tvBlocks as $item): ?>
      <?php $this->render('tvScheduleRow', [
          'className' => '',
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
      'className' => 'js-tv-schedule',
      'dtFrom' => '',
      'dtTo' => '',
      'editable' => $editable,
      'postName' => $postName
  ]); ?>

  <?php if($editable): ?>
      <div>
          <button class="add-tv-period btn btn-default" type="button">Add period</button>
      </div>
  <?php endif; ?>
</div>
