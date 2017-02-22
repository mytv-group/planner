<?php /* @var $this Controller */ ?>

<?php
	Yii::app()->clientScript->registerCoreScript('jquery');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-lightness/jquery-ui-1.10.4.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<!-- body -->

<body>


<div class='row Jumbotron'>

	<div class='col-sm-12 LogoStrip'>
		<div class='col-sm-2'>
			<div class='LogoBlock'></div>
		</div>

		<div class='col-sm-4 LogoAppName'>
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

<div class='row'>
	<div class='col-sm-4 col-md-2 MainMenu'>
		<?php $this->renderPartial("/site/menu"); ?>
	</div>

	<div class='col-sm-8 col-md-10 MainContent'>
		<?php echo $content; ?>
	</div>
</div>

<div id="footer" class='row col-sm-12'>
	<div><center>
		<p>Copyright &copy; <?php echo date('Y'); ?> by RTV group.</br>
		All Rights Reserved.</p>
	</center></div>
</div><!-- footer -->

</div><!-- page -->

</body>
</html>
