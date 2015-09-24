var ScreenBlock = function(){
	this.snaptarget = Object();
	this.scale = {'X':0, 'Y':0};
	
	var _screenBlocks = Array();	
	
	function _calcPosition(block$, self){
		var x = ((block$.position().left) /
				self.scale['X']).toFixed(),
			y = ((block$.position().top)  /
				self.scale['Y']).toFixed();		

		return [x,y];
	};
	
	function _calcSize(block$, self){
		var height = ((block$.height() + 
				parseInt(self.snaptarget.css('padding-top')) * 2 - 
				parseInt(self.snaptarget.css('border-top-width')) * 2) / 
				self.scale['Y']).toFixed(),
			width = ((block$.width() + 
				parseInt(self.snaptarget.css('padding-left')) * 2 - 
				parseInt(self.snaptarget.css('border-left-width')) * 2) / 
				self.scale['X']).toFixed();
		return [width, height];
	};
	
	function _setMaxSize(block$, self){
		var totalX = (self.snaptarget.width() +
				parseInt(self.snaptarget.css('padding-left')) * 2 -
				parseInt(self.snaptarget.css('border-top-width')) * 2) -
				(block$.position().left),
			totalY = (self.snaptarget.height()  +
				parseInt(self.snaptarget.css('padding-top')) * 2 - 
				parseInt(self.snaptarget.css('border-top-width')) * 2) - 
				(block$.position().top);
		
		block$.css({
			'max-height': totalY,
			'max-width': totalX
		});
	};
	
	function _makeBlockResponsive(block$, extSelf){	
		var self = extSelf;
		
		
		block$.draggable({ 
			snap: true,
			containment: self.snaptarget, 
		})
		.resizable()
		.on('mouseup', function(e) {
			var this$ = $(this),
				xy = _calcPosition(this$, self),
				x = xy[0],
				y = xy[1],
				widthHeight = _calcSize(this$, self),
				width = widthHeight[0], 
				height = widthHeight[1];
			
			_setMaxSize(this$, self);
			this$.find('input').val(width+","+height+","+x+","+y);
			block$.find(".DraggableCoord").text("x("+x+"),y("+y+")")
		})
		.on('resize', function(e){
			var this$ = $(this),
				xy = _calcPosition(this$, self),
				x = xy[0],
				y = xy[1],
				widthHeight = _calcSize(this$, self),
				width = widthHeight[0], 
				height = widthHeight[1];
			this$.find('input').val(width+","+height+","+x+","+y);
			block$.find(".DraggableSize").text("w("+width+"),h("+height+")");
		}).on('dblclick', function(e){
			$(this).remove();
			var index = _screenBlocks.indexOf($(this));
			_screenBlocks.splice(index, 1)
		});
	};
	
	this.init = function(snaptarget, displayWidth, displayHeight){
		this.snaptarget = snaptarget;
		this.calcScale(displayWidth, displayHeight);
	};
	
	this.calcScale = function(displayWidth, displayHeight){
		this.scale = {
		    'X':this.snaptarget.width() / displayWidth, 
		    'Y':this.snaptarget.height() / displayHeight
		};
	};
	
	this.addBlock = function(blockName){
		var self = this,
			block$ = $("<div></div>")
				.addClass("Draggable")
				.addClass("ui-widget-content")
				.css({
					'max-width': self.snaptarget.width(),
					'max-height': self.snaptarget.height(),
					'width': '100px',
					'height': '100px',
				})
				.append(
					$("<input/>")
						.attr("type", 'text')
						.css("display", 'none')
						.attr('name', 'Blocks['+blockName+']')
				)
				.append(
					$("<p></p>")
						.addClass("DraggableName")
						.text(blockName)
				)
				.append(
					$("<p></p>")
						.addClass("DraggableCoord")
				)
				.append(
					$("<p></p>")
						.addClass("DraggableSize")
				)
				.appendTo(snaptarget);
		
		_setMaxSize(block$, self);
		
		var xy = _calcPosition(block$, self),
			x = xy[0],
			y = xy[1],
			widthHeight = _calcSize(block$, self),
			width = widthHeight[0], 
			height = widthHeight[1];
		
		block$.find('input').val(width+","+height+","+x+","+y);
		block$.find(".DraggableSize").text("w("+width+"),h("+height+")");
		block$.find(".DraggableCoord").text("x("+x+"),y("+y+")")
		
		_makeBlockResponsive(block$, self);
		_screenBlocks.push(block$);
	};
	

	
	this.removeAllBlocks = function(){
		$.each(_screenBlocks, function(ii, item){
			$(item).remove();
		});
		_screenBlocks = Array();
	}
	
	function _retrievePosition(actualLeft, actualTop, self){
		var x = (actualLeft * self.scale['X']).toFixed(),
			y = (actualTop * self.scale['Y']).toFixed();		
		return [x,y];
	};
	
	function _retrieveSize(actualWidth, actualHeight, self){
		var height = (actualHeight * 
				self.scale['Y']).toFixed(),
			width = (actualWidth * 
				self.scale['X']).toFixed();
		return [width, height];
	};
	
	this.establish = function(blocks$){
		var self = this;
		blocks$.each(function(index){
			var block$ = $(this),
			actualTop = parseInt(block$.css('top').replace("px", "")),
			actualLeft = parseInt(block$.css('left').replace("px", "")),
			actualWidth = parseInt(block$.css('width').replace("px", "")),
			actualHeight = parseInt(block$.css('height').replace("px", ""));
						
			var xy = _retrievePosition(actualLeft, actualTop, self),
				x = xy[0],
				y = xy[1],
				widthHeight = _retrieveSize(actualWidth, actualHeight, self),
				width = widthHeight[0], 
				height = widthHeight[1];
			
			block$.css({
				'position': 'absolute',
				'top': y+'px',
				'left': x+'px',
				'width': width+'px',
				'height': height+'px'
			});
			block$.find('input').val(actualWidth+","+actualHeight+","+actualLeft+","+actualTop);
			_makeBlockResponsive(block$, self);
			_setMaxSize(block$, self);
			_screenBlocks.push(block$);
		});
	}
	
	
}