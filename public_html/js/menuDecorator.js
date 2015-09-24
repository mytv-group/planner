$(document).ready(function(e){
		var operationsMenu$ = $(".OperationsMenu");
		
		(function(){
			$.each($(".MainMenu li"), function(index, item){
				var menuUrl = $(item).data('url'),
					curUrl = window.location.href;;
				if(curUrl.indexOf(menuUrl) > -1){
					window.curMenuUrl = menuUrl;
				}
			});
		})();
		
		operationsMenu$
			.detach()
			.appendTo($(".MainMenu li[data-url='"+curMenuUrl+"']"))
			.slideDown()
			.removeClass("HiddenOnLoad");

		operationsMenu$.find("li").addClass("list-group-item");
});
