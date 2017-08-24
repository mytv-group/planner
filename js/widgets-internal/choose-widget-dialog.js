$(document).ready(function() {
    var windowId = null;
    var pointId = null;
    var $dialog = $('#choose-widget-dialog');
    $(document).on('choose-widget-dialog:show', function(event, args) {
        $dialog.modal('show');
        windowId = args.windowId || null;
        pointId = args.pointId || null;
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
        var description = $selected.children('.modal-widget-description').text().trim() || null;

        if ((windowId !== null) && (id !== null)) {
            $(document).trigger('choose-widget-dialog:widget-attached', {
                windowId: windowId,
                widgetDescription: description,
                widgetId: id,
                pointId: pointId
            });
        }

        $dialog.modal('hide');
        $rows.removeClass('is-selected');
        $attachWidgetBtn.attr('disabled', 'disabled');
    });
});
