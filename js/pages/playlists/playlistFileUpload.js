$(document).ready(function(e){
    var url = document.location.origin + '/uploader/',
        moveFileSrc = document.location.origin + '/playlists/upload/',
        viewSrc = document.location.origin + 'heap/view/',
        playlistIdTag = $("#playlistId");

    $("#fileupload").fileupload({
        url: url,
        dataType: 'json',
        //acceptFileTypes: /(\.|\/)(gif|jpe?g|png|mov|mkv|avi|swf|mp4|mp3')$/i,
        //
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png|webm|ogg|wav|avi|mov|mkv|mp3|mp4|swf)$/i,
        maxFileSize: 2500000000000,
        dropZone: $('#dropzone'),
        done: function (e, data) {
            var playlistId = playlistIdTag.val();
            var i = 0;
            var dfdArr = [];

            $.each(data.result.files, function (index, file) {
                var pV = {
                    data:{
                        file: file,
                        playlistId: playlistId
                    }
                };

                dfdArr.push($.ajax({
                  url: moveFileSrc,
                  type: "POST",
                  data: pV,
                  dataType: "json",
                }).done(function(e){
                    console.log(e.status);
                    i++;
                    if(e['status'] == 'ok'){
                        $('<p/>').text(file.name).appendTo('#files');

                    } else if(e['status'] == 'err') {
                        $('<p/>').text(e["error"]).css("color", "darkred").appendTo('#files');
                        $('#progress .progress-bar').css(
                            'width',
                            0 + '%'
                        );
                    }
                }).fail(function(e){
                    console.log(e);
                    $('<p/>').text(e["responseText"]).css("color", "darkred").appendTo('#files');
                    $('#progress .progress-bar').css(
                        'width',
                        0 + '%'
                    );
                }));
            });

            $.when.apply($, dfdArr).then(function() {
                location.reload();
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    $(document).bind('dragover', function (e) {
        var dropZone = $('#dropzone'),
            timeout = window.dropZoneTimeout;
        if (!timeout) {
            dropZone.addClass('in');
        } else {
            clearTimeout(timeout);
        }
        var found = false,
            node = e.target;
        do {
            if (node === dropZone[0]) {
                found = true;
                break;
            }
            node = node.parentNode;
        } while (node != null);
        if (found) {
            dropZone.addClass('hover');
        } else {
            dropZone.removeClass('hover');
        }
        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZone.removeClass('in hover');
        }, 100);
    });
});
