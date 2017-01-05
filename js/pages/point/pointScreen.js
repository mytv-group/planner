$(document).ready(function(e){

    var pointId = $("#pointId").data('value'),
        windowsList = $("#windowsList"),
        CM = new ChannelManager(pointId);

    $('#Point_screen_id').on("click", function(e){
        var target$ = $(e.target);
        if(target$.prop("tagName") == 'OPTION') {
            if (confirm("Channging screen will clear all attached playlists")) {
                var screenId = target$.val();
                var attachScreenToPoint = document.location.origin + '/point/attachScreenToPoint';

                $.ajax({
                    url: attachScreenToPoint,
                    type: "POST",
                    data: {
                        pointId: pointId,
                        screenId: screenId
                    },
                    dataType: "json",
                }).done(function(channels){
                    var channelList = $("#windowsList");
                    channelList.empty();
                    for(var ii = 0; ii < channels.length; ii++) {

                        var newChannelId = channels[ii]['id'];
                        var newChannelInternalId = channels[ii]['internalId'];
                        var windowName = channels[ii]['windowName'];

                        var curChannelsContainer = $("<div></div>")
                            .appendTo(channelList)
                            .addClass('ChannelsContainer btn-toolbar')
                            .attr("data-channelid", newChannelId)
                            .attr("role", 'toolbar')
                            .attr("aria-label",'');

                         var btnGroup = $("<div></div>")
                            .appendTo(curChannelsContainer)
                            .addClass('btn-group')
                            .attr("role",'group')
                            .attr("aria-label", '');

                         $("<button></button>")
                            .appendTo(btnGroup)
                            .addClass('btn btn-default ChannelButt')
                            .attr('disabled', 'disabled')
                            .prepend("Screen " + windowName + " ");

                         var curButt = $("<button></button>")
                            .appendTo(btnGroup)
                            .addClass('attach-widget btn btn-success')
                            .attr("data-channelid", newChannelId)
                            .attr("type", 'button');

                        $("<span></span>")
                            .appendTo(curButt)
                            .addClass('glyphicon glyphicon-paperclip');

                        curButt.append(" Attach widget");
                    }
                }).fail(function(msg){
                    console.warn(msg);
                });
            }
        }
    });
});
