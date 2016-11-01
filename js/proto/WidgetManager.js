var WidgetManager = function(){	
	var _attachWidgetAddr = document.location.origin + '/channel/attachWidget',
	_attachWidgetReq = function(channelId, widgetId){
		return $.ajax({
      	  url: _attachWidgetAddr,
    	  type: "POST",
    	  data: {
    		  channelId: channelId,
    		  widgetId: widgetId
    	  },
    	  dataType: "json",
    	}).fail(function(e){
			console.log(e);
		});
	},
	_detachWidgetAddr = document.location.origin + '/channel/detachWidget',
	_detachWidgetReq = function(channelId){
		return $.ajax({
      	  url: _detachWidgetAddr,
    	  type: "POST",
    	  data: {
    		  channelId: channelId
    	  },
    	  dataType: "json",
    	}).fail(function(e){
			console.log(e);
		});
	};
	
	return {
		attachWidgetReq: _attachWidgetReq,
		detachWidgetReq: _detachWidgetReq
	};
}

