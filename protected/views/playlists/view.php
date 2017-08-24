<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */


$this->menu=array(
    array('label'=>'List', 'url'=>array('index')),
    array('label'=>'Create', 'url'=>array('create')),
    array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete', 'url'=>'#',
            'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View <?php echo $model->name; ?></h1>

<?php
$this->renderPartial('_form', [
    'model' => $model,
    'stream' => $stream,
    'isViewForm' => true
]);
?>

<div id='file-manager'>

    <div id='filesPreviewContainer' style='display:block;'>
    <?php
        $filesToPreview = $model->relatedFiles;

        printf("<ul id='sortable' class='unstyled list-unstyled'>");

        $ii = 0;
        foreach($filesToPreview as $key => $val)
        {
            $ii++;
            $fileid = $val['id'];
            $mime = $val['mime'];
            $link = $val['link'];
            $name = substr($val['name'], 13, strlen($val['name']));
            $mime = explode('/', $val['mime']);
            $mimeType = $mime[0];
            $mimeFormat = $mime[1];

            printf("<li data-fileid='%s'>", $fileid);

            printf('<span class="num" data-fileid="%s" data-plid="%s">' .
                    '<button type="button" class="btn btn-default btn-num disabled">'.
                    '<span aria-hidden="true" class="btn-num-label">'.$ii.'</span>'.
                    '</button>' .
                    '</span>', $fileid, $model->id);

            if(($mimeType == 'video') &&
                (($mimeFormat == 'mp4') ||
                ($mimeFormat == 'mpeg') ||
                ($mimeFormat == 'ogg') ||
                ($mimeFormat == 'webm')))
            {
                printf('<span>' .
                        '<button id="preplayAudioItemButt" type="button" class="btn btn-default PreviewBtn" data-type="%s" data-mime="%s" data-link="%s">'.
                            '<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>'.
                        '</button>' .
                        '</span>', $mimeType, $mimeFormat, $link);

                printf("<span class='PreviewContentMiddleCell'>");
                PrintLinkContainer($link, $name);
            }
            else if(($mimeType == 'audio') &&
                    (($mimeFormat == 'mp3') ||
                            ($mimeFormat == 'mpeg') ||
                            ($mimeFormat == 'ogg')))
            {
                printf('<span>' .
                        '<button id="preplayAudioItemButt" type="button" class="btn btn-default PreviewBtn" data-type="%s" data-mime="%s" data-link="%s">'.
                            '<span class="glyphicon glyphicon-music" aria-hidden="true"></span>'.
                        '</button>' .
                        '</span>', $mimeType, $mimeFormat, $link);

                printf("<span class='PreviewContentMiddleCell'>");
                PrintLinkContainer($link, $name);
            }
            else if($mimeType == 'image')
            {
                printf("<span style='vertical-align:middle;'></span>");
                printf("<span data-index='%s' data-mime='%s' data-link='%s' class='PreviewContentRow'>",
                    $key, $mimeFormat, $link);
                PrintImageContainer($link);
            }
            else
            {
                printf("<span style='vertical-align:middle;'></span>");
                printf("<span data-index='%s' data-mime='%s' data-link='%s' class='PreviewContentMiddleCell'>",
                    $key, $mimeFormat, $link);
                PrintLinkContainer($link, $name);
            }

            printf('</span>');

            printf("</li>");

        }

        printf("</ul>");

        printf('<div id="dialogVideoPreview" title="Video preview" style="display:none">' .
                '<p></p>'.
            '</div>');

        printf('<div id="dialogAudioPreplay" title="Audio preplay" style="display:none">' .
                '<p></p>'.
            '</div>');

        printf('<div id="dialogImagePreview" title="Dialog image preview" style="display:none">' .
                '<p></p>'.
            '</div>');
        ?>

        </div>

        <?php
            function PrintImageContainer($link)
            {
                printf('<img src="%s" class="ImgInPreviewTable">', $link);
            }

            function PrintLinkContainer($link, $name)
            {
                printf('<a href="%s"><button class="btn btn-default LinkButton" type="button">'.
                '<span aria-hidden="true">%s</span>'.
                '</button></a>', $link, $name);
            }
        ?>
</div>
