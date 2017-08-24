$(document).ready(function(e){
	var User_roleCont = $("#User_role");
	
	if((User_roleCont.length > 0) && (User_roleCont.val() != undefined) && (User_roleCont.val() != '')){
		var User_roleVal = User_roleCont.val();
		try {
			var User_role = $.parseJSON(User_roleVal);
			$.each(User_role, function(key, val){
				$.each(val, function(key2, val2){
					if(val2 == 1){
						$("#" + key+key2).attr('checked', 'checked');
					}
				});
			});		
		} catch(e)
		{ 
			console.log("Cant parse json, " + e.toString());
		}
		
		User_roleCont.attr('value', '');
		
		var roleArr = '';			
		var role = $("input.Role:checked");
		$.each(role, function(key, val){
			var el = $(val);		
			roleArr += el.data('page') + ":" + el.data('action') + ";";

		});					
		User_roleCont.val(roleArr);
	}
	
	$(".Role").on("click", function(e){
		User_roleCont.attr('value', '');
		
		var roleArr = '';
		//JSON.stringify(value[, replacer [, space]])
		
		var role = $("input.Role:checked");
		$.each(role, function(key, val){
			var el = $(val);		
			roleArr += el.data('page') + ":" + el.data('action') + ";";

		});
		
		console.log(roleArr);
				
		User_roleCont.val(roleArr);
	});
		
	$("input#submitUser").on("click", function(e){
		$("User_password").val();
		if($("#User_password").val() == ""){
			e.preventDefault();
			alert("Password field should not be empty!");
		}
		
		if(User_roleCont.val() == ""){
			e.preventDefault();
			alert("Roles should not be empty!");
		}
	});
});
