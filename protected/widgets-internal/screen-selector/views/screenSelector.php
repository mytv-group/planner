<?php
  $screenId = -1;
  if (isset($point->screen) && isset($point->screen->id)) {
      $screenId = $point->screen->id;
  }
?>

<div id='screen-selector-grid'>
    <select id="point-screen-id" class="form-control" name="Point[id_screen]" size="10">
        <? foreach ($screens as $item): ?>
            <option value="<?= $item->id; ?>"
              <?= ($item->id == $screenId) ? "selected='selected'" : '' ?>
            >
                <?= $item->name; ?>
            </option>
        <? endforeach; ?>
    </select>
</div>

<div class="windows-list is-active">
  <?php if (isset($point->screen) && isset($point->screen->windows)): ?>
      <?php $this->render('screenWindows', [
          'windows' => $point->screen->windows,
          'editable' => $editable
      ]); ?>
  <? endif; ?>
</div>

<? foreach ($screens as $screen): ?>
    <div class="windows-list">
        <?php if (isset($screen->windows)): ?>
            <?php $this->render('screenWindows', [
                'windows' => $screen->windows,
                'editable' => $editable
            ]); ?>
        <?php endif; ?>
    </div>
<? endforeach; ?>


    <?php
        /*$dropDown = array();
        $userId = Yii::app()->user->getId();
        $userModel = User::model()->findByPk($userId);
        $ScreenModels = $userModel->screens;
        $selectedItems = array();

        foreach ($ScreenModels as $val)
        {
            $dropDown[$val->id] = $val->name;
        }

        if(!$model->isNewRecord) {
            $ScreenModelId = $model->screen_id;
            $selectedItems[$ScreenModelId] = array('selected'=>'selected');
        }

        if (!$isView) {
            echo $form->dropDownList($model, 'screen_id',
                    $dropDown,
                    array('options'=>$selectedItems,
                        'multiple'=>false,'class'=>'form-control','size'=>'10'));
        } else {
            echo "<b><a href='" .
                Yii::app()->createUrl('screen/' . $model->screen_id). "'>" .
                Screen::model()->findByPk($model->screen_id)->name .
            "</a></b>";
            echo "<br><br>";
        }

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

                                    printf ("<button type='button' class='detach-widget btn btn-warning' data-channelid='%s' %s>".
                                            "<span class='glyphicon glyphicon-off'></span> %s %s" .
                                            "</button>",  $channel ['id'], $isView ? "disabled='disabled'" : '', $isView ? "Widget" : "Detach widget", $widgetModel['name'] );
                                } else if (!$isView) {
                                    printf ("<button type='button' class='attach-widget btn btn-success' data-channelid='%s'>".
                                            "<span class='glyphicon glyphicon-paperclip'></span> Attach widget" .
                                            "</button>",  $channel ['id']);
                                }

                                printf ("</div></div>");
                            }
                        }
                    }
                }
            }
        }

    printf ( "</div>" );*/

?>
