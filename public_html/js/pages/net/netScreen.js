$(document).ready(function(e){
	
	var pointId = $("#pointId").data('value'),
		windowsList = $("#windowsList"),
		CM = new ChannelManager(pointId);

	//TODO dynamic screens channels channging on select screen change 

//	$("#addChannel").on("click", function(e){
//		e.preventDefault();
//		e.stopPropagation();
//		
//		var $this = $(this);
//		CM.AddChannel().done(function(mess){
//			var status = mess.status,
//				newChannelId = mess.id,
//				newChannelInternalId = mess.internalId;
//			
//			var curChannelsContainer = $("<div></div>")
//				.appendTo(channelList)
//				.addClass('ChannelsContainer btn-toolbar')
//				.attr("data-channelid", newChannelId)
//				.attr("role", 'toolbar')
//				.attr("aria-label",'');
//	
//			 var btnGroup = $("<div></div>")
//				.appendTo(curChannelsContainer)
//				.addClass('btn-group')
//				.attr("role",'group')
//				.attr("aria-label", '');
//			 		 
//			 $("<button></button>")
//				.appendTo(btnGroup)
//				.addClass('btn btn-default ChannelButt')
//				.append(
//						$("<span/>")
//							.addClass("glyphicon glyphicon-minus")
//							.css("color", "#F05757")
//				)
//				.prepend("Channel " + newChannelInternalId + " ")
//				.on('click', function(e){
//					var this$ = $(this),
//						channelRow = this$.parents(".ChannelsContainer"),
//						newChannelId = channelRow.data("channelid");
//					CM.removeChannel(newChannelId).done(function(res){
//						channelRow.remove();
//					});
//				});
//			
//			 var curButt = $("<button></button>")
//				.appendTo(btnGroup)
//				.addClass('AddPlaylistsBut btn btn-info')
//				.attr("data-channelid", newChannelId)
//				.attr("type", 'button');
//				
//			$("<span></span>")
//				.appendTo(curButt)
//				.addClass('glyphicon glyphicon-plus');
//			
//			curButt.append(" Add playlists");
//		});
//	});
	
});