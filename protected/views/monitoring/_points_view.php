<?php if ($index === 0): ?>
<?php
    $model = Point::model();
    $user = Yii::app()->user;
?>
  <div class="row row-header">
    <div class="col-md-1">
      <b>#</b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('id')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('name')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('ip')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('volume')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('sync_time')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('update_time')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('sync')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></b>
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

  <div class="col col-md-1">
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->name); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->ip); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->volume); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->sync_time); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->update_time); ?>
  </div>

  <div class="col col-md-1">
    <?= '<div class="switch">
          <input type="checkbox" '.($data->sync ? 'checked' : '').' disabled="disabled">
          <label>
            <span class="fontawesome-ok"></span>
            <span class="fontawesome-remove"></span>
            <div></div>
          </label>
        </div>';
    ?>
  </div>

  <div class="col col-md-1">
    <?php
        $isOnline = Yii::app()->pointService->checkIpOnline($data->ip);
        $glyphicon = $isOnline ? 'glyphicon-globe' : 'glyphicon-eye-close';
        $btnType = $isOnline ? 'btn-success' : 'btn-danger';
        echo '<button type="button" class="btn '.$btnType.' btn-sm" disabled>
              <span class="glyphicon '.$glyphicon.'" aria-hidden="true"></span>
            </button>';
    ?>
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
