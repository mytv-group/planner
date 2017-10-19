<?php /* @var $this Controller */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="language" content="en" />

  <link rel="stylesheet" type="text/css" href="
    <?php
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(INDEX_PATH.'/css/main.css'
            )
        );
    ?>
  "/>

  <link rel="stylesheet" type="text/css" href="
    <?php
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(INDEX_PATH.'/css/form.css'
            )
        );
    ?>
  "/>

  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<!-- body -->

<body>
<div class='row jumbotron'>

  <div class='col-sm-12 logo-strip'>
    <div class='col-sm-2'>
      <div class='logo-block'></div>
    </div>

    <div class='col-sm-4 logo-app-name'>
      <?php echo CHtml::encode(Yii::app()->name); ?>
    </div>

    <div class='col-sm-4'>
      <?= 'Support: 044 364 12 65' ?>
    </div>

    <div class='col-sm-2'>
      <a href='/site/logout'>Logout (<?= Yii::app ()->user->name ?>)</a>
    </div>
  </div>
</div>

<div class='container'>
    <div class='col-sm-4 col-md-2 main-menu'>
    <?php $this->renderPartial("/site/menu"); ?>
    </div>


    <div class='col-sm-8 col-md-10 content-wraper'>
        <div class='content'>
            <?php echo $content; ?>
        </div>
    </div>
</div>

<div class="footer" class='row col-sm-12'>
  <div><center>
    <p>Copyright &copy; <?php echo date('Y'); ?> by RTV group.</br>
    All Rights Reserved.</p>
  </center></div>
</div><!-- footer -->

</div><!-- page -->

</body>
</html>
