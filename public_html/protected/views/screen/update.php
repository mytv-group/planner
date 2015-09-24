<?php
/* @var $this ScreenController */
/* @var $model Screen */

// $this->breadcrumbs=array(
// 	'Screens'=>array('index'),
// 	$model->name=>array('view','id'=>$model->id),
// 	'Update',
// );

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
	array('label'=>'Create', 'url'=>array('create')),
	array('label'=>'View', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Update Screen <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<div id="snaptargetBorder">
	<div id="snaptarget">
		<?php foreach ($windows as $window): ?>
			<div class="Draggable ui-widget-content" style="
				top:<?= $window->top?>px; 
				left:<?= $window->left?>px;
				width:<?= $window->width?>px;
				height:<?= $window->height?>px;">
			 <input name="Blocks[<?= $window->name?>]" style="display: none;" type="text"
			 	val="<?=$window->width?>,<?=$window->height?>,<?=$window->top?>,<?=$window->left?>" >
			 <p class="DraggableName"><?= $window->name?></p>
			 <p class="DraggableCoord">x(<?= $window->left?>),y(<?= $window->top?>)</p>
			 <p class="DraggableSize">w(<?= $window->width?>),h(<?= $window->height?>)</p>
			</div>
		<?php endforeach; ?>
	</div>
</div> 
<br style="clear:both">

<p>Make double click on block to remove it.</p>

<div class="row">
	<div class='col-md-4'>
  		<input id='screenBlockName' class='form-control'>
  	</div>
  	
  	<div class='col-md-4'>
	  <button id='addDraggableBlock' class='btn btn-success'>
	 	<span class="glyphicon glyphicon-plus"></span> One more
	  </button>
	</div>
</div>
