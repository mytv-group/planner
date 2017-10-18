/*jslint browser: true*/
/*global $ */

$(document).ready(function () {
    'use strict';

    $('.hashtags-input').on('input', function () {
        $(document).trigger('hashtags-input:change', {
            tags: $(this).val().trim()
        });
    });
});
