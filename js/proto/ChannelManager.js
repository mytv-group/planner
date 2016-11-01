var ChannelManager = function(itemId, isNet){
	var _itemId = itemId,
		_isNet = isNet;
	
	var _addChannelAdr = document.location.origin + '/channel/addChannel';
	this.AddChannel = function(){
		return $.ajax({
      	  url: _addChannelAdr,
    	  type: "POST",
    	  data: {
    		  itemId: _itemId
    	  },
    	  dataType: "json",
    	}).fail(function(e){
			console.log(e);
		});
	}
	
	var _removeChannelAdr = document.location.origin + '/channel/removeChannel';
	this.removeChannel = function(channelId){
		return $.ajax({
      	  url: _removeChannelAdr,
    	  type: "POST",
    	  data: {
    		  channelId: channelId
    	  },
    	  dataType: "json",
    	}).fail(function(e){
			console.log(e);
		});
	}
	
	var _addPlaylistToChannelAddr = document.location.origin + '/channel/addPlaylistToChannel';
	this.AddPlaylistToChannel = function(channelId, plId, plName, addPlFunc){
		return $.ajax({
      	  url: _addPlaylistToChannelAddr,
    	  type: "POST",
    	  data: {
    		  isNet: _isNet,
    		  channelId: channelId,
    		  plId: plId
    	  },
    	  dataType: "json",
    	  success: function(e){
    		  addPlFunc(e, plId, plName)
    	  }
    	}).fail(function(e){
			console.log(e);
		});
	}
	
	var _removePlaylistFromChannelAddr = document.location.origin + '/channel/removePlaylistFromChannel';
	this.RemovePlaylistFromChannel= function(channelId, plId){
		return $.ajax({
      	  url: _removePlaylistFromChannelAddr,
    	  type: "POST",
    	  data: {
    		  isNet: _isNet,
    		  channelId: channelId,
    		  plId: plId
    	  },
    	  dataType: "json",
    	}).fail(function(e){
			console.log(e);
		});
	}
}

