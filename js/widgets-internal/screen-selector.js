$(document).ready(function(e) {
    var $showcases = $('.screen-showcases');
    $showcases.on('click', '.attach-widget', function(event) {
        event.stopPropagation();
        var windowId = $(this).data('window-id');
        var pointId = $(this).data('point-id');
        $(document).trigger('choose-widget-dialog:show', {
            windowId: windowId,
            pointId: pointId
        });
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
        var $window;
        if ((args.pointId !== null) && (args.pointId !== '')) {
            $window = $('.windows-list.is-active[data-point-id='+args.pointId+']').find('[data-window-id='+args.windowId+']');
        } else {
            $window = $('.windows-list.is-active').find('[data-window-id='+args.windowId+']');
        }
        $window.addClass('btn-warning detach-widget')
            .removeClass('attach-widget btn-success')
            .children('.glyphicon')
            .addClass('glyphicon-off')
            .removeClass('glyphicon-paperclip');

        $window.find('.widget-name').text(args.widgetDescription);
        $window.find('.showcase-widget').val(args.widgetId).removeAttr('disabled');
    });

    $('.point-screen').change(function(event) {
        var pointId = $(this).data('point-id');
        var $active;

        if (pointId !== undefined) {
           $active = $('.windows-list.is-active[data-point-id='+pointId+']');
        } else {
           $active = $('.windows-list.is-active');
        }

        var append = function ($this) {
            var screenId = $this.val();
            $active.remove();

            var newContent = $('.windows-list[data-point-id='+pointId+'][data-screen-id='+screenId+']:not(.is-active)')
                .clone().addClass('is-active').get(0).outerHTML;
            $showcases.filter('[data-point-id='+pointId+']').prepend(newContent);
        }

        if ($active.length === 0) {
            append($(this));
        } else if (confirm("Screen change will drop widget settings. Continue?")) {
            append($(this));
        }
    });
});
