$(document).ready(function(e) {
    $('.attach-widget').click(function(event) {
        var windowId = $(this).data('window-id');
        $(document).trigger('choose-widget-dialog:show', {windowId: windowId});
    });

    $(document).on('choose-widget-dialog:widget-attached', function(event, args) {
        console.log(args);
    });
});
