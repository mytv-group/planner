/*jslint browser: true*/
/*global $, jQuery*/

$(document).ready(function () {
    'use strict';

    var origin = document.location.origin;

    var url =  origin + '/uploader/';
    var moveFileSrc = origin + '/playlists/upload/';
    var deleteFileSrc = origin + '/playlists/deletefilefrompl/';
    var addfilefromheapSrc = origin + '/playlists/addfilefromheap/';
    var folderSrc = origin + '/admin/getfoldercontent/';
    var viewSrc = origin + '/admin/view/';
    var Playlist_name = $("#Playlist_name");
    var playlistIdTag = $("#playlistId");
    var filesBlock = $("#filesBlock");
    var selectedjsTreeNode = 0;

    $(".datepicker").datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });

    $(".timepicker").datetimepicker({
        format: 'H:i:s',
        datepicker: false
    });

    var typeBlock = $(".type-control");
    var type = $(".type-control:checked").val();
    var streamUrlBlock = $('#stream-url-block');
    var fileManager = $('#file-manager');
    var everyBlock = $("#everyBlock");
    var periodBlock = $("#periodBlock");

    var hideFunctionality = function(type, animationTime) {
        if (type == 1) {
            everyBlock.slideUp(animationTime);
            streamUrlBlock.slideUp(animationTime);
            fileManager.slideDown(animationTime);
        } else if(type == 2) {
            everyBlock.slideDown(animationTime);
            streamUrlBlock.slideUp(animationTime);
            fileManager.slideDown(animationTime);
        } else if(type == 3) {
            everyBlock.slideUp(animationTime);
            streamUrlBlock.slideDown(animationTime);
            fileManager.slideUp(animationTime);
        }
    }

    hideFunctionality(type, 1);

    typeBlock.on('change', function(e) {
        hideFunctionality($(e.target).val(), 200);
    });

    $('.delete-playlist').on('submit', function() {
        return confirm('Do you really want to delete playlist?');
    });

});
