<?php if ($index === 0): ?>
<?php
    $model = Point::model();
    $user = Yii::app()->user;
?>
  <div class="row row-header">
    <div class="col-md-1">
      <b>#</b>
    </div>

    <div class="col col-md-2">
      <b><?php echo CHtml::encode($model->getAttributeLabel('name')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></b>
    </div>

    <div class="col col-md-2">
      <b><?php echo CHtml::encode($model->getAttributeLabel('ip')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('volume')); ?></b>
    </div>

    <div class="col col-md-2">
      <p><b><u><?php echo CHtml::encode($model->getAttributeLabel('sync_time')); ?></u></b> / </p>
      <p><b><?php echo CHtml::encode($model->getAttributeLabel('update_time')); ?></b></p>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('screen')); ?></b>
    </div>

    <?php if ($user->checkAccess ("pointViewUser")): ?>
      <div class="col col-md-2">
        <b><?php echo CHtml::encode($model->getAttributeLabel('options')); ?></b>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<div class="row row-list-view">
  <div class="col col-md-1">
    <b><?= $index + 1 ?></b>
  </div>

  <div class="col col-md-2">
    <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?>
  </div>

  <div class="col col-md-1">
    <?php
        if ($data->sync) {
            echo '<span class="is-up-to-date status-glyph glyphicon glyphicon-ok-circle" aria-hidden="true"></span>';
        } else {
            echo '<span class="is-waiting-sync status-glyph glyphicon glyphicon-refresh" aria-hidden="true"></span>';
        }

        if (Yii::app()->pointService->checkIpOnline($data->ip)) {
            echo '<span class="is-online status-glyph glyphicon glyphicon-globe" aria-hidden="true"></span>';
        } else {
            echo '<span class="is-offline status-glyph glyphicon glyphicon-off" aria-hidden="true"></span>';
        }
    ?>
  </div>

  <div class="col col-md-2">
    <?php echo CHtml::encode($data->ip); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->volume); ?>
  </div>

  <div class="col col-md-2">
    <p><u><?php echo CHtml::encode($data->sync_time); ?></u></p>
    <p><?php echo CHtml::encode($data->update_time); ?></p>
  </div>

  <div class="col col-md-1">
    <?= '<button type="button" class="show-point-screen btn btn-default btn-sm" ' .
            'data-id="'.$data->id.'" data-ip="'.$data->ip.'">
          <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
        </button>';
    ?>
  </div>

  <?php if (Yii::app ()->user->checkAccess ("pointViewUser")): ?>
    <div class="col-md-2">
      <div class="btn-group">
        <?php if (Yii::app ()->user->checkAccess ("pointViewUser")): ?>
          <form action="/point/view/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="View">
              <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>

        <?php if (Yii::app ()->user->checkAccess ("pointEditUser")): ?>
          <form action="/point/update/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="Update">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>

        <?php if (Yii::app()->user->checkAccess ("pointUser")): ?>
          <form action="/point/delete/<?= $data->id ?>" method="post" class="btn-group delete-point">
            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
              <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>
      </div>
   </div>
  <?php endif; ?>
</div>
