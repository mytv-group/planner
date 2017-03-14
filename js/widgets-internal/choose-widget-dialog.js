$(document).ready(function() {
    var windowId = null;
    var $dialog = $('#choose-widget-dialog');
    $(document).on('choose-widget-dialog:show', function(event, args) {
        $dialog.modal('show');
        windowId = args.windowId || null;
    });

    var $rows = $('#choose-widget-dialog .row-data');
    var $attachWidgetBtn = $('#modalbtn-attach-widget');
    $rows.click(function() {
        $rows.removeClass('is-selected');
        $(this).addClass('is-selected');
        $attachWidgetBtn.removeAttr('disabled');
    });

    $attachWidgetBtn.click(function(event) {
        var $selected = $($rows.filter('.is-selected').get(0));
        var id = $selected.data('id') || null;

        if ((windowId !== null) && (id !== null)) {
            $(document).trigger('choose-widget-dialog:widget-attached', {
                windowId: windowId,
                widgetId: id
            });
        }

        $dialog.modal('hide');
    });
});
