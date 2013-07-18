$(document).ready(function() {

    $('#menu > li').hover(function() {
        $(this).addClass("hover2");
    }, function() {
        $(this).removeClass("hover2");
    });
    $('.filter_type').hover(function() {
        $(this).addClass("hover2");
    }, function() {
        $(this).removeClass("hover2");
    });
    $('.filter_type a').live('click', function(e) {
        e.stopPropagation();
        return false;
    });

    var top_pos = 80;
    $(window).scroll(function() {
        if ($(window).scrollTop() > top_pos) {
            $("#left_menu").css("position", "fixed");
            $("#left_menu").css("top", "40px");

        } else {
            $("#left_menu").css("position", "relative");
            $("#left_menu").css("top", "0px");
        }
    });
});

function callMe() {
    var featureValues = [];
    $.each($('#filter input[type=checkbox]:checked'),function(k,elem){
         featureValues.push($(elem).attr('value'));
    });
   
    return {
        featureValues : featureValues.join(',')
    };
}

