<?php echo $form->hiddenField($model,'TV', array('value' => 0)); ?>

<div class="row">
    <?php echo $form->hiddenField($model,'tv_schedule_blocks'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'TVschedule'); ?>
    <?php echo "<br><div id='periodContainer' data-isview='".$isView."'></div>"; ?>
    <?php if(!$isView): ?>
        <?php echo "<p><button id='addTVperiod' class='btn btn-default'>Add period</button></p>"; ?>
    <?php endif; ?>
</div>
