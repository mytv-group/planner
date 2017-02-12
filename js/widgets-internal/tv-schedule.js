$(document).ready(function(e) {

    function bindControls() {
        $(".tv-schedule-datetime").datetimepicker({
            format:'Y-m-d H:i:s'
        });

        $(".remove-tv-block")
          .off('click')
          .click(function(e){
              if (confirm("Delete this TV broadcasting period?")) {
                  $(e.target).parent('div').remove();
              }
          });
    }

    $("#add-tv-period").click(function(e){
        var $tvSchedule = $("#js-tv-schedule").clone();
        $('#tv-schedule-grid').append($tvSchedule.css({display: 'block'}));

        bindControls();
    });
});
