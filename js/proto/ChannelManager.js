var ChannelManager = function(itemId, isNet){
  var _itemId = itemId,
    _isNet = isNet;

  var _addPlaylistToChannelAddr = document.location.origin + '/point/addPlaylistToChannel';
  this.AddPlaylistToChannel = function(channelType, plId, pointId, plName, addPlFunc){
    return $.ajax({
          url: _addPlaylistToChannelAddr,
        type: "POST",
        data: {
          isNet: _isNet,
          channelType: channelType,
          plId: plId,
          pointId: pointId
        },
        dataType: "json",
        success: function(e){
          addPlFunc(e, plId, plName);
        }
      }).fail(function(e){
          console.log(e);
      });
  };

  var _removePlaylistFromChannelAddr = document.location.origin + '/point/removePlaylistFromChannel';
  this.RemovePlaylistFromChannel= function(channelType, plId, pointId){
    return $.ajax({
          url: _removePlaylistFromChannelAddr,
        type: "POST",
        data: {
          isNet: _isNet,
          channelType: channelType,
          plId: plId,
          pointId: pointId
        },
        dataType: "json",
      }).fail(function(e){
          console.log(e);
      });
  };
};
