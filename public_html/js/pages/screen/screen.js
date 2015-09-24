$(document).ready(function(e){
	var snaptarget$ = $("#snaptarget");
	
	if(snaptarget$.length > 0) {
		var screenWidth = parseInt($("#Screen_width").val()),
		screenHeight = parseInt($("#Screen_height").val());
	
		var screenBlockManager = new ScreenBlock();
		screenBlockManager.init(snaptarget$, screenWidth, screenHeight);
		screenBlockManager.establish($(".Draggable"));	
		
		$("#addDraggableBlock").on("click", function(e){
			var drgName = $("#screenBlockName").val();
			screenBlockManager.addBlock(drgName);
		});
		
		$("#changeDisplaySizeButt").on("click", function(e){
			var this$ = $(this);
			
			if(this$.val() == "Change display size"){
				$(".Draggable").remove();
				$("#Screen_width").removeProp("readonly");
				$("#Screen_height").removeProp("readonly");
				$("#addDraggableBlock").attr("disabled", "disabled");
				this$.val("Apply");
				this$.removeClass("btn-danger");
				this$.addClass("btn-info");
			} else {
				$("#Screen_width").prop("readonly", true);
				$("#Screen_height").prop("readonly", true);
				$("#addDraggableBlock").removeAttr("disabled");
				this$.val("Change display size");
				this$.removeClass("btn-info");
				this$.addClass("btn-danger");
				screenWidth = parseInt($("#Screen_width").val()),
				screenHeight = parseInt($("#Screen_height").val());
				screenBlockManager.init(snaptarget$, screenWidth, screenHeight);
			}
		});
		
		$("#screen-form input[type=submit]").on("click", function(e){
			e.preventDefault();
			e.stopPropagation();
			var screenForm = $("#screen-form");
			$.each($(".Draggable input"), function(ii, item){
				screenForm.append($(item));
			});
			screenForm.submit();
		});
	}
	
});