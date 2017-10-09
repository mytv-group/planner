<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */

$this->menu=array(
    array('label'=>'List', 'url'=>array('index')),
    array('label'=>'Create', 'url'=>array('create')),
    array('label'=>'View', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Update Playlists <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', [
    'model'=>$model,
    'stream'=>$stream,
    'isUpdateForm' => true
]); ?>

<div id='file-manager'>
    <div id="filesBlock">
        <?php echo '<input id="playlistId" style="display:none" value="' . $model->id .'">' ?>
        <!-- The fileinput-button span is used to style the file input field as button -->
        <span class="btn btn-default fileinput-button" style="width:120px">
            <span>Upload files</span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="files[]" multiple>
        </span>

        <span id="chooseFiles" class="btn btn-default fileinput-button" style="width:120px">
            <span>Choose files</span>
        </span></br></br>

        <!-- The dropzone -->
        <div id='dropzone'>The drop zone</div>

        <!-- The global progress bar -->
        <div id="progress" class="progress">
            <div class="progress-bar progress-bar-default"></div>
        </div>
        <!-- The container for the uploaded files -->
        <div id="files" class="files"></div>
        <br>

    </div>

    <div class='shuffle-controls'>
        <button id='shuffle-btn' class='btn btn-info'>Shuffle</button>
    </div>

    <div id='filesPreviewContainer'>
    <?php
        $filesToPreview = $model->relatedFiles;

        printf("<ul id='sortable' class='unstyled list-unstyled'>");

        $ii = 0;
        foreach ($filesToPreview as $key => $val) {
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

            printf('<span class="moveup">' .
                    '<button type="button" class="btn btn-default">'.
                    '<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>'.
                    '</button>' .
                    '</span>');

            printf('<span class="movedown">' .
                    '<buttontype="button" class="btn btn-default">'.
                    '<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>'.
                    '</button>' .
                    '</span>');

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

            printf('<span class="DeleteItemCell">' .
                    '<button id="delItemButt" data-fileid="%s" type="button" class="btn btn-default alert alert-danger" data-plid="%s" role="alert">'.
                        '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'.
                    '</button>' .
                    '</span>', $fileid, $model->id);

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

        printf('<div id="dialogHeap" title="Files heap" style="display:none">
        <p>
            <div id="tree" class="Tree">
                <div id="treeGeneral" class="SubTree">
                </div>
                <div id="treePrivate" class="SubTree">
                </div>
            </div>

            <div id="dropzoneHeap" class="Heap">
            </div>

            <span id="check-all-in-folder" class="btn btn-default btn-dialog-heap">
                Check all in folder
            </span>

            <span id="uncheck-all-in-folder" class="btn btn-default btn-dialog-heap">
                Uncheck all in folder
            </span>

            <span class="btn btn-default btn-dialog-heap choose-files-button">
                <span>Choose files</span>
            </span>
        </p>
        </div>');
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
