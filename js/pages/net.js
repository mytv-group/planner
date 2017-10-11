$(document).ready(function() {
    $("#Net_attachedPoints").mousedown(function(e){
        e.preventDefault();

        var select = this;
        var scroll = select.scrollTop;

        e.target.selected = !e.target.selected;

        setTimeout(function(){select.scrollTop = scroll}, 0);

        $(select).focus();
    }).mousemove(function(e){e.preventDefault()});

    $('.delete-net').on('submit', function() {
        return confirm('Do you really want to delete network?');
    });
});
