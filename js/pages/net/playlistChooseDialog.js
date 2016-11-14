$(document).ready(function(e){
	
	var netId = $("#netId").data('value'),
		channelList = $("#channelsList, #windowsList"),
		CM = new ChannelManager(netId, true);//isNet - true

	//
	// addPlaylist butt click and dialog support
	//
	var dialog = $("#dialog").dialog({
		autoOpen: false,
		width: 900,
		minHeight: 300,
		maxHeight: 600,
		show: "fade",
	    modal: true,
	    buttons: {
	        Add: function() {
	        	var selectedPls = $(".SelectedPlsToAdd:checked"),
	        		channelid = dialog.data("channelidplaylisttoadd"),
	        		curChannelContainer = channelList.find('.ChannelsContainer[data-channelid="' + channelid + '"]'),
	        		playlistLinksInChannel = channelList.find('.ChannelsContainer[data-channelid="' + channelid + '"] .PlaylistLinks');
	        		        		
	        	var channelPlIds = function(arr){
	        		var channelPlIdsArr = [];
	        		for(var ii = 0; ii < arr.length; ii++){
	        			channelPlIdsArr.push($(arr[ii]).data("plid"));
	        		}
	        		return channelPlIdsArr;
	        	}
	        	
	        	for(var ii = 0; ii < selectedPls.length; ii++){
	        		var plId = $(selectedPls[ii]).data('plidtoadd'),
	        			plName = $(selectedPls[ii]).data('plnametoadd').toString();
	        		
	        		if($.inArray(plId, channelPlIds(playlistLinksInChannel)) == -1){
	        			CM.AddPlaylistToChannel(channelid, plId, plName, function(e, plId, plName){
	        				if(e.status == 'ok'){
	        					curChannelContainer.append(
	    	        					"<div class='btn-group' role='group' aria-label=''>" +
	    	        					"<button type='button' class='PlaylistLinks btn btn-default' data-plid='" + plId + "'>"+
	    	        					"<a href='/playlists/" + plId + "'>" + plName + "</a></button>" + 
	    	        					"<button type='button' class='RemovePlaylist btn btn-danger' "+
	    	        					"data-plidtoremove='" + plId + "' data-channelidpltoremove='" + channelid + "'>" + 
	    	        					"x" + "</button>" +
	    	        					"</div>");
	        				} else if(e.status == 'err'){
	        					alert(e.error);
	        				}
	        			});
	        		}
	        	}
	        	
	            $(this).dialog("close");
	        }
	    }
	});
	
	$("#channelsList, #windowsList").on("click", ".AddPlaylistsBut", function(e){
		e.preventDefault();
		e.stopPropagation();
		
		var $this = $(this),
			channelid = $this.data("channelid");
		
		dialog.data("channelidplaylisttoadd", channelid);
		dialog.dialog('open');
	});
	
	$("#channelsList, #windowsList").on("click", ".RemovePlaylist", function(e){
		e.preventDefault();
		e.stopPropagation();
		
		var $this = $(this),
			channelId = $this.data("channelidpltoremove"),
			plId = $this.data("plidtoremove");
		
		if(confirm("Romeve playlist from channel?")){
			CM.RemovePlaylistFromChannel(channelId, plId).done(function(e){
				if(e.status == 'ok'){
					$this.parents(".btn-group").remove();
				} else if(e.status == 'err'){
					alert(e.error);
				}
			});			
		}
	});
});