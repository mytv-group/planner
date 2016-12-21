<?php
/* @var $this PointController */
/* @var $model Point */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'point-form',
    'enableAjaxValidation'=>true,
)); ?>

<?php
    $isView = false;
    if(isset($isViewForm)
        && ($isViewForm === true)
    ) {
        $isView = true;
    }
?>

    <?php if(!$isView): ?>
        <p class="note">Fields with <span class="required">*</span> are required.</p>
    <?php endif; ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>"form-control", 'readonly' => $isView)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'ip'); ?>
        <?php echo $form->textField($model,'ip',array('size'=>60,'maxlength'=>255, 'class'=>"form-control", 'readonly' => $isView)); ?>
        <?php echo $form->error($model,'ip'); ?>
    </div>

    <div class="row">
        <?php echo $form->hiddenField($model,'username',array('value'=>Yii::app()->user->name)); ?>
    </div>


        <div class="row">
            <?php echo $form->labelEx($model,'volume'); ?>
            <?php if(!$isView): ?>
                <?php echo $form->hiddenField($model,'volume'); ?>

                <section>
                    <span class="tooltip"></span>
                    <!-- Tooltip -->
                    <div id="slider"></div>
                    <!-- the Slider -->
                    <span class="volume"></span>
                    <!-- Volume -->
                </section>
            <?php else: ?>
                <?php echo $model->volume . ' %'; ?>
            <?php endif; ?>

            <?php echo $form->error($model,'volume'); ?>
        </div>

    <?php echo $form->hiddenField($model,'TV', array('value' => 0)); ?>

    <div class="row">
        <?php echo $form->hiddenField($model,'tv_schedule_blocks'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'TVschedule'); ?>
        <?php echo "<br><div id='periodContainer'></div>"; ?>
        <?php if(!$isView): ?>
            <?php echo "<p><button id='addTVperiod' class='btn btn-default'>Add period</button></p>"; ?>
        <?php endif; ?>
    </div>

    <div class="row">
    <?php

        if(!$model->isNewRecord)
        {
            echo $form->labelEx($model,'channels');

            printf("<div id='channelsList'>");
            $playlistsToPoint = $model->playlistToPoint;

            for ($ii = 1; $ii <= 3; $ii++)
            {
                printf("<div class='ChannelsContainer btn-toolbar' data-channelid='%s' role='toolbar' aria-label=''>",
                    $ii);

                printf("<div class='btn-group' role='group' aria-label=''>" .
                    "<button type='button' class='btn btn-default ChannelButt'>".
                        "Channel %s </button>".
                    "<button type='button' class='AddPlaylistsBut btn btn-info' data-channelid='%s'>" .
                    "<span class='glyphicon glyphicon-plus'></span> Add playlists" .
                    "</button></div>", $ii, $ii);

                $channelPlaylists = [];
                foreach ($playlistsToPoint as $pl) {
                    if ($pl->channel_type == $ii) {
                        $channelPlaylists[] = Playlists::model()->findByPk($pl->id_playlist);;
                    }
                }

                if (count($playlistsToPoint) > 0) {
                    echo "<div class='btn-group' role='group' aria-label=''>";
                }
                foreach ($channelPlaylists as $pl) {
                        printf("<button type='button' class='PlaylistLinks btn btn-default' ".
                                "data-plid='%s'>%s</button>",
                            $pl['id'], CHtml::link($pl['name'], array('playlists/' . $pl['id'])));
                        printf("<button type='button' class='RemovePlaylist btn btn-danger' ".
                                "data-plidtoremove='%s' ".
                                "data-channelidpltoremove='%s' ".
                            ">x</button>", $pl['id'], $ii);
                }

                if (count($playlistsToPoint) > 0) {
                    echo "</div>";
                }

                echo "</div>";
            }

            printf("</div>");
        }
    ?>

    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'screen_id'); ?>

        <?php
            $dropDown = array();
            $userId = Yii::app()->user->getId();
            $userModel = User::model()->findByPk($userId);
            $ScreenModels = $userModel->screens;
            $selectedItems = array();

            foreach ($ScreenModels as $val)
            {
                $dropDown[$val->id] = $val->name;
            }

            if(!$model->isNewRecord)
            {
                $ScreenModelId = $model->screen_id;
                $selectedItems[$ScreenModelId] = array('selected'=>'selected');

            }

            echo $form->dropDownList($model, 'screen_id',
                    $dropDown,
                    array('options'=>$selectedItems,
                        'multiple'=>false,'class'=>'form-control','size'=>'10'));

            printf ( "<div id='windowsList'>" );

            if(!$model->isNewRecord)
            {
                $channels = $model->channels;
                $ScreenModelId = $model->screen_id;
                if($ScreenModelId != null){
                    $ScreenModel = Screen::model()->findByPk($ScreenModelId);

                    if(isset($ScreenModel->windows)) {
                        $windows = $ScreenModel->windows;
                        foreach ($windows as $window) {

                            $windowId = $window->id;
                            $windowName = $window->name;

                            foreach ($channels as $channel) {
                                if(($channel->id_point === $model->id) && ($channel->window_id === $window->id)) {
                                    printf ( "<div class='ChannelsContainer btn-toolbar' data-channelid='%s' role='toolbar' aria-label=''>", $channel ['id'] );

                                    $widgetToChannel = WidgetToChannel::model()->find("channel_id = :channel_id",
                                        array("channel_id" => $channel['id']));

                                    printf ("<div class='btn-group' role='group' aria-label=''>" .
                                            "<button type='button' class='btn btn-default ChannelButt' disabled='disabled'>Screen %s</button>",
                                            $windowName);

                                    if(count($widgetToChannel) > 0) {
                                        $widgetModel = Widget::model()->findByPk($widgetToChannel['widget_id']);
                                        printf ("<button type='button' class='detach-widget btn btn-warning' data-channelid='%s'>".
                                                "<span class='glyphicon glyphicon-off'></span> Detach widget " . $widgetModel['name'] .
                                                "</button>",  $channel ['id']);
                                    } else {
                                        printf ("<button type='button' class='attach-widget btn btn-success' data-channelid='%s'>".
                                                "<span class='glyphicon glyphicon-paperclip'></span> Attach widget" .
                                                "</button>",  $channel ['id']);
                                    }

                                    echo "</div>";
                                }
                            }
                        }
                    }
                }
            }

        printf ( "</div>" );

            ?>
        <?php echo $form->error($model,'screen_id'); ?>
    </div>

    <div class="row">
        <?php
            echo $form->hiddenField($model,'sync', array("value"=>"0"));
            if(!$model->isNewRecord) {
                echo CHtml::tag('div',array('id'=> 'pointId', 'data-value'=>$model->id));
            }
        ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>"form-control")); ?>
    </div>

<?php $this->endWidget(); ?>

</div>
<!-- form -->
