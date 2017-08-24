$(document).ready(function(e){
	$('.moveup').click(function() {
		var $el = $(this).parent();
		if(!$el.is(':first-child')) {
			$el.insertBefore($el.prev()); 
			$(this)[0].focus();
			sendOrder();
		}
	});

	$('.movedown').click(function() {
		var $el = $(this).parent();
		if(!$el.is(':last-child')) {
			$el.insertAfter($el.next()); 
			$(this)[0].focus();
			sendOrder();
		}
	});
	
	function setRowNums() {
		$('.btn-num-label').each(function(index, item) {
			$(item).text(index+1);
		})
	};
	
	function sendOrder() {
		var playlistId = $("#playlistId").val();
		var files = [];
		$('.num').each(function(index, item) {
			files.push($(item).data('fileid'));
		});
		
		$('.moveup, .movedown').find('btn').addClass('disabled');
		
		$.ajax({
			type: "POST",
			url: document.location.origin + '/playlists/setFileOrder',
			dataType: 'json',
			data: {
				playlistId: playlistId,
				files: files
			}
		}).always(function(e) {
			$('.moveup, .movedown').find('btn').removeClass('disabled');
			setRowNums();
		});
	};
});