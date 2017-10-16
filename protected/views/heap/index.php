<h1>Content Heap</h1>

<?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'heap-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => ['enctype' => 'multipart/form-data'],
    ]);
?>

<?php $this->widget('AjaxUploadWidget', [
    'config' => [
        'action' => Yii::app()->createUrl('heap/upload'),
        'multiple' => true,
        'allowedExtensions' => Yii::app()->params['fileUploadAllowedExtensions'],//array("jpg","jpeg","gif","exe","mov" and etc...
        'sizeLimit' => Yii::app()->params['fileUploadMaxSize'],// maximum file size in bytes
    ]
]);
?>

<?php $this->endWidget(); ?>

<div class='heap__tree-container'>
    <div id="tree" class="Tree">
        <div id="treeGeneral" class="SubTree">
        </div>

        <div id="treePrivate" class="SubTree">
        </div>
    </div>

    <div id='dropzoneHeap' class="Heap">
    </div>
</div>

<div id="dialogVideoPreview" title="Video preview" style="display:none">
<p></p></div>

<div id="dialogAudioPreplay" title="Audio preplay" style="display:none">
<p></p></div>

<div id="dialogImagePreview" title="Dialog image preview" style="display:none">
<p></p></div>
