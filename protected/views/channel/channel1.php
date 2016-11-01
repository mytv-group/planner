<?

    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/utils.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/channel1.js', CClientScript::POS_END);

    $startHour = 6;
    $endHour = 24;
    $hoursInDay = $endHour - $startHour;
    $daysInWeek = 7;
    $weekDays = array("Sun", "Mon", "Tue", "Wen", "Thu", "Fri", "Sat");

?>

<h1>View Channel #<?php echo $model->id; ?></h1>

<!--<div id="zTime" style="display: none;">
    <div class="hour_grid">
        <div class="names">
            <div class="start"></div>
            <div class="end"></div>
        </div>
        <table class="lines"><tr><?for($i=0;$i<60;$i++){echo "<td>&nbsp;</td>";}?></tr></table>
    </div>

    <div id="hourCal"></div>
</div>-->

<div id="zDay" style="display: none;">

    <div id="hourFrame">
        <div id="hourCal">
            <table class="lines"><tr><?for($i=0;$i<60*18;$i++){echo "<td><div>&nbsp;</td>";}?></tr></table>
            <div id="timeLine"></div>
        </div>
    </div>
    <div class="days">
        <ul class="hours_ul">
            <?for($i=0;$i<$hoursInDay;$i++){echo "<li data-selected=0 data-hour=".$i." class='hour ui-widget-content'>1</li>";}?>
        </ul>
    </div>
    <div class="names"><div><?for($i=0;$i<($hoursInDay+1);$i++){echo "<div class='hour'><span>".($i+$startHour)."</span>:<span>00</span></div>";}?></div></div>
    <div class="noUISlider" id="noUISlider" style="width: 862px;">
        <!--<div class="popupBtn">forward</div>-->
    </div>
</div>

<div id="formBox">
    <div data-hour="" class="form">

    </div>
</div>

<div id="saveBtn">Save changes</div>

<div id="weekCal">

    <div id="dayPopup" style="display: none;">
        <div class="head">
            <div class="close">close</div>
        </div>
        <div class="body">
            <div class="line">

            </div>
        </div>
    </div>

    <div id="vTimeLine">
        <? for($i=$startHour;$i<$startHour+$hoursInDay;$i++): ?>
            <!--<div><?=$i." : 00";?></div>-->
            <div class="hour">
                <div class="minutes">
                    <? for($j=0;$j<20;$j++): ?>
                        <div <? if ($j%10==0){ echo 'class="bold"'; }?>><?=$i;?>:<?=sprintf( '%02d', $j*3 );?></div>
                    <? endfor; ?>
                </div>
            </div>
        <? endfor; ?>
    </div>

    <div id="elPlace">
        <? for($j=0;$j<7;$j++): ?>
            <div data-day="<?=$j?>" class="day day_<?=$j?>">
                <div class="fantomEl"></div>
            </div>
        <? endfor; ?>
    </div>

    <table class="CSSTableGenerator" id="zWeek">
        <thead>
        <tr>
            <? for($j=0;$j<7;$j++): ?>
                <th class="th_<?=$j?>"><?=$weekDays[$j];?></th>
            <? endfor; ?>
        </tr>
        </thead>
        <tbody id="tbody">
        <? for($i=$startHour;$i<$startHour+$hoursInDay;$i++): ?>
            <tr>
                <? for($j=0;$j<7;$j++): ?>
                    <td data-day="<?=$j?>" data-hour="<?=$i?>" id="<?=$j."-".$i;?>"><div></div></td>
                <? endfor; ?>
            </tr>
        <?endfor;?>
        </tbody>
    </table>
    <!--<a style="cursorpointer;text-decoration:none;font-weight:bold;padding-top:10px;float:left;" onclick="javascript:$('tbody').show()">SHOW</a>-->
</div>

<script>
    if (!window.app){
        app = {
            params: {
                channel_id: <?=$_GET['id'];?>,
                startHour: <?=$startHour;?>,
                sH: <?=$startHour;?>,
                endHour: <?=$endHour;?>,
                eH: <?=$endHour;?>,
                hoursInDay: <?=($endHour - $startHour);?>,
                weekDays: JSON.parse('<?=CJSON::encode($weekDays);?>'),
                px_h: 1/360,
                px_min: 60/360,
                px_sec: 60*60/360,
                min_px: 360/60,
                sec_px: 360/60/60,
                h_px: 360
            }
        };
        app.p = app.params;
    }
    var blocks = JSON.parse('<?=CJSON::encode($model->items)?>');
