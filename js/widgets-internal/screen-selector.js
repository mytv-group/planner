$(document).ready(function(e) {
    $('.windows-list').on('click', '.attach-widget', function(event) {
        event.stopPropagation();
        var windowId = $(this).data('window-id');
        $(document).trigger('choose-widget-dialog:show', {windowId: windowId});
    });

    $('.windows-list').on('click', '.detach-widget', function(event) {
        event.stopPropagation();
        var $this = $(this);
        $this.removeClass('btn-warning')
            .removeClass('detach-widget')
            .addClass('attach-widget')
            .addClass('btn-success')
            .children('.glyphicon')
            .removeClass('glyphicon-off')
            .addClass('glyphicon-paperclip');

        $this.find('.widget-name').text('');
        $this.find('.showcase-widget').val('');
    });

    $(document).on('choose-widget-dialog:widget-attached', function(event, args) {
        var $this = $('.windows-list.is-active').find('[data-window-id='+args.windowId+']');
        $this.addClass('btn-warning')
            .addClass('detach-widget')
            .removeClass('attach-widget')
            .removeClass('btn-success')
            .children('.glyphicon')
            .addClass('glyphicon-off')
            .removeClass('glyphicon-paperclip');

        $this.find('.widget-name').text(args.widgetDescription);
        $this.find('.showcase-widget').val(args.widgetId);
    });

    $('#point-screen-id').change(function(event) {
        if (confirm("Screen change will drop widget settings. Continue?")) {
            var screenId = $(this).val();
            var $list = $('.windows-list.is-active');
            var $newContent = $('.windows-list[data-screen-id='+screenId+']:not(.is-active)').html();
            $list.empty().html($newContent);
        }
    });
});
