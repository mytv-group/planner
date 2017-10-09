/*jslint browser: true*/
/*global $, jQuery*/

$(document).ready(function(e){
    $('.moveup').click(function() {
        var $el = $(this).parent();
        if(!$el.is(':first-child')) {
            $el.insertBefore($el.prev());
            $(this)[0].focus();
            sendOrder();
        }
    });

    $('.movedown').click(function() {
        var $el = $(this).parent();
        if(!$el.is(':last-child')) {
            $el.insertAfter($el.next());
            $(this)[0].focus();
            sendOrder();
        }
    });

    $('#shuffle-btn').on('click', function() {
        var array = $('#sortable').find('li');
        var shuffledArray = shuffleArray(array);

        $.each(shuffledArray, function(index) {
            $('#sortable').append($(this));
        });

        sendOrder();
    });

    function setRowNums() {
        $('.btn-num-label').each(function(index, item) {
            $(item).text(index+1);
        })
    };

    function sendOrder() {
        var playlistId = $("#playlistId").val();
        var files = [];
        $('.num').each(function(index, item) {
            files.push($(item).data('fileid'));
        });

        $('.moveup, .movedown').find('btn').addClass('disabled');

        return $.ajax({
            type: "POST",
            url: document.location.origin + '/playlists/set-file-order',
            dataType: 'json',
            data: {
                playlistId: playlistId,
                files: files
            }
        }).always(function(e) {
            $('.moveup, .movedown').find('btn').removeClass('disabled');
            setRowNums();
        });
    };

    function shuffleArray(array) {
        for (var i = array.length - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var temp = array[i];
            array[i] = array[j];
            array[j] = temp;
        }
        return array;
    }
});
