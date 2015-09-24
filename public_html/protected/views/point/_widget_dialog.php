<!-- Modal -->
<div id="widget-dialog" title="Attach widget channel">
  <p>
  <?php 

  	 $userId = Yii::app()->user->id;
 	 $widgets = Widget::model()->findAll("user_id = :user_id", array("user_id"=>$userId));
 	 
 	 $attributeLabels = Widget::model()->attributeLabels();
 	 if(count($widgets) > 0)
	 {
	 	echo '<table class="table table-hover">';
	 	echo '<tr>';
	 	echo '<td></td>';
	 	echo '<td>' . $attributeLabels['name'] . '</td>';
	 	echo '<td>' . $attributeLabels['created_dt'] . '</td>';
	 	echo '<td>' . $attributeLabels['updated_dt'] . '</td>';
	 	echo '</tr>';
	 	foreach ($widgets as $widget)
	 	{	 		
		 	echo '<tr>';
		 	echo '<td>' . '<input class="selected-widget" type="radio" name="selected-widget" '.
		 			'data-widgetid="'.$widget['id']. '" ' .
		 			'data-widgetname="'.$widget['name'].'"/>'.
		 		'</td>';
		 	echo '<td>' . $widget['name'] . '</td>';
		 	echo '<td>' . $widget['created_dt'] . '</td>';
		 	echo '<td>' . $widget['updated_dt'] . '</td>';
		 	echo '</tr>';
 		}
 		echo '</table>';
	 }
	 else 
	 {
	 	echo 'No avaliable widgets';
	 } 
  ?>
  </p>
</div>