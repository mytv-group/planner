var LayoutFactory = function(container) {
	//var _url = '//localhost/getPlaylist.php';
	var _container = container,
		_layouts = [],
		_num = 0;
	
	function _addLayout(width, height, top, left, widget, num){
		if(num === undefined){
			num = _num = _num + 1;
		}
		
		var layout1 = $("<video></video>")
			.attr('id', 'id'+num)
			.addClass('class'+num)
			.prop('loop', true)
			.css({
				'position': 'absolute',
				'width': width,
				'height': height,
				'top': top,
				'left': left,
				'display': 'block',
				'background-color': 'transparant'
			})
			.append(
				$("<source></source>")
					.attr('type', 'video/mp4')
			);
		
		var layout2 = $("<video></video>")
			.attr('id', 'id'+num+'Support')
			.addClass('class'+num)
			.prop('loop', true)
			.attr('width', width)
			.attr('height', height)
			.css({
				'position': 'absolute',
				'width': width,
				'height': height,
				'top': top,
				'left': left,
				'display': 'none',
				'background-color': 'transparant'
			})
			.append(
				$("<source></source>")
					.attr('type', 'video/mp4')
			);

		_layouts.push([layout1, layout2]);
		_container.append(layout1);
		_container.append(layout2);
		_container.append(widget);
		
		return num;
	};
	
	function _setInitialContent(mainId, supportId, source, nextSource){
		var v = document.getElementById(mainId),
			v2 = document.getElementById(supportId);	
		$("#" + mainId).find(':first-child').attr('src', source);
		v.load();
		v.play();
		
		$("#" + supportId).find(':first-child').attr('src', nextSource);
		v2.load();
		v2.pause();
	};
	
	function _setSingleContent(mainId, supportId, tm){
		var setTimeoutFn = function(){	
			var v = document.getElementById(mainId),
				v2 = document.getElementById(supportId);	
		
			$("#" + mainId).css('display', 'block');
			v.play();
			
			v2.pause();
			$("#" + supportId).css('display', 'none');	
		};
		
		setTimeout(function() { setTimeoutFn() }, tm);		
	};
	
	function _setContent(mainId, supportId, source, tm){
		//console.log(tm);
		//console.log(nextMoovie);
		var setTimeoutFn = function(){	
			console.log(nextMoovie);
			var v = document.getElementById(mainId),
				v2 = document.getElementById(supportId);	
			
			$("#" + mainId).css('display', 'block');
			v.play();
			
			v2.pause();
			$("#" + supportId).css('display', 'none');	
			$("#" + supportId).find(':first-child').attr('src', '');
			$("#" + supportId).find(':first-child').attr('src', source);
			v2.load();
		};
		
		setTimeout(function() { setTimeoutFn() }, tm);
	};
		
	function _getNextId(){
		return _num + 1;
	};
	
	function _clearLayouts(){
		$.each(_layouts, function(ii, item){
			$(item).remove();
		});
		return true;
	};
	
	return {
		addLayout: _addLayout,
		clearLayouts: _clearLayouts,
		setInitialContent: _setInitialContent,
		setSingleContent: _setSingleContent,
		setContent: _setContent
	};
}