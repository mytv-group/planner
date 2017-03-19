$(document).ready(function(e) {
    var $showcases = $('#screen-showcases');
    $showcases.on('click', '.attach-widget', function(event) {
        event.stopPropagation();
        var windowId = $(this).data('window-id');
        $(document).trigger('choose-widget-dialog:show', {windowId: windowId});
    });

    $showcases.on('click', '.detach-widget', function(event) {
        event.stopPropagation();
        var $this = $(this);
        $this.removeClass('btn-warning detach-widget')
            .addClass('attach-widget btn-success')
            .children('.glyphicon')
            .removeClass('glyphicon-off')
            .addClass('glyphicon-paperclip');

        $this.find('.widget-name').text('');
        $this.find('.showcase-widget').val('');
    });

    $(document).on('choose-widget-dialog:widget-attached', function(event, args) {
        var $this = $('.windows-list.is-active').find('[data-window-id='+args.windowId+']');
        $this.addClass('btn-warning detach-widget')
            .removeClass('attach-widget btn-success')
            .children('.glyphicon')
            .addClass('glyphicon-off')
            .removeClass('glyphicon-paperclip');

        $this.find('.widget-name').text(args.widgetDescription);
        $this.find('.showcase-widget').val(args.widgetId).removeAttr('disabled');
    });

    $('#point-screen-id').change(function(event) {
        var active = $('.windows-list.is-active');
        var append = function ($this) {
            var screenId = $this.val();
            $('.windows-list.is-active').remove();
            var newContent = $('.windows-list[data-screen-id='+screenId+']:not(.is-active)')
                .clone().addClass('is-active').get(0).outerHTML;
            $showcases.prepend(newContent);
        }

        if (active.length === 0) {
            append($(this));
        } else if (confirm("Screen change will drop widget settings. Continue?")) {
            append($(this));
        }
    });
});
