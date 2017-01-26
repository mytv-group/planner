<div class="row">
<?php
    if(!$model->isNewRecord) {
        echo $form->labelEx($model,'channels');

        printf("<div id='channelsList'>");
        $playlistsToPoint = $model->playlistToPoint;

        for ($ii = 1; $ii <= 3; $ii++)
        {
            printf("<div class='ChannelsContainer btn-toolbar' data-channelid='%s' role='toolbar' aria-label=''>",
                $ii);

            printf("<div class='btn-group' role='group' aria-label=''>" .
                "<button type='button' class='btn btn-default ChannelButt'>".
                    "Channel %s </button>", $ii);

            if (!$isView) {
                printf("<button type='button' class='AddPlaylistsBut btn btn-info' data-channelid='%s'>" .
                    "<span class='glyphicon glyphicon-plus'></span> Add playlists" .
                    "</button>", $ii);
            }

            printf("</div>");

            $channelPlaylists = [];
            foreach ($playlistsToPoint as $pl) {
                if ($pl->channel_type == $ii) {
                    $channelPlaylists[] = Playlists::model()->findByPk($pl->id_playlist);;
                }
            }

            foreach ($channelPlaylists as $pl) {
                    printf("<div class='btn-group' role='group'>");
                    printf("<button type='button' class='PlaylistLinks btn btn-default' ".
                            "data-plid='%s'>%s</button>",
                        $pl['id'], CHtml::link($pl['name'], array('playlists/' . $pl['id'])));

                    if (!$isView) {
                        printf("<button type='button' class='RemovePlaylist btn btn-danger' ".
                                "data-plidtoremove='%s' ".
                                "data-channelidpltoremove='%s' ".
                            ">x</button>", $pl['id'], $ii);
                    }
                    printf("</div>");
            }

            echo "</div>";
        }

        printf("</div>");
    }
?>

</div>
