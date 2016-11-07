$(document).ready(function(e){
    var widgetAttachButtons = $(".attach-widget");
    var widgetDetachButtons = $(".detach-widget");
    var WM = new WidgetManager();

    var widgetDialog = $("#widget-dialog").dialog({
        autoOpen: false,
        width: 900,
        minHeight: 300,
        maxHeight: 600,
        show: "fade",
        modal: true,
        buttons: {
            Add: function() {
                var selectedWidget = $(".selected-widget:checked"),
                    selectedWidgetId = selectedWidget.data('widgetid'),
                    selectedWidgetName = selectedWidget.data('widgetname'),
                    channelId = widgetDialog.data("channelid"),
                    curWidgetButt = $(".attach-widget").filter(function(){
                        return $(this).data("channelid") == channelId;
                    });

                WM.attachWidgetReq(channelId, selectedWidgetId).done(function(msg){
                    curWidgetButt
                        .empty()
                        .removeClass('attach-widget')
                        .removeClass('btn-success')
                        .addClass('btn-warning')
                        .addClass('detach-widget')
                        .html(' Detach widget ' + selectedWidgetName)
                        .prepend(
                            $("<span></span>")
                                .addClass("glyphicon glyphicon-off")
                    )
                    .off("click")
                    .on("click", function(e){
                        detachWidget.call(this, e);
                    });
                });

                $(this).dialog("close");
            }
        }
    });

    $('#windowsList').on("click", function(e){
        if ($(e.target).hasClass('attach-widget')) {
            attachWidget.call($(e.target), e);
        }
    });

    $('#windowsList').on("click", function(e){
        if ($(e.target).hasClass('detach-widget')) {
            detachWidget.call($(e.target), e);
        }
    });

    function attachWidget(e){
        e.preventDefault();
        e.stopPropagation();

        var $this = $(this);
        var channelid = $this.data("channelid");

        widgetDialog.data("channelid", channelid);
        widgetDialog.dialog('open');
    };

    function detachWidget(e){
        e.preventDefault();
        e.stopPropagation();

        var $this = $(this),
        channelId = $this.data("channelid");

        if(confirm("Detach widget from channel?")){
            WM.detachWidgetReq(channelId).done(function(e){
                if(e.status == 'ok'){
                    $this
                        .empty()
                        .addClass('attach-widget')
                        .addClass('btn-success')
                        .removeClass('btn-warning')
                        .removeClass('detach-widget')
                        .html(' Attach widget')
                        .prepend(
                            $("<span></span>")
                                .addClass("glyphicon glyphicon-paperclip")
                    )
                    .off("click")
                    .on("click", function(e){
                        attachWidget.call(this, e);
                    });

                } else if(e.status == 'err'){
                    alert(e.error);
                }
            });
        }
    };

});
