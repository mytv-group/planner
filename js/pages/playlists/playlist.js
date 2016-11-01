$(document).ready(function(e){
	var url = document.location.origin + '/uploader/',
		moveFileSrc = document.location.origin + '/playlists/upload/',
		deleteFileSrc = document.location.origin + '/playlists/deletefilefrompl/',
		addfilefromheapSrc = document.location.origin + '/playlists/addfilefromheap/',
		folderSrc = document.location.origin + '/admin/getfoldercontent/',
		viewSrc = document.location.origin + '/admin/view/',
		Playlist_name = $("#Playlist_name"),
		playlistIdTag = $("#playlistId"),
		filesBlock = $("#filesBlock"),
		selectedjsTreeNode = 0;
	
	$("#Playlists_fromDatetime").datetimepicker({
		format:'Y-m-d H:i:s'
	});
	
	$("#Playlists_toDatetime").datetimepicker({
		format:'Y-m-d H:i:s'
	});
	
	$("#Playlists_fromTime").datetimepicker({
		format: 'H:i:s', 
		datepicker:false,
	});
	
	$("#Playlists_toTime").datetimepicker({
		format: 'H:i:s', 
		datepicker:false,
	});
	
	$("#Playlists_every").datetimepicker({
		format: 'H:i:s', 
		datepicker:false,
	});

	$("#Playlists_midnightOffset").datetimepicker({
		format: 'H:i:s', 
		datepicker:false,
	});

	var typeBlock = $(".type-control"),
		type = $(".type-control:checked").val(),
		streamUrlBlock = $('#stream-url-block'),
		fileManager = $('#file-manager'),
		everyBlock = $("#everyBlock"),
		periodBlock = $("#periodBlock");

	var hideFunctionality = function(type, animationTime) {
		if(type == 0){
			everyBlock.slideUp(animationTime);
			periodBlock.slideDown(animationTime);
			streamUrlBlock.slideUp(animationTime);
			fileManager.slideDown(animationTime);
		} else if(type == 1){
			periodBlock.slideUp(animationTime);
			everyBlock.slideDown(animationTime);
			streamUrlBlock.slideUp(animationTime);
			fileManager.slideDown(animationTime);
		} else if(type == 2){
			everyBlock.slideUp(animationTime);
			periodBlock.slideDown(animationTime);
			streamUrlBlock.slideDown(animationTime);
			fileManager.slideUp(animationTime);
		}
	}
	
	hideFunctionality(type, 0);
	
	typeBlock.on('change', function(e) {
		hideFunctionality($(e.target).val(), 200);
	});

});