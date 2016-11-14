<?php
/* @var $this NetController */
/* @var $model Net */

$this->breadcrumbs=array(
	'Nets'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Net', 'url'=>array('index')),
	array('label'=>'Create Net', 'url'=>array('create')),
	array('label'=>'View Net', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Update Net <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<!-- Modal -->
<div id="dialog" title="Add playlist on channel">
  <p>
  <?php 

  	 $userName = Yii::app()->user->name;
 	 $pls = Playlists::model()->findAll(array("condition"=>"author = '{$userName}'","order"=>"id"));
 	 $attributeLabels = Playlists::model()->attributeLabels();
 	 if(count($pls) > 0)
	 {
	 	echo '<table class="table table-hover">';
	 	echo '<tr>';
	 	echo '<td></td>';
	 	echo '<td>' . $attributeLabels['name'] . '</td>';
	 	echo '<td>' . $attributeLabels['fromDatetime'] . '</td>';
	 	echo '<td>' . $attributeLabels['toDatetime'] . '</td>';
	 	echo '<td>' . $attributeLabels['fromTime'] . '</td>';
	 	echo '<td>' . $attributeLabels['toTime'] . '</td>';
	 	echo '<td>' . $attributeLabels['weekDays'] . '</td>';
	 	echo '</tr>';
	 	foreach ($pls as $pl)
	 	{
	 		$weedDays = "";
	 		if($pl['sun']) { $weedDays .= 'Sun '; }
	 		if($pl['mon']) { $weedDays .= 'Mon '; }
	 		if($pl['tue']) { $weedDays .= 'Tue '; }
	 		if($pl['wed']) { $weedDays .= 'Wed '; }
	 		if($pl['thu']) { $weedDays .= 'Thu '; }
	 		if($pl['fri']) { $weedDays .= 'Fri '; }
	 		if($pl['sat']) { $weedDays .= 'Sat '; }
	 		
		 	echo '<tr>';
		 	echo '<td>' . '<input class="SelectedPlsToAdd" type="checkbox" data-plidtoadd="'.$pl['id'].
		 		'" data-plnametoadd="'.$pl['name'].'">' . '</td>';
		 	echo '<td>' . $pl['name'] . '</td>';
		 	echo '<td>' . $pl['fromDatetime'] . '</td>';
		 	echo '<td>' . $pl['toDatetime'] . '</td>';
		 	echo '<td>' . $pl['fromTime'] . '</td>';
		 	echo '<td>' . $pl['toTime'] . '</td>';
		 	echo '<td>' . $weedDays . '</td>';
		 	echo '</tr>';
 		}
 		echo '</table>';
	 }
	 else 
	 {
	 	echo 'No avaliable playlists';
	 } 
  ?>
  </p>
</div>