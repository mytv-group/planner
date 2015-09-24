$(document).ready(function(e){
	var deleteFileSrc = document.location.origin + '/playlists/deletefilefrompl/',
		addfilefromheapSrc = document.location.origin + '/playlists/addfilefromheap/',
		folderSrc = document.location.origin + '/admin/getfoldercontent/',
		viewSrc = document.location.origin + '/admin/view/',
		Playlist_name = $("#Playlist_name"),
		playlistIdTag = $("#playlistId"),
		filesBlock = $("#filesBlock"),
		selectedjsTreeNode = 0;
	
	var dialogVideoPreview = $("#dialogVideoPreview").dialog({ 
			autoOpen: false,
			resizable: false,
			modal: true,
			close: function( event, ui ) {
				$(this).find('p').empty();
			},
			open: function( event, ui ) {
				var el = $(this),
					currPreviewContainer = el.find('p'),
					mime = el.data('mime'),
					link = el.data('link');
				
				$("ui-button-icon-primary ui-icon ui-icon-closethick ui-state-default").css({
					'background-position': '-16px -144px;'
				});
								
				currPreviewContainer.append(BuildVideoContainer());
				
				if((mime == 'mp4') || (mime == 'mpeg')){
					$("#jquery_jplayer").jPlayer({
						ready: function () {
							$(this).jPlayer("setMedia", {
								//title: "Big Buck Bunny Trailer",
								//m4v: "http://www.jplayer.org/video/m4v/Big_Buck_Bunny_Trailer.m4v",
								//ogv: "http://www.jplayer.org/video/ogv/Big_Buck_Bunny_Trailer.ogv",
								//webmv: "http://www.jplayer.org/video/webm/Finding_Nemo_Teaser.webm",
								m4v: link
							});
							//$(this).play();
						},
						swfPath: "js",
						supplied: "m4v",
						cssSelectorAncestor: "#jp_container",
						globalVolume: true,
						smoothPlayBar: true,
						keyEnabled: true
					});
				} else if(mime == 'ogg') {
					$("#jquery_jplayer").jPlayer({
						ready: function () {
							$(this).jPlayer("setMedia", {
								ogv: link
							});
							//$(this).play();
						},
						swfPath: "js",
						supplied: "ogv",
						cssSelectorAncestor: "#jp_container",
						globalVolume: true,
						smoothPlayBar: true,
						keyEnabled: true
					});
				} else if((mime == 'webm')) {
					$("#jquery_jplayer").jPlayer({
						ready: function () {
							$(this).jPlayer("setMedia", {
								webmv: link
							});
							//$(this).play();
						},
						swfPath: "js",
						supplied: "webmv",
						cssSelectorAncestor: "#jp_container",
						globalVolume: true,
						smoothPlayBar: true,
						keyEnabled: true
					});
				} else {
					//remove player container and add link
					var linkName = link;
					if(link.length > 60){
						linkName = ".." + linkName.substring(link.length - 51, link.length);
					}
					console.log(link.length);
					currPreviewContainer.parent().empty().append("<div style='padding-top:20px; padding-bottom:20px;'>" +
							"<a href='" + link + "'>" + linkName + "</a></div>");
					el.parent().height('110px');
				}
			}
		}),
		
		dialogAudioPreplay = $("#dialogAudioPreplay").dialog({ 
			autoOpen: false,
			resizable: false,
			modal: true,
			close: function( event, ui ) {
				$(this).find('p').empty();
			},
			open: function( event, ui ) {
				var el = $(this),
					currPreviewContainer = el.find('p'),
					mime = el.data('mime'),
					link = el.data('link');
								
				currPreviewContainer.append(BuildAudioContainer());
				
				if((mime == 'mp3') || (mime == 'mpeg')){
					$("#jquery_jplayer").jPlayer({
						ready: function () {
							$(this).jPlayer("setMedia", {
								//title: "Stirring of a fool",
								//m4a: "http://www.jplayer.org/audio/m4a/Miaow-08-Stirring-of-a-fool.m4a",
								//oga: "http://www.jplayer.org/audio/ogg/Miaow-08-Stirring-of-a-fool.ogg"
								m4a: link,
							});
							//$(this).play(0);
						},
						//play: 0,
						swfPath: "js",
						supplied: "m4a",
						wmode: "window",
						cssSelectorAncestor: "#jp_container",
						globalVolume: true,
						smoothPlayBar: true,
						keyEnabled: true
						
					});
				} else if(mime == 'ogg') {
					$("#jquery_jplayer").jPlayer({
						ready: function () {
							$(this).jPlayer("setMedia", {
								oga: link,
							});
							//$(this).play(0);
						},
						//play: 0,
						swfPath: "js",
						supplied: "oga",
						wmode: "window",
						cssSelectorAncestor: "#jp_container",
						globalVolume: true,
						smoothPlayBar: true,
						keyEnabled: true
					});
				} else {
					//remove player container and add link
					var linkName = link;
					if(link.length > 60){
						linkName = ".." + linkName.substring(link.length - 51, link.length);
					}
					console.log(link.length);
					currPreviewContainer.parent().empty().append("<div style='padding-top:20px; padding-bottom:20px;'>" +
							"<a href='" + link + "'>" + linkName + "</a></div>");
					el.parent().height('110px');
				}
			}
		}),
		
		dialogImagePreview = $("#dialogImagePreview").dialog({ 
			autoOpen: false,
			resizable: false,
			modal: true,
			close: function( event, ui ) {
				$(this).find('p').empty();
			},
			open: function( event, ui ) {
				var el = $(this),
					currPreviewContainer = el.find('p'),
					mime = el.data('mime'),
					link = el.data('link');
				
				currPreviewContainer.css('text-align','center');
				currPreviewContainer.append("<img width='590' align='center' src='"+link+"'>");
			}
		}),
		
		dialogHeap = $("div#dialogHeap").dialog({ 
			autoOpen: false,
			resizable: false,
			modal: true,
			close: function( event, ui ) {
				$(this).find('p').empty();
			}
		});
	
	$(".PreviewBtn").on("click", function(e) {
		var el = $(e.target);
		if(el.prop("tagName") == 'SPAN'){
			el = el.parents('button');
		} 

		var link = el.data("link"),
		mime = el.data("mime"),
		type = el.data("type");
		
		if(type == 'video'){
			dialogVideoPreview.data('link', link);
			dialogVideoPreview.data('mime', mime);
			dialogVideoPreview.dialog({ 
				height: 430,
				width: 520
			}).dialog("open");
		} else if (type == 'audio') {	
			dialogAudioPreplay.data('link', link);
			dialogAudioPreplay.data('mime', mime);
			dialogAudioPreplay.dialog({ 
				height: 170,
				width: 520
			}).dialog("open");
		}
		//link = currPreviewContainer.parent().data("link"),
	});
	 	
	///
	//Del button
	///
	$(".DeleteItemCell").on("click", function(e){
		console.log(e);
		if (confirm('Are you sure you want to delete this item?')) {
			var el = $(e.target);
			
			if(el.prop("tagName") == 'SPAN'){
				el = el.parents('button');
			} 
			
			var itemId= el.data("fileid"),
			plId= el.data("plid");
			
			var pV = {
                	data:{
                		file: itemId,
                		plid: plId
                	}
                };
            
            $.ajax({
        	  url: deleteFileSrc,
        	  type: "POST",
        	  data: pV,
        	  dataType: "json",
        	}).done(function(e){
        		console.log(e.status);
        		if(e['status'] == 'ok'){
        			location.reload();
        		} else if(e['status'] == 'err') {
        			console.log(e['error']);
        		}
        	}).fail(function(e){
        		console.log(e);
        	});
		}
	});
	
	
	$("span#chooseFiles").on("click", function(e){
		dialogHeap.dialog({ 
			height: 630,
			width: 800
		}).dialog("open");
	});
	
	function BuildVideoContainer()
	{
		var videoContainerStr = '<div id="jp_container" class="jp-video jp-video-270p">' +
	'<div class="jp-type-single">'+
		'<div id="jquery_jplayer" class="jp-jplayer"></div>' +
		'<div class="jp-gui">' +
			'<div class="jp-video-play">' +
				'<a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>' +
			'</div>' +
			'<div class="jp-interface">' +
				'<div class="jp-progress">' +
					'<div class="jp-seek-bar">' +
						'<div class="jp-play-bar"></div>' +
					'</div>' +
				'</div>' +
				'<div class="jp-current-time"></div>' +
				'<div class="jp-duration"></div>' +
				'<div class="jp-controls-holder">' +
					'<ul class="jp-controls">' +
						'<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>' +
						'<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>' +
						'<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>' +
						'<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>' +
						'<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>' +
						'<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>' +
					'</ul>' +
					'<div class="jp-volume-bar">' +
						'<div class="jp-volume-bar-value"></div>' +
					'</div>' +
					'<ul class="jp-toggles">' +
						'<li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a></li>' +
						'<li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a></li>' +
						'<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>' +
						'<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>' +
					'</ul>' +
				'</div>' +
				'<div class="jp-details">' +
					'<ul>' +
						'<li><span class="jp-title"></span></li>' +
					'</ul>' +
				'</div>' +
			'</div>' +
		'</div>' +
		'<div class="jp-no-solution">' +
			'<span>Update Required</span>' +
			'To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.' +
		'</div>' +
	'</div>' +
	'</div>';
		return videoContainerStr;
	}
	
	function BuildAudioContainer()
	{
		var audioContainerStr = '<div id="jquery_jplayer" class="jp-jplayer jp-audioPlayerContainer"></div>' +
	'<div id="jp_container" class="jp-audio" style="width:480px !important;">' +
		'<div class="jp-type-single">' +
			'<div class="jp-gui jp-interface">' +
				'<ul class="jp-controls">' +
					'<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>' +
					'<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>' +
					'<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>' +
					'<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>' +
					'<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>' +
					'<li><a href="javascript:;" class="jp-volume-max" ' +
				
				'style="position: absolute;' +
				'left: 330px;' +
				'top: 20px;"' +
				
			'tabindex="1" title="max volume">max volume</a></li>' +
				'</ul>' +
				'<div class="jp-progress">' +
					'<div class="jp-seek-bar">' +
						'<div class="jp-play-bar"></div>' +
					'</div>' +
				'</div>' +
				'<div class="jp-volume-bar">' +
					'<div class="jp-volume-bar-value"></div>' +
				'</div>' +
				'<div class="jp-time-holder">' +
					'<div class="jp-current-time"></div>' +
					'<div class="jp-duration"></div>' +

					'<ul class="jp-toggles">' +
						'<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>' +
						'<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>' +
					'</ul>' +
				'</div>' +
			'</div>' +
			'<div class="jp-details">' +
				'<ul>' +
					'<li><span class="jp-title"></span></li>' +
				'</ul>' +
			'</div>' +
			'<div class="jp-no-solution">' +
				'<span>Update Required</span>' +
				'To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.' +
			'</div>' +
		'</div>' +
	'</div>';
		return audioContainerStr;
	}
	
	/*=======================================================================
	 * private tree
	 * */
	
	var treePrivate = $('#treePrivate').on("select_node.jstree", function(e, data){
			var treePrivateSelectedjsTreeNode = "";
			if(data.node.type == 'file'){
				treePrivateSelectedjsTreeNode = data.node.parent;
			} else {
				treePrivateSelectedjsTreeNode = data.node.id;
			}
			RefreshDropZone(treePrivateSelectedjsTreeNode, 'treePrivate');
		}).jstree({
			"types" : {
				"folder" : {
					"icon" : "jstree-folder"
				},
				"file" : {
					"icon" : "jstree-file"
				}
			},
			'core' : {
				'data' : {
					"url" : folderSrc,
					"dataType" : "json",
					"data" : function (node) {
						var type = 'treePrivate';
						return { 
							"id" : node.id,
							"type" : type
						};
					}
				},
				"check_callback" : true
			},
			"plugins" : ["types"]
		});
	
	/*=======================================================================
	 * general tree
	 * */
	
	var treeGeneral = $('#treeGeneral').on("select_node.jstree", function(e, data){
		var treeGeneralSelectedjsTreeNode = "";
		if(data.node.type == 'file'){
			treeGeneralSelectedjsTreeNode = data.node.parent;
		} else {
			treeGeneralSelectedjsTreeNode = data.node.id;
		}
		RefreshDropZone(treeGeneralSelectedjsTreeNode, 'treeGeneral');
	}).jstree({
		"types" : {
			"folder" : {
				"icon" : "jstree-folder"
			},
			"file" : {
				"icon" : "jstree-file"
			}
		},
		'core' : {
			'data' : {
				"url" : folderSrc,
				"dataType" : "json",
				"data" : function (node) {
					var type = 'treeGeneral';
					return { 
						"id" : node.id,
						"type" : type
					};
				}
			},
			"check_callback" : true
		},
		"plugins" : ["types"]
	});
	
	RefreshDropZone(0, 'treePrivate');

	function RefreshDropZone(folderId, type){
        var pV = {
    		id: folderId,
    		type: type
        };
            
        $.ajax({
    	  url: viewSrc,
    	  type: "POST",
    	  data: pV,
    	  dataType: "json",
    	  async: false,
    	}).done(function(e){
    		if(e['status'] == 'ok'){
    			var dropzoneHeap = $("div#dropzoneHeap"),
    				data = e['data'];
    			dropzoneHeap.empty();
    			if(data == 0){
    				dropzoneHeap.append("<div style='margin:10px'>No content<div>");
    			} else {
    				for(var i = 0; i < data.length; i++){
    					if(data[i]['type'] == 'folder'){
    						dropzoneHeap.append("<div class='HeapItemFolder'" +
    								"data-path='"+data[i]['id']+"' " +
    								"data-type='"+data[i]['type']+"' >" + 
    								data[i]['text'] + "<div>");
    					} else if(data[i]['type'] == 'file'){
    						dropzoneHeap.append("<div class='HeapItem'" +
    								"data-mime='"+data[i]['mime']+"' " +
    								"data-link='"+data[i]['link']+"' " +
    								"data-type='"+data[i]['type']+"' >" + 
    								"<input type='checkbox' class='heapitemnode' " +
    								"data-id='"+data[i]['id']+"'>" + 
    								"<span>" + data[i]['text'] + "</span>" + "</div>");
    					}
    				}
    			}  
    			
    			$("div.HeapItemFolder").on('dblclick', function(e){
    				var el = $(e.target);
    				if(el.data('type') == 'folder') {
    					RefreshDropZone(el.data('path'), type);
    				} else {
    					console.log("Incorrect item type");
    				}
    			});
    			
    			$("div.HeapItem").on('dblclick', function(e){
    				var el = $(e.target);
    				if(el.data('type') == 'file') {
    					var link = el.data("link"),
	    					mime = el.data("mime"),
	    					mimeArr = mime.split("/"),
	    					type = mimeArr[0];
    				    					
    					mime = mimeArr[1];
    				
        				if(type == 'video'){
        					dialogVideoPreview.data('link', link);
        					dialogVideoPreview.data('mime', mime);
        					dialogVideoPreview.dialog({ 
        						height: 430,
        						width: 520
        					}).dialog("open");
        				} else if (type == 'audio') {	
        					dialogAudioPreplay.data('link', link);
        					dialogAudioPreplay.data('mime', mime);
        					dialogAudioPreplay.dialog({ 
        						height: 170,
        						width: 520
        					}).dialog("open");
        				} else if (type == 'image') {	
        					dialogImagePreview.data('link', link);
        					dialogImagePreview.data('mime', mime);
        					dialogImagePreview.dialog({ 
        						height: 520,
        						width: 640
        					}).dialog("open");
        				}
    				} else {
    					console.log("Incorrect item type");
    				}
    			});
    			
    			
    		} else if(e['status'] == 'err') {
    			console.log(e['error']);
    		}
    	}).fail(function(e){
    		console.log(e);
    	});
	}
	
	$("input#addFiles").on("click", function(e){
		var playlistId = playlistIdTag.val();
		$.each($(".heapitemnode:checked"), function(i, item){
			var itemId = $(item).data("id");
            var pV = {
        		id: itemId,
        		playlistId: playlistId
            };
            console.log(pV);
            
            $.ajax({
        	  url: addfilefromheapSrc,
        	  type: "POST",
        	  data: pV,
        	  dataType: "json",
        	}).done(function(e){
        		console.log(e);
        		if(e['status'] == 'ok'){
        			location.reload();
        		} else if(e['status'] == 'err') {
        			console.log(e['error']);
        		}
        	}).fail(function(e){
        		console.log(e);
        	});
		});
		
		
	});
});