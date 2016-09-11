var Receiver = function() {
	//var _url = '//localhost/getPlaylist.php';
	var _url = '/preview/ajaxGetChannels';
	
	function _receiveContent(pointId){
		return $.ajax({
			url: _url,
			dataType: 'json',
			type: 'POST',
			data: {
				pointId: pointId
			}
		}).fail(function(e){
			console.warn(e);
		})
	};
	
	return {
		receiveContent: _receiveContent
	};
}