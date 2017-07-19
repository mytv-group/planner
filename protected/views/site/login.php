<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
?>

<h2>Welcome</h2>

<h4>Media content management system</h4>

<h4>RTV Group. <a href='http://rtvgroup.com.ua'>www.rtvgroup.com.ua</a></h4>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'login-form',
'enableClientValidation'=>false,
'clientOptions'=>array(
  'validateOnSubmit'=>true,
),
)); ?>

<p>
    <div class="row">
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username', ['class'=>"form-control"]); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>
</p>

<p>
    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model, 'password', ['class'=>"form-control"]); ?>
        <?php echo $form->error($model,'password'); ?>

    </div>
</p>

<div class="row rememberMe">
      <?php echo $form->checkBox($model,'rememberMe'); ?>
      <?php echo $form->label($model,'rememberMe'); ?>
      <?php echo $form->error($model,'rememberMe'); ?>
</div>

<p>
    <div class="row buttons" >
        <?php echo CHtml::submitButton('Login', ['class'=>"btn btn-default"]); ?>
    </div>
</p>

<?php $this->endWidget(); ?>
</div><!-- form -->
