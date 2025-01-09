(function() {
    "use strict";
    $("#sidebar_overlay").hide();
    if ($('[data-toggle="tooltip"]').length > 0) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    if ($('.select').length > 0) {
        $('.select').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });
    }
    if ($('.datetimepicker').length > 0) {
        $('.datetimepicker').datetimepicker({
            format: 'DD/MM/YYYY',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
        });
    }
    $(window).on('load', function() {
        $('#loader').delay(100).fadeOut('slow');
        $('#loader-wrapper').delay(500).fadeOut('slow');
        $('body').delay(500).css({
            'overflow': 'visible'
        });
    });
    $(document).on('click', '#open_navSidebar', function() {
        $('#offcanvas_menu').css('width', '250px');
        $("#sidebar_overlay").show();
        $('.inner-wrapper').css('overflow', 'hidden');
    });
    $(document).on('click', '#close_navSidebar', function() {
        $('#offcanvas_menu').css('width', '0px');
        $("#sidebar_overlay").hide();
        $('.inner-wrapper').css('overflow', 'scroll');
    });
    $(document).on('click', "#sidebar_overlay", function() {
        $('#offcanvas_menu').css('width', '0px');
        $("#sidebar_overlay").hide();
    });
    if ($(window).width() > 767) {
        if ($('.theiaStickySidebar').length > 0) {
            $('.theiaStickySidebar').theiaStickySidebar({
                additionalMarginTop: 20
            });
        }
    }
}());