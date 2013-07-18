$(document).ready(function() {
    // var largeUrl = .attr('large');
    // $("#product_photos_container img.selected").attr('src',largeUrl);
    $("#photos_switcher img").live('click', function() {
        $("#product_photos_container a").removeClass('aselected');
        $("#product_photos_container a").eq(Number($(this).attr('ind'))).addClass('aselected');
    });
    $('#photos_switcher img:first-child').click();

    $('#product_photos_container a').fancybox({
        'titlePosition' : 'outside',
        'overlayColor' : '#000',
        'overlayOpacity' : 0.9
    });
});

