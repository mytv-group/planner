<div class="row">
    <?php echo $form->labelEx($model,'volume'); ?>
    <?php if(!$isView): ?>
        <?php echo $form->hiddenField($model,'volume'); ?>

        <section>
            <span class="tooltip"></span>
            <!-- Tooltip -->
            <div id="slider"></div>
            <!-- the Slider -->
            <span class="volume"></span>
            <!-- Volume -->
        </section>
    <?php else: ?>
        <?php echo $model->volume . ' %'; ?>
    <?php endif; ?>

    <?php echo $form->error($model,'volume'); ?>
</div>
