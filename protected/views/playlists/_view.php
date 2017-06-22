<?php
/* @var $this PlaylistsController */
/* @var $data Playlists */
?>

<?php if ($index === 0): ?>
<?php
    $model = Playlists::model();
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
      <b><?php echo CHtml::encode($model->getAttributeLabel('type')); ?></b>
    </div>

    <div class="col col-md-3">
      <b><?php echo CHtml::encode($model->getAttributeLabel('fromDatetime')); ?> -
      <?php echo CHtml::encode($model->getAttributeLabel('toDatetime')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('fromTime')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('toTime')); ?></b>
    </div>

    <div class="col col-md-1">
      <b><?php echo CHtml::encode($model->getAttributeLabel('every')); ?></b>
    </div>

    <?php if ($user->checkAccess ("playlistViewUser")): ?>
      <div class="col-md-2">
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
    <?
      $type = 'unknown';
      if (Playlists::$types[$data->type]) {
          $type = Playlists::$types[$data->type];
      }

      echo CHtml::encode($type);
    ?>
  </div>

  <div class="col col-md-3">
    <?php echo CHtml::encode($data->fromDatetime); ?> -
    <?php echo CHtml::encode($data->toDatetime); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->fromTime); ?>
  </div>

  <div class="col col-md-1">
    <?php echo CHtml::encode($data->toTime); ?>
  </div>

  <div class="col col-md-1">
    <?php
      $every = '';
      if ($data->type == 2) {//0 - bg, 1- adv
          $every = $data->every;
      }

      echo CHtml::encode($every);
    ?>
  </div>

  <?php if (Yii::app ()->user->checkAccess ("playlistViewUser")): ?>
    <div class="col-md-2">
      <div class="btn-group">
        <?php if (Yii::app ()->user->checkAccess ("playlistViewUser")): ?>
          <form action="/playlists/view/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="View">
              <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>

        <?php if (Yii::app ()->user->checkAccess ("playlistEditUser")): ?>
          <form action="/playlists/update/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="Update">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>

        <?php if (Yii::app()->user->checkAccess ("playlistUser")): ?>
          <form action="/playlists/delete/<?= $data->id ?>" method="post" class="btn-group delete-playlist">
            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
              <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>
      </div>
   </div>
  <?php endif; ?>
</div>
