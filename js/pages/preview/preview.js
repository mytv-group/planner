$(document).ready(function(e){
	var R = Receiver(),
		LF = LayoutFactory($('body')),
		pointId = $("#pointId").html();
	R.receiveContent(pointId)
	.done(function(msg){
		var layouts = msg;
		//var ii = 0;
		for(ii in layouts) {
			var layout = layouts[ii];
				playlist = layouts[ii]['content'],
				widget = layouts[ii]['widget'],
				id = LF.addLayout(layout.width, layout.height, layout.top, layout.left, widget);
			
			for(var jj = 0; jj < playlist.length; jj++){
				var timeout = parseInt(playlist[jj][0] + '000'),
					moovie = playlist[jj][1];
				
				if((playlist.length > 1) && (jj < playlist.length - 1)) {
					nextMoovie = playlist[jj + 1][1];
					
					if(jj == 0) {
						LF.setInitialContent('id'+id, 'id'+id+"Support", moovie, nextMoovie);
					} else {
						if((jj % 2) == 0) {
							LF.setContent('id'+id, 'id'+id+"Support", nextMoovie, timeout);
						} else {
							LF.setContent('id'+id+"Support", 'id'+id, nextMoovie, timeout);
						}
					}
				} else {
					if((jj % 2) == 0) {
						LF.setSingleContent('id'+id, 'id'+id+"Support", timeout);
					} else {
						LF.setSingleContent('id'+id+"Support", 'id'+id, timeout);	
					}
				}
			}
		}
	});
});


//$(document).ready(function(e){	
//	var videoArr = [
//	    'video/eset.mp4',
//	    'video/marks.mp4',
//	    'video/ducati.mp4',
//	],
//	curPlayingVideo = -1,
//	videoHeight = $('#video2').height() + 47;
//	console.log(videoHeight);
//	
//	$('#img').css({
//		'position': 'absolute',
//		'top':'100px',
//		'left':'200px',
//	});
//	
//	$("#text").css({
//		'height':'400px',
//		'width':'500px',
//		'font-size':'3em',
//		'color':'#fff',
//		'position': 'absolute',
//		'top':'100px',
//		'right':'200px',
//	});
//	
//	var video1 = document.getElementById("video1"),
//		video2 = document.getElementById("video2"),
//		bgVideo = document.getElementById("bgVideo");
//		
//	video1.play();
//	video2.play();
//	
//	video1.volume = 0;
//	video2.volume = 0;
//	
//	setNextBgVideo();
//	setInterval(function(){
//		changeVideo();
//		setNextBgVideo();
//	}, 15000);
//	
//	function setNextBgVideo() {
//		curPlayingVideo += 1;
//		if($('#bgVideo').css('display') == 'none'){
//			$('#bgVideo :first-child').attr('src', videoArr[curPlayingVideo]);
//			bgVideo.load();
//			bgVideo.pause();
//			
//		} else {
//			$('#video1 :first-child').attr('src', videoArr[curPlayingVideo]);
//			video1.load();
//			video1.pause();
//		}
//		
//		console.log(curPlayingVideo);
//		console.log(videoArr[curPlayingVideo]);
//	
//		if(curPlayingVideo >= videoArr.lenght){
//			curPlayingVideo = -1;
//		}
//		
//	};
//	
//	function changeVideo() {
//		if($('#bgVideo').css('display') === 'none'){
//			$('#bgVideo').css('display', 'block');
//			$('#bgVideo').css('height', videoHeight);
//			$('#video1').css('display', 'none');
//
//			console.log('bgheight ' + $('#bgVideo').height());
//			console.log('bgwidth ' + $('#bgVideo').width());
//			
//			video1.pause();
//			bgVideo.play();
//			bgVideo.volume = 0;
//		} else {
//			$('#video1').css('display', 'block');
//			$('#video1').css('height', videoHeight);
//			$('#bgVideo').css('display', 'none');
//			
//			console.log('bgheight ' + $('#video1').height());
//			console.log('bgwidth ' + $('#video1').width());
//			
//			bgVideo.pause();
//			video1.play();
//			video1.volume = 0;
//		}
//	};
//});