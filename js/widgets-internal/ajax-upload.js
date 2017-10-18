/*jslint browser: true*/
/*global $, jQuery, qq*/

function onComplete(id, fileName, responseJSON) {
    'use strict';

    $(document).trigger('ajax-upload:complete', {
        id: id,
        fileName: fileName,
        responseJSON: responseJSON
    });
}

window.onload = function () {
    'use strict';

    if (!Array.isArray(window.widgets.AjaxUpload)) {
        throw new Error('window.widgets global var does not contain AjaxUpload widget config');
    }

    $.each(window.widgets.AjaxUpload, function (index) {
        var item = window.widgets.AjaxUpload[index],
            params = item.config.params || [],
            fu = new qq.FileUploader(item.config);

        fu.setParams(params);

        $(document).on('hashtags-input:change', function (event, data) {
            params = $.extend(
                params,
                { tags: data.tags || '' }
            );

            fu.setParams(params);
        });

        $(document).on('media-tree:change-folder', function (folderId) {
            params = $.extend(
                params,
                { folderId: folderId }
            );

            fu.setParams(params);
        });
    });
};
