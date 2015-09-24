<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */

$this->breadcrumbs=array(
	'Playlists'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List', 'url'=>array('index')),
	array('label'=>'Create', 'url'=>array('create')),
	array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete', 'url'=>'#', 
			'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
			'confirm'=>'Are you sure you want to delete this item?')),
	/*array('label'=>'Manage Playlists', 'url'=>array('admin')),*/
);
?>

<h1>View Playlists <?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
// 		'id',
		'name',
// 		'files',
		'fromDatetime',
		'toDatetime',
		array(
			'label' => 'Type',
			'type' => 'raw',
			'value' => $model->type ? "advertising" : "background"
		),
		array(
				'label' => $model->type ? "Repeating every" : "Broadcasting perion",
				'type' => 'raw',
				'value' => $model->type ? ($model->every) : 
					($model->fromTime . " - " . $model->toTime)
		)
// 		'sun',
// 		'mon',
// 		'tue',
// 		'wed',
// 		'thu',
// 		'fri',
// 		'sat',
// 		'author',
	),
)); ?>

<br>
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