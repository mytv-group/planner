<h1>Content Heap</h1>

<?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'heap-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => ['enctype' => 'multipart/form-data'],
    ]);
?>

    <div class='row-file-upload'>
        <div class='col-ajax-upload-widget'>
            <?php $this->widget('AjaxUploadWidget', [
                'config' => [
                    'action' => Yii::app()->createUrl('heap/upload'),
                    'multiple' => true,
                    'allowedExtensions' => Yii::app()->params['fileUploadAllowedExtensions'],//array("jpg","jpeg","gif","exe","mov" and etc...
                    'sizeLimit' => Yii::app()->params['fileUploadMaxSize'],// maximum file size in bytes
                ]
            ]);
            ?>
        </div>

        <div class='col-hashtags-input-widget'>
            <?php $this->widget('HashtagsInputWidget', []); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

<div class='heap-tree-container'>
    <div id="tree" class="tree">
        <div id="treeGeneral" class="sub-tree">
        </div>

        <div id="treePrivate" class="sub-tree">
        </div>
    </div>

    <div id='dropzoneHeap' class="heap">
    </div>
</div>

<div id="dialogVideoPreview" title="Video preview" style="display:none">
<p></p></div>

<div id="dialogAudioPreplay" title="Audio preplay" style="display:none">
<p></p></div>

<div id="dialogImagePreview" title="Dialog image preview" style="display:none">
<p></p></div>

<?php Yii::app()->templateEngine->put('file-list-item'); ?>
<?php Yii::app()->templateEngine->put('folder-list-item'); ?>