</script>

<style>

    #saveBtn {
        margin: 10px 48px;
    }

    #weekCal {
        display: inline-block;
        position: relative;
    }
    #weekCal .head{

    }
    #weekCal .head .close{
        float: right;
    }

    #dayPopup {
        background: none repeat scroll 0 0 rgba(178, 178, 178, 0.93);
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        z-index: 999;
    }
    #dayPopup .head {
        height: 32px;
        padding: 7px;
        background: #333;
    }
    #dayPopup .body {

    }
    #dayPopup .body .line {
        position: relative;
        height: 600px;
        width: 218px;
        background: #f7f7f7;
        border: 2px solid #ccc;
        border-top: none;
    }
    #dayPopup .body .line p.el {
        background: #b81900;
        border-radius: 3px;
        left: 1px;
        position: absolute;
        right: 1px;
    }


    #vTimeLine {
        position: absolute;
        z-index: 998;
        top: 16px;
    }
    #vTimeLine div.hour {
        font-weight: bold;
        line-height: 47px;
        width: 50px;
    }

    #vTimeLine div.hour div.minutes {

    }

    #vTimeLine div.hour div.minutes > div {
        height: 18px;
        text-align: center;
        font-weight: normal;
    }
    #vTimeLine div.hour div.minutes > div.bold {
        font-weight: bold;
    }

    #zWeek {
        width: auto;
        margin-left: 50px;
    }
    #zWeek tr:nth-child(2n) {
        background: #bce8f1;
    }
    #zWeek tr:nth-child(2n+1) {
        background: #f7f7f7;
    }
    #zWeek tr td, #zWeek tr th {
        width: 100px;
        height: 30px;
    }
    #zWeek tr td {
        height: 360px;
        border-width: 0 1px;
    }
    /*#zWeek tr td > div {
        height: 360px;
        position: relative;
        margin: 0;
        padding: 0;
    }
    #zWeek tr td div p.el {
        background: #b81900;
        border-radius: 3px;
        left: 1px;
        position: absolute;
        right: 1px;
        margin: 0;
    }*/

    #elPlace {
        position: absolute;
        top: 40px;
        right: 0;
        bottom: 0;
        left: 51px;
        opacity: 0.5;
    }
    #elPlace .day {
        width: 114px;
        height: 100%;
        float: left;
        margin-right: 2px;
        position: relative;
        background: url(../images/bbg.png);
    }
    #elPlace .day .fantomEl {
        background: #333;
        position: absolute;
        left: 1px;
        right: 1px;
        z-index: 98;
        opacity: 0.5;
    }
    #elPlace .day .el {
        background: #b81900;
        border-radius: 3px;
        left: 1px;
        position: absolute;
        right: 1px;
        margin: 0;
        z-index: 99;
        opacity: 0.6;
    }
    #elPlace .day .el .ui-resizable-handle{
        background: #f89406;
    }
    #elPlace .day .el .ui-resizable-n{
        top: 4px;
    }
    #elPlace .day .el .ui-resizable-s{
        bottom: 4px;
    }


    #noUISlider .noUi-horizontal .noUi-handle {
        height: 28px;
        left: 0;
        top: -52px;
        width: 50px;
    }

    .noUi-handle:before, .noUi-handle:after {
        background: none repeat scroll 0 0 #E85117;
        content: "";
        display: block;
        height: 69px;
        left: 16px;
        position: absolute;
        top: -128px;
        width: 0px;
    }

    .noUi-handle {
        background: none repeat scroll 0 0 #E85117;
        content: "";
        display: block;
        height: 29px;
        left: 24px;
        position: absolute;
        top: 0;
        width: 48px;
    }

    #zDay {
        width: 900px;
    }

    #zDay #hourFrame {
        width: 100%;
        height: 92px;
        background: #a9b6d0;
        margin: 5px 0;
        overflow: hidden;
        display: block;
        position: relative;
    }
    #zDay #hourFrame #hourCal  {
        position: absolute;
        height: 80px;
        top: 6px;

    }
    #zDay #hourFrame #hourCal .lines {

    }
    #zDay #hourFrame #hourCal .lines tr {
        height: 15px;
    }
    #zDay #hourFrame #hourCal  .lines tr td {
        border-top: 0;
        border-bottom: 0;
        border-right: 1px solid #333;
        border-left: 1px solid #333;
        margin: 0;
        padding: 0;
    }

     #zDay #hourFrame #hourCal #timeLine {
         display: block;
         height: 65px;
         width: 100%;
         position: relative;
         background: #fbfbfb;
     }

     #zDay #hourFrame #hourCal #timeLine .newEl {
         background: #FBC2C4;
         width: 45px;
         height: 65px;
         display: block;
         position: absolute;
         top: 0;
         z-index: 999;
     }

     #zDay #hourFrame #hourCal #timeLine .newEl .ui-resizable-handle {
        background: #333;
     }
     #zDay #hourFrame #hourCal #timeLine .newEl .ui-resizable-w {
        left: 10px;
     }
     #zDay #hourFrame #hourCal #timeLine .newEl .ui-resizable-e {
        right: 10px
     }


     #zDay #hourFrame #hourCal .fantom {
         background: #333;
         position: absolute;
         top: 0;
         bottom: 0;
         width: 45px;
         z-index: 99;
         opacity: 0.5;
     }

    #zTime .hour_grid .lines tr td:first-child {
        border-left: 2px solid #333;
    }
    #zTime .hour_grid .lines tr td:last-child {
        border-right: 2px solid #333;
    }




    #formBox {
        margin: 20px 0;
        border: 1px solid #333;
    }






    #zTime .hour_grid {
        position: relative;
    }
    #zTime .hour_grid .names {
        border: 0;
        height: 20px;
    }
    #zTime .hour_grid .names div{
        font: bold 11px Verdana ;
    }
    #zTime .hour_grid .names .start{
        float: left;
        margin-left: -15px;
    }
    #zTime .hour_grid .names .end{
        float: right;
        margin-right: -15px;
    }
    #zTime .hour_grid .lines {

    }
    #zTime .hour_grid .lines tr {

    }
    #zTime .hour_grid .lines tr td {
        border-top: 0;
        border-bottom: 0;
        border-right: 1px solid #333;
        border-left: 1px solid #333;
        margin: 0;
        padding: 0;
    }
    #zTime .hour_grid .lines tr td:first-child {
        border-left: 2px solid #333;
    }
    #zTime .hour_grid .lines tr td:last-child {
        border-right: 2px solid #333;
    }

    #formBox {

    }
    #formBox .form {

    }
    #formBox .form input {
        margin: 5px;
        text-align: center;
        width: 30px;
    }




    #zDay {
        margin: 0;
        padding: 5px 0 5px;
    }

    #zDay > div {
        display: table;
        width: 100%;
    }

    #zDay div div {
        display: table-row;
    }

    #zDay div.days {

    }
    #zDay div.days ul.hours_ul {
        margin: 0;
        padding: 0;
        display: table-row;
    }

    #zDay div.days .hours_ul li {
        height: 50px;
        display: table-cell;
        border: 1px solid #fbfbfb;
        background: #a9b6d0;
        color: #333;
        text-indent: -9999px;
        border: 1px solid #333333;
    }
    #zDay div.days .hours_ul li.ui-selected {
        background: #006600;
    }

    #zDay div.days .hour:first-child {
        /*border-left: 2px solid #fbfbfb;*/
    }
    #zDay div.days .hour:last-child {
        /*border-right: 2px solid #fbfbfb;*/
    }
    #zDay div.days .hour.selected {
        border: 1px solid #fff;
    }

    #zDay div.names {

    }
    #zDay div.names .hour {
        font: bold 11px Verdana;
        text-align: center;
        display: table-cell;
        border: 0;
    }

    #zDay div.noUISlider {
        height: 30px;
        background: #FBC2C4;
        position: relative;
    }
    #zDay div.noUISlider div.popupBtn {
        display: none;
        height: 30px;
        font: bold 12px Verdana;
        position: absolute;
        top: 0;
        line-height: 30px;
        text-decoration: underline;
    }

</style>
