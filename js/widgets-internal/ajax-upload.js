function onComplete(id, fileName, responseJSON) {
    'use strict';

    $(document).trigger('ajax-upload:complete', {
        id: id,
        fileName: fileName,
        responseJSON: responseJSON
    });
}

window.onload = function() {
    if (!Array.isArray(window.widgets.AjaxUpload)) {
        throw new Error('window.widgets global var does not contain AjaxUpload widget config');
    }

    $.each(window.widgets.AjaxUpload, function(index) {
        var item = window.widgets.AjaxUpload[index];
        var params = item.config.params || [];
        var fu = new qq.FileUploader(item.config);

        fu.setParams($.extend(
            params,
            { p: 'p' }
        ))

    })
};
