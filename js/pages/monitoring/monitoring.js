$(document).ready(function(e){

  $("[name='syncCheckBox']").bootstrapSwitch({
      size: 'mini',
      disabled: true,
      onText: 'true',
      offText: 'false',
      onColor: 'success',
      offColor: 'warning'
  });

  $(".ShowPointScreenBut").on("click", function(e){
      var this$ = $(this),
        pointId = this$.data("id"),
        pointIp = this$.data("ip");

    if((this$.data('clicked') == 'false') ||
        (this$.data('clicked') == undefined)){
      this$.data('clicked', 'true');
      var curScreenBox$ = $("<div></div>")
        .addClass('ScreenShotBox ScreenShotBoxBgLoading')
        .css({
          'top': e.pageY - 120,
          'left': e.pageX - 360
        })
        .append(
          $("<img/>")
            .addClass('ScreenShotImg')
        )
        .appendTo('body')
        .fadeIn();

      $.ajax({
            url: 'http://' + window.location.host + "/monitoring/ajaxGetPointScreen",
            type: "POST",
            data: {
              pointId: pointId,
              pointIp: pointIp
            },
            dataType: "json",
      }).done(function(answ){
        console.log(answ);
        if((answ != null) && (answ[0] == 'ok')){
          curScreenBox$.find('.ScreenShotImg').attr('src', answ[1])
        } else {
          curScreenBox$
            .removeClass('ScreenShotBoxBgLoading')
            .addClass('ScreenShotBoxBgUnavaliable');
        }
      }).fail(function(msg){
        curScreenBox$
          .removeClass('ScreenShotBoxBgLoading')
          .addClass('ScreenShotBoxBgUnavaliable');
      });
    } else {
      this$.data('clicked', 'false');
      $(".ScreenShotBox").remove();
    }

  });

  $('.delete-point').on('submit', function() {
      return confirm('Do you really want to delete point?');
  });

  $('.delete-playlist').on('submit', function() {
      return confirm('Do you really want to delete playlist?');
  });
});
