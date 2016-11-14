$(document).ready(function(e){

    var radioSet = $("#radioSet").buttonset()
        .css("width", "170px");

    $('.radioTv').on("change", function(event){
            var elVal = $(e.target).val();
            $("#tvRadioVal").val(elVal);
    });

    $("#Point_ip").on("input", function(e){
        var el = $(e.target);
            elVal = el.val();

        el.css('background-color', '#fff');

        if(!elVal.match(/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/)) {
            el.css('background-color', '#FFE8E8');
        }
    });

    $("#Point_dirname").on("input", function(e){
        var el = $(e.target);
            elVal = el.val();

        el.css('background-color', '#fff');

        if(!elVal.match(/^[a-zA-Z0-9]*$/)) {
            el.css('background-color', '#FFE8E8');
        }
    });

    $("[name='syncCheckBox']").bootstrapSwitch({
            size: 'mini',
            disabled: true,
            onText: 'true',
            offText: 'false',
            onColor: 'success',
            offColor: 'warning'
    });
});
