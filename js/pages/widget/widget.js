/*jslint browser: true*/
/*global $, jQuery*/

$(document).ready(function () {
    'use strict';

    var $previewBox = $('<div></div>')
        .attr('id', 'preview-box')
        .appendTo('body')
        .click(function () {
            $(this).removeClass('is-active');
        });

    $('.btn-widget-preview').click(function (event) {
        var $target = $(event.currentTarget),
            buttonPosition = $target.offset(),
            widgetName = $target.data('id');

        $previewBox.removeClass('is-active');

        $.get('/widget/preview',
            { id: widgetName }
        ).done(function (result) {
            $previewBox.empty();
            $previewBox.append(result);
            $previewBox.css({
                top: buttonPosition.top - $previewBox.height() / 2,
                left: buttonPosition.left - $previewBox.width() - 10
            });
            $previewBox.addClass('is-active');
        });
    });
});
