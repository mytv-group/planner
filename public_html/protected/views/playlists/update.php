<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */

$this->breadcrumbs=array(
	'Playlists'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
	array('label'=>'Create', 'url'=>array('create')),
	array('label'=>'View', 'url'=>array('view', 'id'=>$model->id)),
	/*array('label'=>'Manage Playlists', 'url'=>array('admin')),*/
);
?>

<h1>Update Playlists <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'stream'=>$stream)); ?>

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
	
	<div id='filesPreviewContainer' style='display:block;'>
	<?php 
		$filesToPreview = $model->GetFilesInPlaylist($model->id);
		
		printf("<table>");
		
		foreach($filesToPreview as $key => $val)
		{
			$fileid = $val['id'];
			$mime = $val['mime'];
			$link = $val['link'];
			$name = $val['name'];
			$mimeType = $val['mimeType'];
			$mimeFormat = $val['mimeFormat'];
		
			printf("<tr data-fileid='%s'>", $fileid);
		
			if(($mimeType == 'video') &&
				(($mimeFormat == 'mp4') ||
				($mimeFormat == 'mpeg') ||
				($mimeFormat == 'ogg') ||
				($mimeFormat == 'webm')))
			{
				printf('<td>' .
						'<button id="preplayAudioItemButt" type="button" class="btn btn-default PreviewBtn" data-type="%s" data-mime="%s" data-link="%s">'.
							'<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>'.
						'</button>' .
						'</td>', $mimeType, $mimeFormat, $link);
					
				printf("<td class='PreviewContentMiddleCell'>");
				PrintLinkContainer($link, $name);
			}
			else if(($mimeType == 'audio') &&
					(($mimeFormat == 'mp3') ||
							($mimeFormat == 'mpeg') ||
							($mimeFormat == 'ogg')))
			{
				printf('<td>' .
						'<button id="preplayAudioItemButt" type="button" class="btn btn-default PreviewBtn" data-type="%s" data-mime="%s" data-link="%s">'.
							'<span class="glyphicon glyphicon-music" aria-hidden="true"></span>'.
						'</button>' .
	    				'</td>', $mimeType, $mimeFormat, $link);
					
				printf("<td class='PreviewContentMiddleCell'>");
				PrintLinkContainer($link, $name);
			}
			else if($mimeType == 'image')
			{
				printf("<td style='vertical-align:middle;'></td>");
				printf("<td data-index='%s' data-mime='%s' data-link='%s' class='PreviewContentRow'>",
					$key, $mimeFormat, $link);
				PrintImageContainer($link);
			}
			else
			{
				printf("<td style='vertical-align:middle;'></td>");
				printf("<td data-index='%s' data-mime='%s' data-link='%s' class='PreviewContentMiddleCell'>",
					$key, $mimeFormat, $link);
				PrintLinkContainer($link, $name);
			}
			
			printf('</td><td class="DeleteItemCell">' .
					'<button id="delItemButt" data-fileid="%s" type="button" class="btn btn-default alert alert-danger" data-plid="%s" role="alert">'.
						'<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'.
					'</button>', $fileid, $model->id);
			printf("</td></tr>");
		
		}
		
		printf("</table>");
		
		printf('<div id="dialogVideoPreview" title="Video preview" style="display:none">' .
				'<p></p>'.
			'</div>');
		
		//there is jp-volume-max glitch, resolving by style add
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
			
			<span class="btn btn-default fileinput-button" style="width:120px; margin-top:20px;">
				<span>Choose files</span>
			    <input id="addFiles" type="input">
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
				
				/*printf("<div class='LinkContainer'>" . 
					"<a href='%s'>%s</a>".
				"</div>", $link, $name);*/
			} 
		?>
</div>