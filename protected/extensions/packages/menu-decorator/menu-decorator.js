$(document).ready(function(e){
    var operationsMenu$ = $(".operations-menu");

    (function(){
        $.each($(".main-menu li"), function(index, item){
            var menuUrl = $(item).data('url'),
                curUrl = window.location.href;;
            if(curUrl.indexOf(menuUrl) > -1){
                window.curMenuUrl = menuUrl;
            }
        });
    })();

    operationsMenu$
        .detach()
        .appendTo($(".main-menu li[data-url='"+curMenuUrl+"']"))
        .slideDown()
        .removeClass("hidden-on-load");
    operationsMenu$.find("li").addClass("list-group-item");
});
