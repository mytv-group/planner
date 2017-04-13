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

    $('#widget-grid').on('click', '.btn-widget-preview', function (event) {
        var $target = $(event.currentTarget),
            buttonPosition = $target.offset(),
            widgetName = $target.data('id');

        $previewBox.removeClass('is-active');

        $.get('/widget/preview',
            { id: widgetName }
        ).done(function (result) {
            $previewBox.empty();
            $previewBox.append(result);

            $previewBox.addClass('is-active');
            var height = $previewBox.height();
            var width = $previewBox.width();

            if (height === 0) {
                height = 300;
            }

            if (width === 0) {
                width = 400;
            }

            $previewBox.css({
                top: buttonPosition.top - height / 2,
                left: buttonPosition.left - width - 10
            });
        });
    });

    $('.delete-widget').on('submit', function() {
        return confirm('Do you really want to delete widget?');
    });
});
