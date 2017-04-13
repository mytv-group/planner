<?php
/* @var $this NetController */
/* @var $data Net */
?>

<?php if ($index === 0): ?>
<?php
    $net = Net::model();
    $user = Yii::app()->user;
?>
  <div class="row row-header">
    <div class="col-md-1">
      <b>#</b>
    </div>

    <div class="col-md-1">
      <b><?php echo CHtml::encode($net->getAttributeLabel('id')); ?></b>
    </div>

    <div class="col-md-4">
      <b><?php echo CHtml::encode($net->getAttributeLabel('name')); ?></b>
    </div>

    <div class="col-md-2">
      <b><?php echo CHtml::encode($net->getAttributeLabel('id_user')); ?></b>
    </div>

    <div class="col-md-2">
      <b><?php echo CHtml::encode($net->getAttributeLabel('dt_created')); ?></b>
    </div>

    <?php if ($user->checkAccess ("netViewUser")): ?>
      <div class="col-md-2">
        <b><?php echo CHtml::encode($net->getAttributeLabel('options')); ?></b>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<div class="row row-list-view">
  <div class="col-md-1">
    <b><?= $index + 1 ?></b>
  </div>

  <div class="col-md-1">
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  </div>

  <div class="col-md-4">
    <?php echo CHtml::encode($data->name); ?>
  </div>

  <div class="col-md-2">
    <?php echo CHtml::encode($data->user->name); ?>
  </div>

  <div class="col-md-2">
    <?php echo CHtml::encode($data->dt_created); ?>
  </div>

  <?php if (Yii::app ()->user->checkAccess ("netViewUser")): ?>
    <div class="col-md-2">
      <div class="btn-group">
        <?php if (Yii::app ()->user->checkAccess ("netViewUser")): ?>
          <form action="/net/view/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="View">
              <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>

        <?php if (Yii::app ()->user->checkAccess ("netEditUser")): ?>
          <form action="/net/copy/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="Copy">
              <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
            </button>
          </form>

          <form action="/net/update/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="Update">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>
          </form>

          <form action="/net/individualUpdate/<?= $data->id ?>" type="post" class="btn-group">
            <button type="submit" class="btn btn-default btn-sm" title="Update">
              <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>

        <?php if (Yii::app ()->user->checkAccess ("netUser")): ?>
          <form action="/net/delete/<?= $data->id ?>" method="post" class="btn-group delete-net">
            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
              <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
          </form>
        <?php endif; ?>
      </div>
   </div>
  <?php endif; ?>
</div>
