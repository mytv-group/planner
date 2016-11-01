var receiveTVBlocks = document.location.origin + '/point/receiveTVBlocks';

$(document).ready(function(e){
	/* if update receive exits tv blocks*/	
	var tvScheduleBlocks = "";
	if($("#Point_tv_schedule_blocks").length > 0){
		tvScheduleBlocks = $("#Point_tv_schedule_blocks").val();
	}
	
	if(tvScheduleBlocks.length > 0) {
	    $.ajax({
	  	  url: receiveTVBlocks,
	  	  type: "POST",
	  	  data: {
	  		tvScheduleBlocks: tvScheduleBlocks
	  	  },
	  	  dataType: "json",
	  	}).done(function(e){
	  		console.log(e.status);
	  		if(e['status'] == 'ok'){
	  			var TVshceduleDatetimeArr = e['TVshceduleDatetimeArr'];

	  			var periodContainer = $("#periodContainer");
	  			
	  			for(var ii = 0; ii < TVshceduleDatetimeArr.length; ii++){
		  			periodContainer.append("<p>" +
		  					"<input name='Point[TVshceduleFromDatetime][]' type='text' size='15' " +
		  						"class='form-control TVshceduleFromDatetime' title='From datetime' value='" + TVshceduleDatetimeArr[ii][0] + "'/> " +
		  					"<input name='Point[TVshceduleToDatetime][]' type='text' size='15' " +
		  					"	class='form-control TVshceduleToDatetime' title='To datetime' value='" + TVshceduleDatetimeArr[ii][1] + "'/> " +
		  					"<button class='btn btn-danger RemoveTVonOff'>" +
		  			  			" x " +
		  			  		"</button>" +
		  					"</p>");
	  			}
	  			
	  			$(".TVshceduleFromDatetime").datetimepicker({
	  				format:'Y-m-d H:i:s'
	  			});
	  			
	  			$(".TVshceduleToDatetime").datetimepicker({
	  				format:'Y-m-d H:i:s'
	  			});
	  			
	  			$(".RemoveTVonOff").on("click", function(e){
  					e.preventDefault();
  					e.stopPropagation();
	  				if (confirm("Delete this TV broadcasting period?")) {
	  					$(e.target).parent('p').remove();
	  				}
	  			});
	  			
	  		} else if(e['status'] == 'err') {
	  			console.log(e['error']);
	  		}
	  	}).fail(function(e){
	  		console.log(e);
	  	});
	}
	
	$("#addTVperiod").on("click", function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#periodContainer").append("<p>" +
				"<input name='Point[TVshceduleFromDatetime][]' type='text' size='15' class='form-control TVshceduleFromDatetime' title='From datetime'/> " +
				"<input name='Point[TVshceduleToDatetime][]' type='text' size='15' class='form-control TVshceduleToDatetime' title='To datetime'/> " +
				"<button class='btn btn-danger RemoveTVonOff'>" +
		  			" x " +
		  		"</button>" +
				"</p>");
		
		$(".TVshceduleFromDatetime").datetimepicker({
			format:'Y-m-d H:i:s'
		});
		
		$(".TVshceduleToDatetime").datetimepicker({
			format:'Y-m-d H:i:s'
		});
		
		$(".RemoveTVonOff").on("click", function(e){
				e.preventDefault();
				e.stopPropagation();
			if (confirm("Delete this TV broadcasting period?")) {
				$(e.target).parent('p').remove();
			}
		});
  			
	});
	
	
});