<?php
/* @var $this NetController */
/* @var $model Net */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'net-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name', array('size'=>60,'maxlength'=>100, 'class'=>"form-control")); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pointsattached'); ?>

		<?php
			$dropDown = array();
			$userId = Yii::app()->user->getId();
			$userModel = User::model()->findByPk($userId);
			$pointsAvaliable = $userModel->pointsavaliable;
			$selectedItems = array();
			
			foreach ($pointsAvaliable as $val)
			{
				$dropDown[$val->id] = $val->name;
			}

			if(!$model->isNewRecord)
			{
				$pointsAttached = $model->pointsattached; //in case new record this wont be exist
				foreach ($pointsAttached as $val)
				{
					$selectedItems[$val->id] = array('selected'=>'selected');
				}
			}
			
			echo $form->dropDownList($model, 'pointsattached', 
					$dropDown,
					array('options'=>$selectedItems, 
					'multiple'=>true,'class'=>'form-control','size'=>'10'));
			?>
		<?php echo $form->error($model,'points'); ?>
	</div>
		
	<div class="row">
		<?php echo $form->labelEx($model,'screen_id'); ?>

		<?php
			$dropDown = array();
			$userId = Yii::app()->user->getId();
			$userModel = User::model()->findByPk($userId);
			$screens = $userModel->screens;
			$selectedItems = array();
			
			foreach ($screens as $val)
			{
				$dropDown[$val->id] = $val->name;
			}

			if(!$model->isNewRecord)
			{
				$screenId = $model->screen_id; 
				$selectedItems[$screenId] = array('selected'=>'selected');

			}
			
			echo $form->dropDownList($model, 'screen_id', 
					$dropDown,
					array('options'=>$selectedItems, 
						'multiple'=>false,'class'=>'form-control','size'=>'10'));

			printf ( "<div id='windowsList'>" );
			
			if(!$model->isNewRecord) 
			{
				$screenId = $model->screen_id;
				$screen = Screen::model()->findByPk($screenId);
				$windows = $screen->windows;
				foreach ($windows as $window) 
				{
					$windowId = $window->id;
					$windowInst = Window::model()->findByPk($windowId);
					$windowName = $windowInst->name;
					$channels = $windowInst->netChannels;
										
					foreach ($channels as $channel) {
						if($channel->net_id == $model->id) {
							printf ( "<div class='ChannelsContainer btn-toolbar' data-channelid='%s' role='toolbar' aria-label=''>", $channel ['id'] );
							$channelM = NetChannel::model()->findByPk($channel['id']);
							$pls = $channelM->playlists;
						
							printf ( "<div class='btn-group' role='group' aria-label=''>" . 
									"<button type='button' class='btn btn-default ChannelButt' disabled='disabled'>Screen %s</button>" . 
								"<button type='button' class='AddPlaylistsBut btn btn-info' data-channelid='%s'>" . 
								"<span class='glyphicon glyphicon-plus'></span> Add playlists" . "</button></div>", $windowName, $channel ['id'] );
						
							foreach ( $pls as $pl ) 
							{
								echo "<div class='btn-group' role='group' aria-label=''>";
								printf ( "<button type='button' class='PlaylistLinks btn btn-default' " . "data-plid='%s'>%s</button>", $pl ['id'], CHtml::link ( $pl ['name'], array (
									'playlists/' . $pl ['id'] 
								)));
								printf ( "<button type='button' class='RemovePlaylist btn btn-danger' " . "data-plidtoremove='%s' " . "data-channelidpltoremove='%s' " . ">x</button>", $pl ['id'], $channel ['id'] );
								echo "</div>";
							}
						
							echo "</div>";
						}
					}
				}
			}
		
		printf ( "</div>" );

			?>
		<?php echo $form->error($model,'screen_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->hiddenField($model,'user_id', array('value' => Yii::app()->user->id)); ?>
		<?php 
			if(!$model->isNewRecord) {
				echo CHtml::tag('div',array('id'=> 'netId', 'data-value'=>$model->id));
			} 
		?>
	</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>"form-control")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->