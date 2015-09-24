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
	
	var typeBlock = $("#Playlists_type"),
		type = (function(typeBlock){
			if(typeBlock.attr("checked"))
				return true;
			else
				return false;
		})(typeBlock),
		everyBlock = $("#everyBlock"),
		periodBlock = $("#periodBlock");
	
	
	if(type){
		everyBlock.css("display", "block");
		periodBlock.css("display", "none");
	} else {
		everyBlock.css("display", "none");
		periodBlock.css("display", "block");
	}

	typeBlock.bootstrapSwitch({
		state: type,
		size: 'normal',
		onText: 'advertising',
		offText: 'background',
		onColor: 'success',
		offColor: 'info',
		onSwitchChange: function(e, state){
			if(state){
				periodBlock.slideUp();
				everyBlock.slideDown();
			} else {
				everyBlock.slideUp();
				periodBlock.slideDown();
			}
		}
	});
});